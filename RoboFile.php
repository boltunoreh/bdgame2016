<?php

/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 21.01.2016
 * Time: 23:37
 */
class RoboFile extends \Robo\Tasks
{
    public function deployVagrant()
    {
        $this->yell('Deploying to Vagrant');

        $this
            ->_exec('composer config -g github-oauth.github.com 5260f87b04f608b43d06d4b51bd8c6577add4b70');

        $this
            ->taskExec('composer install')
            ->arg('--prefer-dist')
            ->arg('--no-interaction')
            ->run();

        $dbCreateOutput = $this
            ->taskExec('php app/console doctrine:database:create')
            ->arg('--if-not-exists')
            ->printed(false)
            ->run();

        $this
            ->taskExec('php app/console doctrine:schema:update')
            ->arg('--force')
            ->arg('--dump-sql')
            ->run();

        if (!preg_match('!skipped!is', $dbCreateOutput->getMessage())) {
            $this
                ->taskExec('php app/console fos:user:create admin admin@admin.ru admin')
                ->arg('--super-admin')
                ->run();
        }

        $this
            ->taskExec('php app/console assets:install')
            ->printed(false)
            ->run();

        foreach (['dev', 'prod'] as $env) {
            $this
                ->taskExec('php app/console cache:clear')
                ->option('--env', $env)
                ->run();
        }
    }
}
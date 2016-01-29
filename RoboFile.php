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

        if (!file_exists(__DIR__ . '/app/phpunit.xml')) {

            $this
                ->taskFilesystemStack()
                ->copy(__DIR__ . '/app/phpunit.xml.dist', __DIR__ . '/app/phpunit.xml')
                ->run();

        }
    }

    public function deployCodeship()
    {
        $this->yell('Deploying to Codeship');

        $this
            ->taskExec('php app/console doctrine:schema:update')
            ->option('--env', 'test')
            ->arg('--force')
            ->printed(false)
            ->run();

        $this
            ->taskExec('php app/console doctrine:fixtures:load -n')
            ->printed(false)
            ->run();

        $this
            ->taskExec('php app/console assets:install')
            ->printed(false)
            ->run();

        $this
            ->taskExec('php app/console cache:clear')
            ->option('--env', 'test')
            ->printed(false)
            ->run();

        $this
            ->taskFilesystemStack()
            ->copy(__DIR__ . '/app/phpunit.xml.dist', __DIR__ . '/app/phpunit.xml')
            ->run();

    }
}
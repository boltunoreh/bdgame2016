<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

if (isset($_SERVER['VAGRANT_ENV'])) {

    /**
     * @var ClassLoader $loader
     */
    $loader = require '/home/vagrant/vendor/autoload.php';
    $loader->setUseIncludePath(true);

    $json = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);

    foreach ($json['autoload']['psr-4'] as $key => $value) {
        set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../' . $value));
    }

    //special for symfony 2.8 god dammit
    $loader->addClassMap([
        'AppKernel' => realpath(__DIR__ . '/AppKernel.php'),
        'AppCache' => realpath(__DIR__ . '/AppCache.php')
    ]);


} else {
    /**
     * @var ClassLoader $loader
     */
    $loader = require __DIR__ . '/../vendor/autoload.php';
}


AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;

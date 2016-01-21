<?php

namespace VagrantUtils;

/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 19.06.2015
 * Time: 17:55
 */
class VagrantUtils
{

    static public function cloneVendor()
    {
        if (!isset($_SERVER['VAGRANT_ENV'])) {
            echo "Current environment is not a vagrant machine\nCloning process will not be performed...\n";
            return;
        }

        $currentVendorDir = __DIR__ . '/../vendor';
        $targetVendorDir = '/home/vagrant/vendor';

        echo "Clonning vendor folder outside shared directory for vagrant performance...\n";
        system("rm -rf $targetVendorDir && cp -ra $currentVendorDir $targetVendorDir");
    }
}
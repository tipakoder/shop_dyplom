<?php

namespace Core\Utils;

class Filesystem {
    public static function uploadFile($path, $file) : bool {
        if (move_uploaded_file($file['tmp_name'], __ROOTDIR__.$path)) {
            return true;
        }
        return false;
    }

    public static function removeDirectory($dir) : bool {
        if ($objs = glob(__ROOTDIR__.$dir."/*")) {
            foreach($objs as $obj) {
                is_dir($obj) ? self::removeDirectory($obj) : unlink($obj);
            }
        }
        return rmdir($dir);
    }
}
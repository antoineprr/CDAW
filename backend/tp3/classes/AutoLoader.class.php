<?php

class AutoLoader {

    public function __construct() {
        spl_autoload_register( array($this, 'load') );
        // spl_autoload_register(array($this, 'loadComplete'));
    }

    // This method will be automatically executed by PHP whenever it encounters an unknown class name in the source code
    private function load($className) {
        // compute path of the file to load if it exists
        // it is in one of these subdirectory '/classes/', '/model/', '/controller/'
        // if it is a model, load its sql queries file too in sql/ directory

        $dir = ["/classes/", "/model/", "/controller/"];
        $path = "";
        foreach ($dir as $d) {
            $path = __ROOT_DIR . $d . $className . ".class.php";
            if (is_readable($path)) {
                require_once($path);
                if ($d == "/model/") {
                    $path = __ROOT_DIR . "/sql/" . $className . ".sql.php";
                    if (is_readable($path)) {
                        require_once($path);
                    }
                }
            }
        }

    }
}

$__LOADER = new AutoLoader();
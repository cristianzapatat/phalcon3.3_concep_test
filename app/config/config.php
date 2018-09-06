<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'port'        => 3306,  
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'testPhalcon',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'routesDir'      => APP_PATH . '/routes/', 
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'utilitiesDir'   => APP_PATH . '/utilities/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],
    'crypt' => [
        'cipher'    => 'aes-256-ctr',
        'key'       => 'i$1^&/:%2@a1!R1Q<@{(e@*!<7u|R2~0',
        'separator' => '&*&'
    ],
    'common' => [
        'format'    => 'Y-m-d H:i:s',
        'increase'   => 'PT20M'
    ],
    'authorizeException' => [
        'login',
        'register'
    ]
]);

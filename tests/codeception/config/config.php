<?php
/**
 * Application configuration shared by all applications and test types
 */
return [
    'language' => 'en-US',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'fixtureDataPath' => '@tests/codeception/common/fixtures/data',
            'templatePath' => '@tests/codeception/common/templates/fixtures',
            'namespace' => 'tests\codeception\common\fixtures',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=pedi', // MySQL, MariaDB
            //'dsn' => 'sqlite:/path/to/database/file', // SQLite
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=pedi_test', // PostgreSQL
            //'dsn' => 'cubrid:dbname=demodb;host=localhost;port=33000', // CUBRID
            //'dsn' => 'sqlsrv:Server=localhost;Database=mydatabase', // MS SQL Server, sqlsrv driver
            //'dsn' => 'dblib:host=localhost;dbname=mydatabase', // MS SQL Server, dblib driver
            //'dsn' => 'mssql:host=localhost;dbname=mydatabase', // MS SQL Server, mssql driver
            //'dsn' => 'oci:dbname=//localhost:1521/mydatabase', // Oracle
            'username' => 'postgres',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];

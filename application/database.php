<?php

return [
    'type' => 'sqlite',
    'dsn' => 'sqlite:' . __DIR__ . '/../simple.sqlite3',
    'charset' => 'utf8',
	'prefix' => 'simple_',
	'debug' => true
];

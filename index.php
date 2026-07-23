<?php
session_start();

define('BASE_PATH', __DIR__);
define('BACKEND_PATH', __DIR__ . '/backend');

require_once BACKEND_PATH . '/config/constants.php';
require_once BACKEND_PATH . '/config/database.php';

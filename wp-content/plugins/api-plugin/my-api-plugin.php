<?php
/*
Plugin Name: API Plugin
Description: A simple plugin to integrate with an external API.
Version: 2.0
Author: Jamil
*/
require 'vendor/autoload.php';

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path(__FILE__) . 'includes/init.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/sync.php';

// Регистрация хуков активации и деактивации
register_activation_hook(__FILE__, 'my_api_plugin_activate');
register_deactivation_hook(__FILE__, 'my_api_plugin_deactivate');
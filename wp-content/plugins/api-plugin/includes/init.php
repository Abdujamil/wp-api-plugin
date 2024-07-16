<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Подключение автозагрузчика Composer
function my_api_plugin_init()
{
    if (file_exists(plugin_dir_path(__FILE__) . '../vendor/autoload.php')) {
        require_once plugin_dir_path(__FILE__) . '../vendor/autoload.php';
    } else {
        error_log('Autoload file not found: ' . plugin_dir_path(__FILE__) . '../vendor/autoload.php');
    }
}
add_action('init', 'my_api_plugin_init');

// Функция для получения логгера
function my_api_plugin_get_logger()
{
    $logger = new Monolog\Logger('my_api_plugin');
    $logger->pushHandler(new Monolog\Handler\StreamHandler(WP_CONTENT_DIR . '/logs/my_api_plugin.log', Monolog\Logger::DEBUG));
    return $logger;
}

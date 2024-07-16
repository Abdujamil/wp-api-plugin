<?php

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function my_api_plugin_sync_data(LoggerInterface $logger = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_api_plugin_tasks';

    $response = wp_remote_get('https://jsonplaceholder.typicode.com/todos');

    if (is_wp_error($response)) {
        if ($logger) {
            $logger->error('Error fetching data from API.');
        }
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $tasks = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        if ($logger) {
            $logger->error('Error decoding JSON response.');
        }
        return;
    }

    foreach ($tasks as $task) {
        $wpdb->replace($table_name, [
            'id' => $task['id'],
            'user_id' => $task['userId'],
            'title' => $task['title'],
            'completed' => $task['completed'] ? 1 : 0,
        ]);
    }

    if ($logger) {
        $logger->info('Data synchronized successfully.');
    }
}

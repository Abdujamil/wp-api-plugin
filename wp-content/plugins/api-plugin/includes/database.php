<?php

function my_api_plugin_activate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_api_plugin_tasks';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        title text NOT NULL,
        completed tinyint(1) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function my_api_plugin_deactivate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_api_plugin_tasks';
    // Опционально: Удаление таблицы при деактивации
    // $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

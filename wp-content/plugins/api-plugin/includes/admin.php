<?php


// Добавление страницы настроек в админке
add_action('admin_menu', 'my_api_plugin_menu');
function my_api_plugin_menu()
{
    add_menu_page(
        'API Plugin',
        'API Plugin',
        'manage_options',
        'my-api-plugin',
        'my_api_plugin_page',
        'dashicons-admin-generic',
        6
    );
}

add_action('admin_init', 'my_api_plugin_settings');
function my_api_plugin_settings()
{
    register_setting('my_api_plugin_options_group', 'my_api_plugin_option_name');

    add_settings_section(
        'my_api_plugin_settings_section',
        'API Settings',
        'my_api_plugin_section_callback',
        'my-api-plugin'
    );

    add_settings_field(
        'my_api_plugin_field',
        'API Key',
        'my_api_plugin_field_callback',
        'my-api-plugin',
        'my_api_plugin_settings_section'
    );
}

function my_api_plugin_section_callback()
{
    echo 'Enter your API settings below:';
}

function my_api_plugin_field_callback()
{
    $setting = get_option('my_api_plugin_option_name');
    echo "<input type='text' name='my_api_plugin_option_name' value='" . esc_attr($setting) . "' />";
}

function my_api_plugin_page()
{
    if (isset($_POST['sync_data'])) {
        $logger = my_api_plugin_get_logger();
        my_api_plugin_sync_data($logger);
        echo '<div class="notice notice-success is-dismissible"><p>Data synchronized successfully.</p></div>';
    }

    $results = [];
    if (isset($_POST['search'])) {
        $title = sanitize_text_field($_POST['title']);
        global $wpdb;

        $table_name = $wpdb->prefix . 'my_api_plugin_tasks';
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE title LIKE %s", '%' . $wpdb->esc_like($title) . '%'));
    }
?>
    <div class="wrap">
        <h1>My API Plugin</h1>
        <p>For sync your data from API to database, click the button below:</p>
        <form method="post" action="">
            <input type="hidden" name="sync_data" value="1">
            <?php submit_button('Sync Data'); ?>
        </form>

        <h2>Search Tasks</h2>
        <form method="post" action="">
            <input type="text" name="title" placeholder="Enter title">
            <?php submit_button('Search', 'primary', 'search'); ?>
        </form>

        <?php if (!empty($results)) : ?>
            <h3>Search Results</h3>
            <ul>
                <?php foreach ($results as $task) : ?>
                    <li><?php echo esc_html($task->title); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php
}

// Шорткод для вывода последних незавершенных задач
add_shortcode('my_api_tasks', 'my_api_plugin_shortcode');
function my_api_plugin_shortcode()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_api_plugin_tasks';
    $tasks = $wpdb->get_results("SELECT * FROM $table_name WHERE completed = 0 ORDER BY id DESC LIMIT 5");

    if (empty($tasks)) {
        return 'No tasks found.';
    }

    $output = '<ul>';
    foreach ($tasks as $task) {
        $output .= '<li>' . esc_html($task->title) . '</li>';
    }
    $output .= '</ul>';

    return $output;
}

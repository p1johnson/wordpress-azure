<?php
/*
Plugin Name: Prune Plugins
*/

if ( ! function_exists( 'add_action' ) ) {
    echo "Plugin cannot be called directly!";
    exit;
}

function ppi_prune_plugins() {
    error_log( "Prune plugins fired" );
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
    require_once ABSPATH . '/wp-admin/includes/file.php';
    $prune_plugins = getenv( "PRUNE_PLUGINS" );
    error_log( "PRUNE_PLUGINS environment variable: " . $prune_plugins );
    $plugins_to_prune = explode( ',' , $prune_plugins );
    error_log( "Count of plugins to prune: " . strval( count ( $plugins_to_prune ) ) );
    foreach ( $plugins_to_prune as $plugin_to_prune ) {
        error_log( $plugin_to_prune );
    }
    $plugins = get_plugins();
    $count = count( $plugins );
    error_log( "Count of installed plugins: " . strval( $count ) );
    foreach ($plugins as $filename => $plugin_data) {
        error_log( "Plugin filename: " . $filename );
        error_log( "Plugin name: " . $plugin_data["Name"]);
        if (in_array($plugin_data["Name"],$plugins_to_prune)) {
            error_log("Found " . $plugin_data["Name"] . " in plugins to prune");
            if (is_plugin_active($filename)) {
                error_log("Plugin is active, need to deactivate");
                deactivate_plugins( $filename );
            }
            error_log( "Deleting plugin " . $plugin_data["Name"]);
            delete_plugins( [ $filename] );
        }
    }
    
}

add_action( 'ppi_cron_hook' , 'ppi_prune_plugins' );

function ppi_cron_interval( $schedules ) { 
    $schedules['five_minutes'] = array(
        'interval' => 300,
        'display'  => esc_html__( 'Every Five Minutes' ), );
    return $schedules;
}

add_filter( 'cron_schedules', 'ppi_cron_interval' );

if ( ! wp_next_scheduled( 'ppi_cron_hook' ) ) {
    wp_schedule_event( time(), 'five_minutes', 'ppi_cron_hook' );
}

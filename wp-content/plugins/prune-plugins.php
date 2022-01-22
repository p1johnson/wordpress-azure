<?php
/*
Plugin Name: Prune Plugins
*/

if ( ! function_exists( 'add_action' ) ) {
    echo "Plugin cannot be called directly!";
    exit;
}

function ppi_prune_plugins() {
    $plugins=["Hello Dolly"];
    delete_plugins( $plugins );
}

add_action( 'ppi_cron_hook' , 'ppi_prune_plugins' );

function ppi_cron_interval( $schedules ) { 
    $schedules['five_seconds'] = array(
        'interval' => 300,
        'display'  => esc_html__( 'Every Five Minutes' ), );
    return $schedules;
}

add_filter( 'cron_schedules', 'ppi_cron_interval' );

if ( ! wp_next_scheduled( 'ppi_cron_hook' ) ) {
    wp_schedule_event( time(), 'five_minutes', 'ppi_cron_hook' );
}

?>
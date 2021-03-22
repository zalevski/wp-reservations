<?php

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'reservations';
$sql = "DROP TABLE IF EXISTS $table_name;";
$wpdb->query($sql);
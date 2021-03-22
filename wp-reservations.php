<?php

/**
* Plugin Name:  WordPress Reservations
* Description:  Sample plugin for Concise Software.
* Version:      1.0.1
* Author:       Arkadiusz Zalewski
* Author URI:   https://adius.pl/
* License:      GPL v2 or later
* License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

// 0 - prepare database on plugin activation
function reservationsTable()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'reservations';
    $sql = "CREATE TABLE `$table_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `name` varchar(220) DEFAULT NULL,
  `confirmation` boolean DEFAULT NULL,
  PRIMARY KEY(id)
  ) $charset_collate;
  ";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'reservationsTable');

// 1 - add custom admin menu page
function register_menu_page()
{
    add_menu_page(
        'Reservations',
        'Reservations',
        'manage_options',
        'wp-reservations',
        'reservationsAdminPage',
        'dashicons-list-view',
        4
    );
};
add_action('admin_menu', 'register_menu_page');

// 2, 3 - display reservation table with action buttons
function reservationsAdminPage()
{
    if (!current_user_can('manage_options')) {
        echo "Access denied";
        return;
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'reservations';
    if (isset($_POST['create'])) {
        $date = $_POST['date'];
        $name = $_POST['name'];
        $confirmation = $_POST['confirmation'] == 'on' ? 1 : 0;
        $wpdb->query("INSERT INTO $table_name(date, name, confirmation) VALUES('$date','$name', '$confirmation')");
    }
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $date = $_POST['date'];
        $name = $_POST['name'];
        $confirmation = $_POST['confirmation'] == 'on' ? 1 : 0;
        $wpdb->query("UPDATE $table_name SET date='$date', name='$name', confirmation='$confirmation' WHERE id='$id'");
    }
    if (isset($_POST['remove'])) {
        $id = $_POST['id'];
        $wpdb->query("DELETE FROM $table_name WHERE id='$id'");
    }
    include('wp-reservations-editor.php');
}

// 4 - hide for user roles other than admin (based on manage_options capability)
function hide_reservation_menu_item()
{
    if (!current_user_can('manage_options')) remove_menu_page('wp-reservations');
}
add_action('admin_menu', 'hide_reservation_menu_item');

// 5 - add new user role
function add_moderator_role()
{
    add_role('moderator', 'Moderator', array('manage_options' => true));
}
register_activation_hook(__FILE__, 'add_moderator_role');
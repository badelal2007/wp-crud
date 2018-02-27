<?php

/*
  Plugin Name: Press Room
  Description: Press Room developed by Bade lal | badelal2007@gmail.com
  Version: 1.0
  Author: Bade Lal | badelal2007@gmail.com
  Author URI: https://facebook.com/badelal143
 */

//https://github.com/azeemhassni/envalid
use validator\Validator;
require_once __DIR__ . '/vendor/autoload.php';

$validator = new Validator();

global $wpdb;

defined('WP_MYPLUGIN_PATH') or define('WP_MYPLUGIN_PATH', WP_PLUGIN_URL.'/press_room/assets');
defined('PLACE_HOLDER_IMG') or define('PLACE_HOLDER_IMG', WP_MYPLUGIN_PATH.'/images/200x100.png');
defined('TABLE_NAME') or define('TABLE_NAME', $wpdb->prefix . "PRESSROOM");
defined('TABLE_PK') or define('TABLE_PK', 'PRESSROOMID');
defined('RECORD_PER_PAGE') or define('RECORD_PER_PAGE', 10);
defined('IMAGE_TEXT_TYPE') or define('IMAGE_TEXT_TYPE', 'text'); // hidden OR text
defined('EDITOR_HEIGHT') or define('EDITOR_HEIGHT', 280); // hidden OR text
defined('ROOTDIR') or define('ROOTDIR', plugin_dir_path(__FILE__));

defined('VALIDATION_RULES') or define('VALIDATION_RULES', [
            'TITLE' => 'required--message=Please enter title',
            //'IMAGEPATH' => 'required',
            'THUMBNAIL' => 'required--message=Please upload thumbnail image',
            //'NEWSPAPERLINK' => 'required',
            'REMARKS' => 'required',
            'DESCRIPTION' => 'required--message=Please enter description',
            'MEDIATYPE' => 'required',
            'ACTIVE' => 'required',
        ]);


// function to create the DB / Options / Defaults					
function pr_options_install() {

    global $wpdb;
    
    //$table_name = $wpdb->prefix . "PRESSROOM";
    $table_name = TABLE_NAME;
    $table_pk = TABLE_PK;
    //$table_name = "MMM_PRESSROOM";
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            $table_pk int(11) NOT NULL AUTO_INCREMENT,
            `TITLE` varchar(250) NOT NULL,
            `IMAGEPATH` varchar(255) DEFAULT NULL,
            `NEWSPAPERLINK` varchar(300) DEFAULT NULL,
            `DESCRIPTION` varchar(1000) DEFAULT NULL,
            `REMARKS` text DEFAULT NULL,
            `ACTIVE` varchar(6) DEFAULT NULL,
            `CREATEDBY` int(11) DEFAULT NULL,
            `CREATED_DATETIME` datetime DEFAULT NULL,
            `UPDATEDBY` int(11) DEFAULT NULL,
            `UPDATED_DATETIME` datetime DEFAULT NULL,
            `MEDIATYPE` varchar(20) DEFAULT NULL,
            `THUMBNAIL` varchar(200) DEFAULT NULL,
            PRIMARY KEY ($table_pk)
          ) ENGINE=InnoDB AUTO_INCREMENT=100000000 $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'pr_options_install');

//menu items
add_action('admin_menu', 'mmm_press_room_modifymenu');

function mmm_press_room_modifymenu() {
    
    //this is the main item for the menu
    add_menu_page('Press Room', //page title
            'Press Room', //menu title
            'manage_options', //capabilities
            'mmm_press_room_list', //menu slug
            'mmm_press_room_list', //function
            '', //icon url
            4 //position
    );

    //this is a submenu
    add_submenu_page('mmm_press_room_list', //parent slug
            'Add New Press Room', //page title
            'Add New', //menu title
            'manage_options', //capability
            'mmm_press_room_create', //menu slug
            'mmm_press_room_create'); //function
    //this submenu is HIDDEN, however, we need to add it anyways
    add_submenu_page(null, //parent slug
            'Update Press Room', //page title
            'Update', //menu title
            'manage_options', //capability
            'mmm_press_room_update', //menu slug
            'mmm_press_room_update'); //function
}

require_once(ROOTDIR . 'pressroom-list.php');
require_once(ROOTDIR . 'pressroom-create.php');
require_once(ROOTDIR . 'pressroom-update.php');

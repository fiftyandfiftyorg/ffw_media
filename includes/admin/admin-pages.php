<?php
/**
 * Admin Pages
 *
 * @package     Fifty Framework Staff
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2013, Bryan Monzon
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;




/**
 * Creates the admin menu pages under Donately and assigns them their global variables
 *
 * @since  1.0
 * @global  $ffw_media_settings_page
  * @return void
 */
function ffw_media_add_menu_page() {
    global $ffw_staff_settings_page;

    $ffw_staff_settings_page = add_submenu_page( 'edit.php?post_type=ffw_media', __( 'Settings', 'ffw_media' ), __( 'Settings', 'ffw_media'), 'edit_pages', 'ffw-media-settings', 'ffw_media_settings_page' );
    
}
add_action( 'admin_menu', 'ffw_media_add_menu_page', 11 );

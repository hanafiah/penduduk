<?php

/*
  Plugin Name:  Penduduk
  Plugin URI:   http://ibnuyahya.com/
  Description:  Simple residents management
  Author:       Muhamad Hanafiah Yahya
  Author URI:   http://www.ibnuyahya.com
  Version:      0.0.1
  License:      MIT
  License URI:  http://opensource.org/licenses/MIT
 */

if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('PDDK_VERSION', '0.0.1');
define('PDDK_DB_VERSION', '1.0.0');
define('PDDK_MINIMUM_WP_VERSION', '3.2');

require_once('autoload.php');
require_once('pddk_config.php');


register_activation_hook(__FILE__, array('Pddk_Main', 'plugin_activation'));
register_deactivation_hook(__FILE__, array('Pddk_Main', 'plugin_deactivation'));
add_action('init', array('Pddk_Main', 'init'));
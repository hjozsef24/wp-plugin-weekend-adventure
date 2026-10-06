<?php

/*
 * Plugin Name: WP Plugin Weekend Adventure
 * Description: Weekend Adventure test task
 * Version: 1.0.0
 * Author: Hajdu József
*/

if (!defined('ABSPATH')) exit;

/* 
** Load classes 
*/
define('WA_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once WA_PLUGIN_DIR . 'includes/class-shortcode.php';

/*
** Initialize the plugin
*/
function init_wa() {}

add_action('plugins_loaded', 'init_wa');

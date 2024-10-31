<?php
/*
Plugin Name: Poll And Survey plugin
Plugin URI: https://techmix.xyz/downloads/poll-and-survey-plugin
Description: You can display customized poll and survey in your website. This plugin can place anywhere in any wordpress website. Poll And Survey plugin comes with widget and shortcode support. 
Version: 1.01
Text Domain: poll-and-survey-plugin
Domain Path: /languages
Author: TechMix
Author URI: https://techmix.xyz/
*/



defined('ABSPATH') or die();

/**
 * required all file
 */
require_once dirname(__FILE__) . '/inc/init.php';
require_once dirname(__FILE__) . '/inc/Base/class-deactivate.php';

use Pasp\Inc\Init;
use Pasp\Inc\Base\Deactivate;

/**
 * the code that runs during plugin deactivate 
 */

if (!function_exists('pasp_deactivate')) {
    function pasp_deactivate()
    {
        Deactivate::deactivate();
    }
    register_deactivation_hook( __FILE__, 'pasp_deactivate' );
}

/**
 * init this plugin all services execution by below this condition 
 */
if (class_exists( Init::class )) {
    Init::RegisterServices();
}
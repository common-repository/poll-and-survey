<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Base;
require_once \dirname(__FILE__) . '/class-base-controller.php';
require_once \dirname(__FILE__) . '../../Pages/shortcode.php';
use Pasp\Inc\Base\BaseController;
use Pasp\Inc\Pages\ShortCode;

class Enqueue extends BaseController
{
    public function pasp_register()
    {
        add_action( 'admin_enqueue_scripts', array( $this,'PaspEnqueueStylesheetAndJs' ) );
        add_action( 'wp_enqueue_scripts', array( $this,'frontendPaspEnqueueStylesheetAndJs' ) );
        add_action( 'wp_ajax_pasp_poll_action', array( $this,'pasp_poll_ajax_vote' ) );
        add_action( 'wp_ajax_nopriv_pasp_poll_action', array( $this,'pasp_poll_ajax_vote' ) );
    }

    public function PaspEnqueueStylesheetAndJs()
    {
        wp_enqueue_script('media-upload');
		wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_script('thickbox');
        wp_enqueue_style( 'pasp_main_css', $this->plugin_url . 'assets/css/main.css' );

        wp_enqueue_script( 'pasp_main_js', $this->plugin_url . 'assets/js/main.js' );
    }

    public function frontendPaspEnqueueStylesheetAndJs()
    {
        wp_enqueue_style( 'pasp_main_css', $this->plugin_url . 'assets/css/frontend.css' );
    }
    

}
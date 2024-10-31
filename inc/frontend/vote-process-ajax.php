<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Frontend;
require_once \dirname(__FILE__) . '../../Base/class-base-controller.php';
require_once \dirname(__FILE__) . '../../Pages/shortcode.php';
use Pasp\Inc\Base\BaseController;
use Pasp\Inc\Pages\ShortCode;

class VoteProcess extends BaseController
{
    public $shortcode;
    public function pasp_register()
    {
        add_filter( 'single_template', array($this,'pasp_poll_get_single_template') );
        add_action( 'wp_enqueue_scripts', array($this,'paspFrontendcssJsEnqueue') );
        add_action( 'wp_ajax_pasp_poll_action', array($this,'pasp_poll_ajax_vote') );
        add_action( 'wp_ajax_nopriv_pasp_poll_action', array($this,'pasp_poll_ajax_vote') );

        $this->shortcode = new ShortCode();

    }

    public function paspFrontendcssJsEnqueue()
    {
        wp_enqueue_script( 'pasp_poll_ajax', $this->plugin_url . 'assets/js/frontend.js',array('jquery') );
        wp_localize_script( 'pasp_poll_ajax', 'pasp_poll_ajax_obj', array('ajax_url' => admin_url( 'admin-ajax.php' )) );
    }

    public function pasp_poll_get_single_template( $content )
    {
        global $post;
        if ($post->post_type == 'polls_survey') {
            $content = $this->plugin_path . 'templates/pasp_poll_template.php';
        }
        
        return $content;
    }

    public function pasp_poll_ajax_vote()
    {
        if (isset($_POST['action'])) {
            \session_start();
            if (isset($_POST['poll_id'])) {
                $poll_id = \intval( sanitize_text_field( $_POST['poll_id'] ) );
            }

            if (isset($_POST['option_id'])) {
                $option_id = (float) sanitize_text_field( $_POST['option_id'] );
            }
            if ( !$poll_id ) {
                $poll_id = '';
                $_SESSION['pasp_poll_session'] = \uniqid();
            }

            if ( !$option_id ) {
                $option_id = '';
                $_SESSION['pasp_poll_session'] = \uniqid();
            }

            $oldest_vote = 0;
            $oldest_total_vote = 0;
            if (get_post_meta($poll_id,'pasp_poll_vote_count_'.$option_id,true)) {
                $oldest_vote = get_post_meta($poll_id,'pasp_poll_vote_count_'.$option_id,true);
            }

            if (get_post_meta($poll_id,'pasp_poll_vote_total_count',true)) {
                $oldest_total_vote = get_post_meta($poll_id,'pasp_poll_vote_total_count',true);
            }

            if (!self::check_unique_vote($poll_id,$option_id)) {
                $new_total_vote = \intval( $oldest_total_vote ) + 1;
                $new_vote = (int)$oldest_vote + 1;
    
                update_post_meta( $poll_id, 'pasp_poll_vote_count_'.$option_id, $new_vote );
                update_post_meta( $poll_id, 'pasp_poll_vote_total_count', $new_total_vote );

                $getdata = array();
                $getdata['total_vote_count'] = $new_total_vote;
                $getdata['total_opt_vote_count'] = $new_vote;
                $getdata['option_id'] = $option_id;
                $getdata['voting_status'] = "done";
                $getdataPercentage = ($new_vote*100)/$new_total_vote;
                $getdata['total_vote_percentage'] = (int)$getdataPercentage;
                $_SESSION['pasp_poll_session_'.$poll_id] = \uniqid();
                
                print_r(json_encode($getdata));
            }

        }

        die();
    }

    public static function check_unique_vote($poll_id,$option_id)
    {
        if (isset( $_SESSION['pasp_poll_session_'.$poll_id] )) {
            return true;
        }else{
            return false;
        }

        if (isset( $_SESSION['pasp_poll_session'] )) {
            return true;
        }else{
            return false;
        }
    }
}
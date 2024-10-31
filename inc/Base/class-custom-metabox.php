<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Base;
require_once \dirname(__FILE__) . '../../Api/class-settings-api.php';
require_once \dirname(__FILE__) . '../../Api/Callbacks/class-posttype-callbacks.php';
use Pasp\Inc\Api\SettingsApi;
use Pasp\Inc\Api\Callbacks\PostTypeCallbacks;

class CustomMetaBox
{
    public $settings;
    public $postCallbacks;
    public $add_metabox = array();

    public function pasp_register()
    {
        $this->settings = new SettingsApi();
        $this->postCallbacks = new PostTypeCallbacks();
        $this->pasp_poll_metabox();
        $this->settings->addMetaBox( $this->add_metabox )->pasp_register();
    }

    /**
     * @method pasp_poll_metabox
     * @param null
     * create custom meta box 
     */
    public function pasp_poll_metabox()
    {
        $this->add_metabox = array(
            array(
                'id' => 'pasp_poll_',
                'title' => __( 'Add Poll Options', 'poll-and-survey-plugin' ),
                'callback' => array($this->postCallbacks,'pasp_polls_metabox_form'),
                'screen' => 'polls_survey',
                'context' => 'normal',
                'priority' => 'high'
            ),
        );
    }
}
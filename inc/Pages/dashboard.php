<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Pages;
require_once \dirname(__FILE__) . '../../Api/class-settings-api.php';
require_once \dirname(__FILE__) . '../../Api/Callbacks/class-dashboard-callback.php';
use Pasp\Inc\Api\SettingsApi;
use Pasp\Inc\Api\Callbacks\DashboardCallback;
class Dashboard
{
    public $settings;
    public $dashboardCallbacks;
    public $pages = array();
    public $sub_pages = array();
    public function pasp_register()
    {
        $this->settings = new SettingsApi();
        $this->dashboardCallbacks = new DashboardCallback();
        $this->setPages();
        $this->settings->addPages($this->pages)->duplicateMainMenu('Dashboard')->addSubPages($this->sub_pages)->pasp_register();
    }

    /**
     * @method setPages
     * @param null
     * array attribute for main menu
     */
    public function setPages()
    {
        $this->pages = array(
            array(
                'page_title'    => 'Poll and Survey plugin for WordPress',
                'menu_title'    => 'POLL REPORT',
                'capability'    => 'manage_options',
                'menu_slug'     => sanitize_key('polls_survey_menu'),
                'callback'      => array($this->dashboardCallbacks,'pasp_poll_result_callback'),
                'icon_url'      =>  'dashicons-chart-bar',
                'position'      =>  55
            )
        );
    }
}

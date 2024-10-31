<?php
/**
 * @package poll and survey plugin for wordpress
 */

namespace Pasp\Inc\Base;
require_once \dirname(__FILE__) . '/class-base-controller.php';

class SettingsLink extends BaseController
{
    public function pasp_register()
    {
        add_filter( 'plugin_action_links_'.$this->plugin_name, array($this,'settingsLinkUrl'), 10, 2 );
    }

    public function settingsLinkUrl( $links )
    {
        $url = esc_url( add_query_arg( 'page', 'polls_survey_menu', get_admin_url() . 'admin.php' ) );
        $settings_link = '<a href="'.esc_attr( $url ).'">'.esc_html__( 'Settings', 'poll-and-survey-plugin' ).'</>';
        array_unshift($links,$settings_link);
        return $links;
    }
}

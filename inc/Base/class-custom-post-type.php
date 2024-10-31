<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Base;

class CustomPostType
{
    public function pasp_register()
    {
        add_action( 'init', array($this,'createCustomPostType') );
        add_filter( 'manage_polls_survey_posts_columns', array( $this,'pasp_polls_survey_columns' ) );
        add_action('manage_polls_survey_posts_custom_column', array($this,'pasp_polls_survey_columns_content'), 10, 2);
    }

    /**
     * create custom post type
     * @method createCustomPostType
     * @param null
     */
    public function createCustomPostType()
    {
        register_post_type( 'polls_survey', array(
            'labels'    => array(
                'name'                =>  'Poll and Survey',
                'singular_name'       =>  'Polls and Survey',
                'menu_name'           => __( 'PASP POLL', 'poll-and-survey-plugin' ),
                'name_admin_bar'      => __( 'PASP POLL', 'poll-and-survey-plugin' ),
                'parent_item_colon'   => __( 'Parent Poll:', 'poll-and-survey-plugin' ),
                'all_items'           => __( 'All Polls', 'poll-and-survey-plugin' ),
                'add_new_item'        => __( 'Add New Poll', 'poll-and-survey-plugin' ),
                'add_new'             => __( 'Add New', 'poll-and-survey-plugin' ),
                'new_item'            => __( 'New Poll', 'poll-and-survey-plugin' ),
                'edit_item'           => __( 'Edit Poll', 'poll-and-survey-plugin' ),
                'update_item'         => __( 'Update Poll', 'poll-and-survey-plugin' ),
                'view_item'           => __( 'View Poll', 'poll-and-survey-plugin' ),
                'search_items'        => __( 'Search Poll', 'poll-and-survey-plugin' ),
                'not_found'           => __( 'Not found', 'poll-and-survey-plugin' ),
                'not_found_in_trash'  => __( 'Not found in Trash', 'poll-and-survey-plugin' ),
            ),
            'public'    =>  true,
            'has_archive'   =>  true,
            'menu_icon'  =>  'dashicons-list-view',
            'supports'   => array( 'title','author','thumbnail','revisions'),
        ) );
    }

    /**
     * create custom columns for custom post type
     * @method pasp_polls_survey_columns
     * @param $columns
     */
    public function pasp_polls_survey_columns( $columns )
    {
        $newColumns = array();
        $newColumns['title']    = 'Poll Title';
        $newColumns['Date'] = 'Date';
        $newColumns['poll_option']  =   'Total Poll Option';
        $newColumns['shortcode']    =   'Shortcode';
        $newColumns['result']   =   'View Result';
        return $newColumns;
    }
    /**
     * create custom column content
     * @method pasp_polls_survey_columns_content
     * @param $column, $post_id
     */
    public function pasp_polls_survey_columns_content($column, $post_id)
    {
        switch ($column) {
            case 'poll_option':
                if (get_post_meta( $post_id, 'pasp_poll_option', true )) {
                    $poll_options = get_post_meta( $post_id, 'pasp_poll_option', true );
                }
                echo \sizeof($poll_options);
                break;
            case 'shortcode':
                $shortcode = '<code>[PASP_POLL id="'.$post_id.'"]</code>';
                echo $shortcode;
                break;
            case 'result':
                echo "<a target='_blank' href='".admin_url('admin.php?page=polls_survey_menu&view=results&id='.$post_id)."' class='button button-primary'>View</a>";
                break;
            default:
                echo "";
                break;
        }
    }
}
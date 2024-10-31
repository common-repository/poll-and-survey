<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Api\Callbacks;

class PostTypeCallbacks
{
    public function pasp_save_options()
    {
        add_action( 'save_post', array($this,'pasp_poll_survey_save_options') );
    }

    public function pasp_polls_metabox_form( $post )
    {
        wp_nonce_field( 'pasp_poll_survey_nonce_id', 'pasp_poll_metabox_nonce' );

        $pasp_poll_type = get_post_meta( $post->ID, 'pasp_poll_type', true );
        if (get_post_meta( $post->ID, 'pasp_poll_option', true )) {
            $pasp_poll_option = get_post_meta( $post->ID, 'pasp_poll_option', true );
        }
        $pasp_poll_option_id = get_post_meta($post->ID, 'pasp_poll_option_id',true);
        $pasp_poll_option_image = get_post_meta( $post->ID, 'pasp_poll_option_image', true );
        $pasp_poll_vote_total_count = (int)get_post_meta($post->ID, 'pasp_poll_vote_total_count',true);

        if(($post->post_type == 'polls_survey') && isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
                <div class="pasp_poll_shortcode">
                    <?php _e('Shortcode for this poll is : <code>[PASP_POLL id="'.$post->ID.'"]</code> (Insert it anywhere in your post/page and show your poll)','it_epoll');?>
                </div>
        <?php }?>
        <div class="pasp_poll_type_area">
            <label for="pasp_poll_type_field">Select Poll Type:</label>
            <select id="pasp_poll_type_field" name="pasp_poll_type_field" onchange="pollType(this);">
                <option value="">Select Poll Style</option>
                <option <?php selected( $pasp_poll_type,'ipoll' )?> value="ipoll">Image Poll</option>
            </select>
        </div>
        <div id="pasp_text_poll" style="display: block;">
            <div class="pasp_form" id="pasp_poll_append_option_field">
            <table id="pasp_poll_append_option_field_table">
            
                <?php 
                if (!empty( $pasp_poll_option )) : 
                        $i = 0;
                        foreach ( $pasp_poll_option as $pasp_poll_opt ) :
                            $pollKey = (float)$pasp_poll_option_id[$i];
                            $pasp_poll_vote_count = (int)get_post_meta($post->ID, 'pasp_poll_vote_count_'.$pollKey,true);
    
                            if(!$pasp_poll_vote_count){
                                $pasp_poll_vote_count = 0;
                            }
                ?>
                <tr class="pasp_poll_append_option_field_table_tr">
                    <td>
                        <table id="pasp_poll_append_option_field_table">
                        <tr>
                        <td>Option Name: </td>
                        <td>
                            <input type="text" name="pasp_poll_option[]" id="pasp_poll_option" class="widefat" value="<?php echo esc_attr( $pasp_poll_opt,'poll-and-survey-plugin' ) ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Option Image: </td>
                        <td>
                        <input type="url" id="pasp_poll_option_image" name="pasp_poll_option_image[]" class="widefat" value="<?php if($pasp_poll_option_image) echo esc_attr( $pasp_poll_option_image[$i],'poll-and-survey-plugin' ) ?>" />
                        <input type="hidden" name="pasp_poll_option_id[]" id="pasp_poll_option_id" value="<?php echo esc_attr($pasp_poll_option_id[$i],'poll-and-survey-plugin');?>"/>
                        </td>
                        <td><input type="button" class="button" name="pasp_poll_option_button" id="pasp_poll_option_button" value="Upload" /></td>
                    </tr>
                    <tr>
                            <td>Vote: </td>
                            <td><input type="text" name="pasp_poll_vote_count[]" id="pasp_poll_vote_count" value="<?php echo esc_attr($pasp_poll_vote_count,'poll-and-survey-plugin')?>" disabled /></td>
                    </tr>
                        </table>
                    </td>
                </tr>
                    
                        <?php $i++;
                    endforeach;
                    endif; ?>
            </table>
            </div>
            <!-- end pasp_poll_option_field -->
            <div class="pasp_add_option_btn_area" >
                <button type="button" class="pasp_add_option_btn"><i class="dashicons-before dashicons-plus-alt"></i> Add Options</button>
            </div>
        </div>
        <?php

    }
    public function pasp_poll_survey_save_options( $post_id )
    {
        /**
         * check that nonce is set
         */
        if (!isset( $_POST['pasp_poll_metabox_nonce'] )) {
            return;
        }

        if (!wp_verify_nonce( $_POST['pasp_poll_metabox_nonce'], 'pasp_poll_survey_nonce_id' )) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        /** 
         * Check the user's permissions.
         */
        if ( isset( $_POST['post_type'] ) && 'polls_survey' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        /**
         * update poll type
         */
        if (isset($_POST['pasp_poll_type_field'])) {
            $pasp_poll_type = sanitize_text_field( $_POST['pasp_poll_type_field'] );
            update_post_meta( $post_id, 'pasp_poll_type', $pasp_poll_type );
        }
        /**
         * update poll option
         */
        if (isset($_POST['pasp_poll_option'])) {
            $pasp_poll_options = array_map('esc_attr',$_POST['pasp_poll_option']);
            $pasp_poll_option = array();
            foreach ($pasp_poll_options as $pasp_poll_option_key) {
                if ($pasp_poll_option_key) {
                    array_push($pasp_poll_option,sanitize_text_field( $pasp_poll_option_key ));
                }
            }
            update_post_meta( $post_id, 'pasp_poll_option', $pasp_poll_option );
        }

        /**
         * update poll option id
         */
        if (isset($_POST['pasp_poll_option_id'])) {
            $pasp_poll_option_ids = array_map('esc_attr',$_POST['pasp_poll_option_id']);
            $pasp_poll_option_id = array();
            foreach ($pasp_poll_option_ids as $pasp_poll_option_id_key) {
                if ($pasp_poll_option_id_key) {
                    array_push($pasp_poll_option_id,sanitize_text_field( $pasp_poll_option_id_key ));
                }
            }
            update_post_meta( $post_id, 'pasp_poll_option_id', $pasp_poll_option_id );
        }

        /**
         * update poll image
         */
        if (isset( $_POST['pasp_poll_option_image'] )) {
             $pasp_poll_option_images = array_map('esc_attr',$_POST['pasp_poll_option_image']);
            $pasp_poll_option_image = array();
            foreach ($pasp_poll_option_images as $pasp_poll_option_image_key) {
                if ($pasp_poll_option_image_key) {
                    array_push($pasp_poll_option_image,sanitize_text_field( $pasp_poll_option_image_key ));
                }
            }
            update_post_meta( $post_id, 'pasp_poll_option_image', $pasp_poll_option_image );
        }

        
    }

    
}
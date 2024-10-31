<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Pages;
require_once \dirname(__FILE__) . '/../frontend/vote-process-ajax.php';
use Pasp\Inc\Frontend\VoteProcess;
class ShortCode
{
    public function pasp_register()
    {
        add_shortcode( 'PASP_POLL', array($this,'paspPollShortcode') );
    }
    
    public function paspPollShortcode($atts)
    {
        \extract(shortcode_atts( array(
            'id'    =>  '1',
            'use_for'   =>  'post',
            'type'  =>  ''
        ), $atts ));
        
        $args = array(
            'post_type' =>  array('polls_survey'),
            'post_status'   =>  array('publish'),
            'order' =>  'DESC',
            'orderby'   =>  'date',
            'p' =>  $id,
        );

        $q = new \WP_Query($args);

        \ob_start();

            if( $q->have_posts() ){
                while( $q->have_posts() ) : $q->the_post();
                $pasp_poll_option_names = array();
                if (get_post_meta( get_the_id(), 'pasp_poll_option', true )) {
                    $pasp_poll_option_names = get_post_meta( get_the_id(), 'pasp_poll_option', true );
                }
                $pasp_poll_option_images = array();
                if (get_post_meta( get_the_id(), 'pasp_poll_option_image', true )) {
                    $pasp_poll_option_images = get_post_meta( get_the_id(), 'pasp_poll_option_image', true );
                }
                $pasp_poll_option_id = get_post_meta( get_the_id(), 'pasp_poll_option_id', true );
                if ($type) {
                    $pasp_poll_type = $type;
                }else{
                    $pasp_poll_type = get_post_meta( get_the_id(), 'pasp_poll_type' , true );
                }
                
                $pasp_poll_vote_total_count = (int)get_post_meta(get_the_id(), 'pasp_poll_vote_total_count',true);
                
                ?>
                <div class="pasp_poll_container">
                    <h1 class="pasp_poll_title"><?php the_title(); ?></h1>
                    <div class="single-poll-wrapper">
                <?php
                $i = 0;
                foreach ($pasp_poll_option_names as $pasp_poll_option_name) :
                    $pasp_poll_vote_count = (int)get_post_meta( get_the_id(), 'pasp_poll_vote_count_'.(float)$pasp_poll_option_id[$i], true );
                    $pasp_poll_vote_percentage =0;
                    if($pasp_poll_vote_count == 0){
                    $pasp_poll_vote_percentage =0;
                    }else{
                    $pasp_poll_vote_percentage = (int)$pasp_poll_vote_count*100/$pasp_poll_vote_total_count; 
                    }
                    $pasp_poll_vote_percentage = (int)$pasp_poll_vote_percentage;
                    if ($pasp_poll_type == 'ipoll') :
                    ?>
                    <div class="single-poll-item">
                    
                        <div class="pasp_poll_img_thumb">
                            <img src="<?php echo esc_url($pasp_poll_option_images[$i])?>" alt="" />
                        </div>
                        <h3><?php echo esc_html( $pasp_poll_option_name,'poll-and-survey-plugin' )?></h3>
                        <div class="pasp_poll_action">
                            <?php 
                                if (!VoteProcess::check_unique_vote(get_the_ID(),$pasp_poll_option_id[$i])) :
                            ?>
                            <form action="" name="pasp_poll_action_form" class="pasp_poll_action_form">
                                <input type="hidden" name="pasp_poll-id" id="pasp_poll-id" value="<?php echo get_the_ID()?>" />
                                <input type="hidden" name="pasp_poll-option-id" id="pasp_poll-option-id" value="<?php echo esc_attr( $pasp_poll_option_id[$i],'poll-and-survey-plugin' ); ?>">
                                <input type="button" name="pasp_poll_vote_button" id="pasp_poll_vote_button" value="Vote">
                            </form>
                            <?php else: ?>
                                <span> You already Participated</span>
                            <?php endif; ?>
                            
                        </div>
                        <div class="pasp_poll_result">
                            <span class="vote_percentage"><?php echo $pasp_poll_vote_percentage; ?>%</span>
                            <span class="vote_process"><?php echo $pasp_poll_vote_count ."/".$pasp_poll_vote_total_count; ?></span>
                        </div>
                    </div>
                    <!-- end single-poll-item -->
                    <?php
                    else:
                        ?>
                    <div class="single-poll-item">
                        <div class="pasp_list_poll_type_container">
                            <h3><?php echo esc_html( $pasp_poll_option_name,'poll-and-survey-plugin' )?></h3>
                            <div class="pasp_poll_action">
                                <?php 
                                    if (!VoteProcess::check_unique_vote(get_the_ID(),$pasp_poll_option_id[$i])) :
                                ?>
                                <form action="" name="pasp_poll_action_form" class="pasp_poll_action_form">
                                    <input type="hidden" name="pasp_poll-id" id="pasp_poll-id" value="<?php echo get_the_ID()?>" />
                                    <input type="hidden" name="pasp_poll-option-id" id="pasp_poll-option-id" value="<?php echo esc_attr( $pasp_poll_option_id[$i],'poll-and-survey-plugin' ); ?>">
                                    <input type="button" name="pasp_poll_vote_button" id="pasp_poll_vote_button" value="Vote">
                                </form>
                                <?php else: ?>
                                    <span> You already Participated</span>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                        <div class="pasp_poll_result">
                            <span class="vote_percentage"><?php echo $pasp_poll_vote_percentage; ?>%</span>
                            <span class="vote_process"><?php echo $pasp_poll_vote_count ."/".$pasp_poll_vote_total_count; ?></span>
                        </div>
                    </div>
                    <!-- end single-poll-item -->
                        <?php
                    endif;
                    $i++;
                    endforeach;
                    ?>
                </div>
    <!-- end single-poll-wrapper -->
            </div>
                <?php
                endwhile;
            }
        $output = \ob_get_contents();
        \ob_end_clean();
        return $output;
        // Restore original Post Data
        wp_reset_postdata();
    }
}
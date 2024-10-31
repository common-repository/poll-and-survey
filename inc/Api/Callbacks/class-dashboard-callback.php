<?php
/**
 * @package poll and survey plugin for wordpress
 */
namespace Pasp\Inc\Api\Callbacks;

class DashboardCallback
{
    public function pasp_poll_result_callback()
    {
        if (isset($_REQUEST['view'])) {
            $postID = sanitize_key( $_GET['id'] );
            ?>
            <div class="pasp_wrap wrap" style="position: relative;">
                <h1 class="heading"><?php esc_html_e( 'Voting Results', 'poll-and-survey-plugin' )?></h1>
                <table class="wp-table widefat fixed striped posts">
                    <thead>
                        <tr>
                            <th>
                                <a href="<?php echo admin_url('admin.php?page=polls_survey_menu');?>" class=""><i class="dashicons dashicons-arrow-left-alt"></i><?php esc_html_e( 'Go Back', 'poll-and-survey-plugin' )?></a>
                            </th>
                            <th style="text-align: center;">
                                <a href="<?php echo admin_url('post-new.php?post_type=polls_survey');?>" class=""><i class="dashicons dashicons-list-view"></i> <?php esc_html_e( 'Create New Poll', 'poll-and-survey-plugin' )?></a>
                            </th>
                            <th style="text-align: right;">
                                
                            </th>
                        </tr>
                    </thead>
                </table>
                <table class="wp-table widefat">
                    <thead>
                        <tr>
                            <th>Option Name</th>
                            <th>Total Vote</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if (get_post_meta( $postID, 'pasp_poll_option', true )) {
                            $pasp_poll_option = get_post_meta( $postID, 'pasp_poll_option', true );
                        }
                        $pasp_poll_option_id = get_post_meta($postID, 'pasp_poll_option_id',true);
                        $pasp_poll_vote_total_count = (int)get_post_meta($postID, 'pasp_poll_vote_total_count',true);
                        $i = 0;
                        
                        foreach ($pasp_poll_option as $pasp_poll_option_value) :
                            $pollKey = (float)$pasp_poll_option_id[$i];
                            $pasp_poll_vote_count = (int)get_post_meta($postID, 'pasp_poll_vote_count_'.$pollKey,true);

                            if(!$pasp_poll_vote_count){
                                $pasp_poll_vote_count = 0;
                            }
                            
                    ?>
                        <tr>
                            <td>
                                <?php echo $pasp_poll_option_value;?>
                            </td>
                            <td>
                                <?php echo $pasp_poll_vote_count.'/'.$pasp_poll_vote_total_count;?>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                        <?php $i++; endforeach;?>
                    </tbody>
                </table>
            </div>
            <!-- end wrap class -->
            <?php
        }else{
            ?>
                <div class="pasp_wrap wrap" style="position: relative;">
                    <h1 class="heading"><?php esc_html_e( 'Voting Results', 'poll-and-survey-plugin' )?></h1>
                    <table class="wp-table widefat fixed striped posts">
                        <thead>
                            <tr>
                                <th>
                                
                                </th>
                                <th style="text-align: center;">
                                    <a href="<?php echo admin_url('post-new.php?post_type=polls_survey');?>" class=""><i class="dashicons dashicons-list-view"></i> <?php esc_html_e( 'Create New Poll', 'poll-and-survey-plugin' )?></a>
                                </th>
                                <th style="text-align: right;">
                                    
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <table class="wp-table widefat">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Poll Name</th>
                                <th>Date</th>
                                <th>Total Votes</th>
                                <th>Total Options</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $args = array(
                                    'post_type'     =>  array('polls_survey'),
                                    'post_status'   =>  array('publish'),
                                    'nopaging'      =>  false,
                                    'paged'         =>  '0',
                                    'posts_per_page'    =>  '20',
                                    'order'         =>  'DESC'
                                );

                                $pasp_poll_query = new \WP_Query($args);
                                if ($pasp_poll_query->have_posts()) :
                                    while ($pasp_poll_query->have_posts()) : $pasp_poll_query->the_post();

                                    ?>
                                        <tr>
                                            <td> <?php the_ID();?> </td>
                                            <td><?php the_title(); ?></td>
                                            <td><?php echo get_the_date()?></td>
                                            <td>
                                                <?php 
                                                    if ( get_post_meta( get_the_id(), 'pasp_poll_vote_total_count', true ) ) {
                                                        echo get_post_meta( get_the_id(), 'pasp_poll_vote_total_count', true );
                                                    }else{
                                                        echo '0';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if ( get_post_meta( get_the_id(), 'pasp_poll_option', true ) ) {
                                                        echo \sizeof(get_post_meta( get_the_id(), 'pasp_poll_option', true ));
                                                    }else{
                                                        echo '0';
                                                    }
                                                ?>
                                            </td>
                                            <td><a href="<?php echo admin_url('admin.php?page=polls_survey_menu&view=results&id='.get_the_id()) ?>" class='button button-primary'>View</a></td>
                                        </tr>
                                    <?php
                                    endwhile;
                                endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- end wrap class -->
            <?php
        }
    }
}
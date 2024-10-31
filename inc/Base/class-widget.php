<?php
/**
 * @package poll and survey plugin for wordpress
 */


//Registering Widget
function pasp_poll_rigister_widget() {
	register_widget( 'PaspWidget' );
}
add_action( 'widgets_init', 'pasp_poll_rigister_widget' );
// Creating the widget 
class PaspWidget extends WP_Widget {
 
    public function __construct() {
        parent::__construct('pasp_widget','PASP POLL',array(
            'description'   =>  'Add PASP Poll via widget in sidebar'
        ));
    }

    public function widget($args, $instance)
    {
            $poll_id = $instance['pasp_poll_id'];
            $pasp_poll_type = $instance['pasp_poll_type'];
            echo $args['before_widget'];
            echo $args['before_title'];
            echo do_shortcode( '[PASP_POLL id="'.$poll_id.'" type="'.$pasp_poll_type.'" use_for="widget" ]' );
            echo $args['after_title'];
            echo $args['after_widget'];
        
    }

    public function form( $instance ) 
    {
        if (isset( $instance['pasp_poll_id'] )) {
            $poll_id = $instance['pasp_poll_id'];
        }else{
            $poll_id = 1;
        }
        if (isset( $instance[ 'pasp_poll_type' ] )) {
            $pasp_poll_type = $instance[ 'pasp_poll_type' ];
        }else{
            $pasp_poll_type = 'tpoll';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'pasp_poll_id' ); ?>">Select Poll: </label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'pasp_poll_id' ); ?>" name="<?php echo $this->get_field_name( 'pasp_poll_id' ); ?>">
                <option value="0">Choose Poll: </option>
                <?php 
                    $pasp_args = array(
						'post_type'              => array( 'polls_survey' ),
						'post_status'            => array( 'publish' ),
						'nopaging'               => false,
						'paged'                  => '0',
						'posts_per_page'         => '20',
						'order'                  => 'DESC',
                    );
                    
                    $q = new WP_Query($pasp_args);
                    if ($q->have_posts()) :
                        while ($q->have_posts()) : $q->the_post();
                            ?>
                                <option value="<?php echo get_the_id(); ?>" <?php echo ($poll_id == get_the_id()) ? 'selected' : '';?> ><?php echo the_title(); ?></option>
                            <?php
                        endwhile;
                    endif;
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'pasp_poll_type' )?>">Choose Poll Type</label>
            <select class="widefat" name="<?php echo $this->get_field_name( 'pasp_poll_type' )?>" id="<?php echo $this->get_field_id( 'pasp_poll_type' )  ?>">
                    <option value="tpoll" <?php echo $pasp_poll_type == 'tpoll' ? 'selected' : '' ?>>Text Poll</option>
                    <option value="ipoll" <?php echo $pasp_poll_type == 'ipoll' ? 'selected' : '' ?>>Image Poll</option>
            </select>
        </p>
        <?php 
    }
}
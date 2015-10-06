<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Counter extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_counter',
            __('Maskitto: Counter', 'maskitto-light'),
            array(
                'description' => __( 'Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-chart-line',
                'panels_groups' => 'theme-widgets'
            )
        );
    }

    /* Front-end display of widget. */
    public function widget( $args, $r ) {

        /* Get variables */
        $counter1_number = isset( $r['counter1_number'] ) ? esc_attr( $r['counter1_number'] ) : '';
        $counter1_title  = isset( $r['counter1_title'] ) ? esc_attr( $r['counter1_title'] ) : '';

        $counter2_number = isset( $r['counter2_number'] ) ? esc_attr( $r['counter2_number'] ) : '';
        $counter2_title  = isset( $r['counter2_title'] ) ? esc_attr( $r['counter2_title'] ) : '';

        $counter3_number = isset( $r['counter3_number'] ) ? esc_attr( $r['counter3_number'] ) : '';
        $counter3_title  = isset( $r['counter3_title'] ) ? esc_attr( $r['counter3_title'] ) : '';

        $counter4_number = isset( $r['counter4_number'] ) ? esc_attr( $r['counter4_number'] ) : '';
        $counter4_title  = isset( $r['counter4_title'] ) ? esc_attr( $r['counter4_title'] ) : '';

        $top_padding     = isset( $r['top_padding'] ) ? esc_attr( $r['top_padding'] ) : '';


        /* Set top inner padding */
        $style = '';
        if( $top_padding > 0 ) :
            $style = 'padding-top: '.$top_padding.'px;';
        endif;

    ?>

    <?php echo $args['before_widget']; ?>
        <?php if( $counter1_number > 0 || $counter2_number > 0 || $counter3_number > 0 || $counter4_number > 0 ) : ?>
            <div class="page-section" style="<?php echo $style; ?>">
                <div class="container countup-list">
                    <?php if( $counter1_number > 0 ) : ?>
                        <div class="countup-item">
                            <div class="countup-circle"><?php echo $counter1_number; ?></div>
                            <div class="countup-title"><?php echo $counter1_title; ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if( $counter2_number > 0 ) : ?>
                        <div class="countup-item">
                            <div class="countup-circle"><?php echo $counter2_number; ?></div>
                            <div class="countup-title"><?php echo $counter2_title; ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if( $counter3_number > 0 ) : ?>
                        <div class="countup-item">
                            <div class="countup-circle"><?php echo $counter3_number; ?></div>
                            <div class="countup-title"><?php echo $counter3_title; ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if( $counter4_number > 0 ) : ?>
                        <div class="countup-item">
                            <div class="countup-circle"><?php echo $counter4_number; ?></div>
                            <div class="countup-title"><?php echo $counter4_title; ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php echo $args['after_widget']; ?>


    <?php }


    public function form( $r ) {

        /* Set variables */
        $counter1_number = isset( $r['counter1_number'] ) ? esc_attr( $r['counter1_number'] ) : '';
        $counter1_title  = isset( $r['counter1_title'] ) ? esc_attr( $r['counter1_title'] ) : '';

        $counter2_number = isset( $r['counter2_number'] ) ? esc_attr( $r['counter2_number'] ) : '';
        $counter2_title  = isset( $r['counter2_title'] ) ? esc_attr( $r['counter2_title'] ) : '';

        $counter3_number = isset( $r['counter3_number'] ) ? esc_attr( $r['counter3_number'] ) : '';
        $counter3_title  = isset( $r['counter3_title'] ) ? esc_attr( $r['counter3_title'] ) : '';

        $counter4_number = isset( $r['counter4_number'] ) ? esc_attr( $r['counter4_number'] ) : '';
        $counter4_title  = isset( $r['counter4_title'] ) ? esc_attr( $r['counter4_title'] ) : '';

        $top_padding     = isset( $r['top_padding'] ) ? esc_attr( $r['top_padding'] ) : '70';

    ?>

        <div class="widget-option">
            <div class="widget-th">
                <label for="counter1-number"><b><?php _e( 'Counter 1', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="wideslim" id="counter1-number" name="<?php echo $this->get_field_name( 'counter1_number' ); ?>" type="number" value="<?php echo esc_attr( $counter1_number ); ?>">
                <p><?php _e( 'Enter counter number', 'maskitto-light' ); ?></p>
                <br >
                <input class="widemedium" id="counter1-text" name="<?php echo $this->get_field_name( 'counter1_title' ); ?>" type="text" value="<?php echo esc_attr( $counter1_title ); ?>">
                <p><?php _e( 'Enter counter name', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="counter2-number"><b><?php _e( 'Counter 2', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="wideslim" id="counter2-number" name="<?php echo $this->get_field_name( 'counter2_number' ); ?>" type="number" value="<?php echo esc_attr( $counter2_number ); ?>">
                <p><?php _e( 'Enter counter number', 'maskitto-light' ); ?></p>
                <br >
                <input class="widemedium" id="counter2-text" name="<?php echo $this->get_field_name( 'counter2_title' ); ?>" type="text" value="<?php echo esc_attr( $counter2_title ); ?>">
                <p><?php _e( 'Enter counter name', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="counter3-number"><b><?php _e( 'Counter 3', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="wideslim" id="counter3-number" name="<?php echo $this->get_field_name( 'counter3_number' ); ?>" type="number" value="<?php echo esc_attr( $counter3_number ); ?>">
                <p><?php _e( 'Enter counter number', 'maskitto-light' ); ?></p>
                <br >
                <input class="widemedium" id="counter3-text" name="<?php echo $this->get_field_name( 'counter3_title' ); ?>" type="text" value="<?php echo esc_attr( $counter3_title ); ?>">
                <p><?php _e( 'Enter counter name', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="counter4-number"><b><?php _e( 'Counter 4', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="wideslim" id="counter4-number" name="<?php echo $this->get_field_name( 'counter4_number' ); ?>" type="number" value="<?php echo esc_attr( $counter4_number ); ?>">
                <p><?php _e( 'Enter counter number', 'maskitto-light' ); ?></p>
                <br >
                <input class="widemedium" id="counter4-text" name="<?php echo $this->get_field_name( 'counter4_title' ); ?>" type="text" value="<?php echo esc_attr( $counter4_title ); ?>">
                <p><?php _e( 'Enter counter name', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option no-border">
            <div class="widget-th">
                <label for="top-padding"><b><?php _e( 'Inner top padding', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="wideslim" id="top-padding" name="<?php echo $this->get_field_name( 'top_padding' ); ?>" type="number" value="<?php echo esc_attr( $top_padding ); ?>">
                <p><?php _e( 'Enter top padding in pixels (minium is 1 px)', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <?php 

        /* Adds theme options CSS file inside widget */
        wp_enqueue_style( 'maskitto-light-theme-options', get_template_directory_uri() . '/css/theme-options.css' );
    }


    /* Sanitize widget form values as they are saved. */
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance['counter1_number'] = intval( $new_instance['counter1_number'] );
        $instance['counter1_text']   = esc_attr( $new_instance['counter1_text'] );

        $instance['counter2_number'] = intval( $new_instance['counter2_number'] );
        $instance['counter2_text']   = esc_attr( $new_instance['counter2_text'] );

        $instance['counter3_number'] = intval( $new_instance['counter3_number'] );
        $instance['counter3_text']   = esc_attr( $new_instance['counter3_text'] );

        $instance['counter4_number'] = intval( $new_instance['counter4_number'] );
        $instance['counter4_text']   = esc_attr( $new_instance['counter4_text'] );

        $instance['top_padding']     = intval( $new_instance['top_padding'] );
        if( !$instance['top_padding'] ) : $instance['top_padding'] = 70; endif;

        return $instance;
    }

}
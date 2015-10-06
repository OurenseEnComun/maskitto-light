<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Slogan extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_partners',
            __('Maskitto: Slogan', 'maskitto-light'),
            array(
                'description' => __( 'Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-megaphone',
                'panels_groups' => 'theme-widgets'
            )
        );
    }


    /* Front-end display of widget. */
    public function widget( $args, $r ) {

        $title = isset( $r['title'] ) ? esc_attr( $r['title'] ) : '';
        $description = isset( $r['description'] ) ? esc_attr( $r['description'] ) : '';

    ?>
    <?php echo $args['before_widget']; ?>
        <div class="page-section page-section-slogan">
            <div class="container">

                <div class="slogan-title"><?php echo $title; ?></div>
                <div class="slogan-description"><?php echo $description; ?></div>
                    
            </div>
        </div>
    <?php echo $args['after_widget']; ?>
    <?php }


    /* Back-end widget form. */
    public function form( $r ) {

        $title = isset( $r['title'] ) ? esc_attr( $r['title'] ) : '';
        $description = isset( $r['description'] ) ? esc_attr( $r['description'] ) : '';

    ?>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><b><?php _e( 'Title', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'description' ); ?>"><b><?php _e( 'Description', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <textarea style="height: 86px;" class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $description ); ?></textarea>
            </div>
            <div class="clearfix"></div>
        </div>

        <?php 

        /* Adds theme options CSS file inside widget */
        wp_enqueue_style( 'maskitto-light-theme-options', get_template_directory_uri() . '/css/theme-options.css' );
    }


    /* Sanitize widget form values as they are saved. */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? esc_attr( $new_instance['title'] ) : '';
        $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? esc_attr( $new_instance['description'] ) : '';

        return $instance;
    }

}
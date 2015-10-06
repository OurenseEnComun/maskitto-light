<?php
/* Adds Testimonials widget. */
class Maskitto_Testimonials extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_services',
            __('Maskitto: Testimonials', 'maskitto-light'),
            array(
                'description' => __( 'Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-businessman',
                'panels_groups' => 'theme-widgets'
            )
        );
    }


    /* Front-end display of widget. */
    public function widget( $args, $instance ) {

        $testimonials = wp_count_posts( 'testimonials' );
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $subtitle = isset( $instance['subtitle'] ) ? esc_attr( $instance['subtitle'] ) : '';
        $remove_border = isset( $instance['remove_border'] ) ? intval( $instance['remove_border'] ) : '';
        $limit = isset( $instance['limit'] ) ? intval( $instance['limit'] ) : '';
        $widget_group = ( isset( $instance['widget_group'] ) ) ? esc_attr( $instance['widget_group'] ) : '';

        if( isset( $testimonials->publish ) && $testimonials->publish > 0){
            if( !isset($instance['limit']) || !$instance['limit']) :
                $limit = 3;
            endif;
    ?>

    <?php echo $args['before_widget']; ?>
        <div class="page-section page-section-testimoninals">
            <div class="container">

                <?php if( $title || $subtitle ) : ?>
                <div class="section-title text-center">
                    <?php if( $title ) : ?>
                        <h3><?php echo $title; ?></h3>
                    <?php endif; ?>
                    <?php if( $subtitle ) : ?>
                        <div class="subtitle"><p><?php echo $subtitle; ?></p></div>
                    <?php endif; ?>
                    <div class="section-title-line"></div>
                </div>
                <?php endif; ?>

                <div class="testimonials-list">
                    <?php
                        $i = 1;

                        $loop_array = array(
                            'post_type' => 'testimonials',
                            'posts_per_page' => $limit,
                        );
                        if( isset( $widget_group ) && $widget_group != '' ) :  
                            $loop_array2 = array(
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'testimonials-group',
                                        'field'    => 'slug',
                                        'terms'    =>  $widget_group,
                                    ),
                                ),
                            );
                            $loop_array = array_merge( $loop_array, $loop_array2 );
                        endif;

                        $loop = new WP_Query( $loop_array );
                        while ( $loop->have_posts() ) : $loop->the_post();

                            $quote = esc_attr( get_post_meta( get_the_ID(), 'wpcf-quote', true ));
                            $author_descr = esc_attr( get_post_meta( get_the_ID(), 'wpcf-author-description', true ));
                            $image = esc_url( get_post_meta( get_the_ID(), 'wpcf-quote-image', true ));
                    ?>

                        <div class="testimonials-item">
                            <div class="testimonials-image" style="background-image: url(<?php echo $image; ?>);"></div>
                            <div class="text-center"><?php echo maskitto_light_admin_edit(get_the_ID()); ?></div>
                            <div class="testimonials-text"><i><?php echo $quote; ?></i></div>
                            <div class="testimonials-author"><?php the_title(); ?><?php if(get_the_title() && $author_descr) echo ' / '; ?><?php echo $author_descr; ?></div>
                            <div class="testimonials-icon"><i class="fa fa-quote-right"></i></div>
                        </div>

                    <?php endwhile; ?>
                </div>

            </div>
        </div>
    <?php echo $args['after_widget']; ?>

    <?php } }


    /* Back-end widget form. */
    public function form( $instance ) {
        $title = (string) NULL;
        $subtitle = (string) NULL;
        $limit = (string) NULL;
        $remove_border = (string) NULL;
        $widget_group = (string) NULL;

        if ( isset( $instance[ 'pageid' ] ) ) {
            $pageid = $instance[ 'pageid' ];
        }

        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }

        if ( isset( $instance[ 'subtitle' ] ) ) {
            $subtitle = $instance[ 'subtitle' ];
        }

        if ( isset( $instance[ 'limit' ] ) ) {
            $limit = $instance[ 'limit' ];
        }

        if ( isset( $instance[ 'widget_group' ] ) ) {
            $widget_group = $instance[ 'widget_group' ];
        }
        ?>

        <div class="widget-option no-border">
            <div class="widget-th">
                <label for=""><b><?php _e( 'Content', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">

                <?php if ( post_type_exists( 'testimonials' ) ) : ?>
                    <a href="<?php echo admin_url( 'edit.php?post_type=testimonials' ); ?>" target="_blank" class="widget-edit-button">
                        <?php _e( 'Manage testimonials content', 'maskitto-light' ); ?> 
                    </a>
                <?php else : ?>
                    <p><?php _e( 'Please import <i>Types</i> plugin XML file from our documentation to access this option.', 'maskitto-light' ); ?></p>
                <?php endif; ?>

            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><b><?php _e( 'Title', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                <p><?php _e( 'This field is optional', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'subtitle' ); ?>"><b><?php _e( 'Subitle', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>">
                <p><?php _e( 'This field is optional', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><b><?php _e( 'Limit items', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <select id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>"> 
                    <option value="3" <?php if( $limit == '3' ) echo 'selected'; ?>><?php _e( '3 items', 'maskitto-light' ); ?></option>
                    <option value="6" <?php if( $limit == '6' ) echo 'selected'; ?>><?php _e( '6 items', 'maskitto-light' ); ?></option>
                    <option value="9" <?php if( $limit == '9' ) echo 'selected'; ?>><?php _e( '9 items', 'maskitto-light' ); ?></option>
                </select>
                <p><?php _e( 'This field is optional', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'widget_group' ); ?>"><b><?php _e( 'Widget group', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <select id="<?php echo $this->get_field_id( 'widget_group' ); ?>" name="<?php echo $this->get_field_name( 'widget_group' ); ?>"> 
                    
                    <option value=""><?php _e( 'Show all', 'maskitto-light' ); ?></option>
                    <?php foreach( get_terms( 'testimonials-group', array( 'hide_empty' => 0 ) ) as $item ) : ?>
                        <option value="<?php echo $item->slug; ?>" <?php if( $widget_group == $item->slug ) echo 'selected'; ?>>
                            <?php echo $item->name; ?>
                        </option>
                    <?php endforeach; ?>

                </select>
                <p><?php _e( 'Select widget group', 'maskitto-light' ); ?></p>
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
        $instance['subtitle'] = ( ! empty( $new_instance['subtitle'] ) ) ? esc_attr( $new_instance['subtitle'] ) : '';
        $instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? intval( $new_instance['limit'] ) : '';
        $instance['remove_border'] = ( ! empty( $new_instance['remove_border'] ) ) ? intval( $new_instance['remove_border'] ) : '';
        $instance['widget_group'] = ( ! empty( $new_instance['widget_group'] ) ) ? esc_attr( $new_instance['widget_group'] ) : '';

        return $instance;
    }

}
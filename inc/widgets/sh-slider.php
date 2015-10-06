<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Slider extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_slider',
            __('Maskitto: Slider', 'maskitto-light'),
            array(
                'description' => __( 'Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-format-video',
                'panels_groups' => 'theme-widgets'
            )
        );
    }


    /* Front-end display of widget. */
    public function widget( $args, $instance ) {
        $sliders = wp_count_posts( 'slider' );
        if( isset( $sliders->publish ) && $sliders->publish > 0){

            $widget_group = ( isset( $instance['widget_group'] ) ) ? esc_attr( $instance['widget_group'] ) : '';
            $desktop_height = ( isset( $instance['desktop_height'] ) ) ? esc_attr( $instance['desktop_height'] ) : '';

    ?>

        <?php if( $desktop_height > 400 ) : ?>
            <style type="text/css">

                /* Custom slideshow height */
                @media (min-width: 1070px) {

                    #wrapper .page-slideshow {
                        height: <?php echo $desktop_height; ?>px;
                        max-height: <?php echo $desktop_height; ?>px;
                    }

                }

            </style>
        <?php endif; ?>


        <div class="page-slideshow widget-slider-<?php if( $widget_group ) : echo $widget_group; else : echo 'all'; endif; ?>">
            <i class="fa fa-circle-o-notch fa-spin"></i>
            <div class="slideshow">
                <?php

                    $loop_array = array(
                        'post_type' => 'slider',
                    );
                    if( isset( $widget_group ) && $widget_group != '' ) : 
                        $loop_array2 = array(
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'slider-group',
                                    'field'    => 'slug',
                                    'terms'    =>  $widget_group,
                                ),
                            ),
                        );
                        $loop_array = array_merge( $loop_array, $loop_array2 );
                    endif;

                    $loop = new WP_Query( $loop_array );
                    while ( $loop->have_posts() ) : $loop->the_post();

                        $class1 = (string) NULL;
                        $class2 = (string) NULL;
                        $style1 = (string) NULL;

                        $image = esc_url( get_post_meta( get_the_ID(), 'wpcf-background-image', true ));
                        $caption = esc_attr( get_post_meta( get_the_ID(), 'wpcf-caption', true ));
                        $caption2 = esc_attr( get_post_meta( get_the_ID(), 'wpcf-caption2', true ));

                        $button_name = esc_attr( get_post_meta( get_the_ID(), 'wpcf-button-name', true ));
                        $button_url = esc_url( get_post_meta( get_the_ID(), 'wpcf-button-url', true ));
                        if( !$button_url ) $button_url = '#';
                        $button_icon = esc_attr( get_post_meta( get_the_ID(), 'wpcf-button-icon', true ));

                        $button_name2 = esc_attr( get_post_meta( get_the_ID(), 'wpcf-button-name2', true ));
                        $button_url2 = esc_url( get_post_meta( get_the_ID(), 'wpcf-button-url2', true ));
                        if( !$button_url2 ) $button_url2 = '#';
                        $button_icon2 = esc_attr( get_post_meta( get_the_ID(), 'wpcf-button-icon2', true ));

                        $background_color = esc_attr( get_post_meta( get_the_ID(), 'wpcf-background-color', true ));
                        $background_shadow = esc_attr( get_post_meta( get_the_ID(), 'wpcf-background-shadow', true ));
                        $background_pattern = esc_attr( get_post_meta( get_the_ID(), 'wpcf-background-texture', true ));
                        $caption_background = esc_attr( get_post_meta( get_the_ID(), 'wpcf-caption-background', true ));

                        $align_horizontal = esc_attr( get_post_meta( get_the_ID(), 'wpcf-text-horizontal', true ));
                        $align_vertical = esc_attr( get_post_meta( get_the_ID(), 'wpcf-text-vertical', true ));

                        if( !$background_shadow ) {
                            $class1.= ' remove-shadow';
                        }

                        if( $image ) {
                            $style1.= "background-image: url($image);";
                        }

                        if( $background_color ) {
                            $style1.= "background-color: $background_color;";
                        }

                        if( $background_pattern ) {
                            $class2.= ' slide-patern-show';
                        }

                        if( $align_vertical == 'top' ) {
                            $class2.= ' slide-align-top';
                        }

                        if( $align_horizontal == 'left' ) {
                            $class2.= ' slide-align-left';
                        } else if( $align_horizontal == 'right' ) {
                            $class2.= ' slide-align-right';
                        }

                        if( $caption_background ) {
                            $class2.= ' caption-background';
                        }

                ?>
                    <div class="<?php if(class_exists("Jetpack")) : echo 'sh-'; endif; ?>slideshow-slide<?php echo $class1; ?>" style="<?php echo $style1; ?>">
                        <div class="slide-patern<?php echo $class2; ?>">
                            <div class="container">
                                <div class="slide-details">
                                    <div><?php echo maskitto_light_admin_edit(get_the_ID()); ?></div>
                                
                                    <?php if( $caption2 ) : ?>
                                        <div class="slide-info2"><?php echo $caption2; ?></div>
                                    <?php endif; ?>

                                    <div class="slide-title"><?php the_title(); ?></div>
                                    <div></div>
                                    <?php if( $caption ) { ?>
                                        <div class="slide-info"><?php echo $caption; ?></div>
                                    <?php } ?>
                                    <?php if( $button_name || $button_name2 ) { ?>
                                        <div class="slide-button">
                                            <?php if( $button_name ) { ?>
                                                <a href="<?php echo $button_url; ?>" class="btn btn-danger">
                                                    <?php if( $button_icon ) { ?>
                                                        <i class="fa <?php echo $button_icon; ?>"></i>
                                                    <?php } ?>
                                                    <?php echo $button_name; ?>
                                                </a>
                                            <?php } ?>

                                            <?php if( $button_name2 ) { ?>
                                                <a href="<?php echo $button_url2; ?>" class="btn btn-white">
                                                    <?php if( $button_icon2 ) { ?>
                                                        <i class="fa <?php echo $button_icon2; ?>"></i>
                                                    <?php } ?>
                                                    <?php echo $button_name2; ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>


    <?php } }


    /* Back-end widget form. */
    public function form( $r ) {

        $desktop_height = 500; 
        if ( isset( $r[ 'desktop_height' ] ) && $r[ 'desktop_height' ] > 0 ) :
            $desktop_height = esc_attr( $r[ 'desktop_height' ] );
        endif;

        $widget_group = '';
        if ( isset( $instance[ 'widget_group' ] ) ) {
            $widget_group = $instance[ 'widget_group' ];
        }

        ?>

        <div class="widget-option">
            <div class="widget-th">
                <label for="desktop_height"><b><?php _e( 'Height', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="wideslim" id="desktop_height" name="<?php echo $this->get_field_name( 'desktop_height' ); ?>" type="number" value="<?php echo esc_attr( $desktop_height ); ?>">
                <p><?php _e( 'Enter slide height for desktop devices (default height is 500px, min height is 400)', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for=""><b><?php _e( 'Content', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">

                <?php if ( post_type_exists( 'slider' ) ) : ?>
                    <a href="<?php echo admin_url( 'edit.php?post_type=slider' ); ?>" target="_blank" class="widget-edit-button">
                        <?php _e( 'Manage slider content', 'maskitto-light' ); ?> 
                    </a>
                <?php else : ?>
                    <p><?php _e( 'Please import <i>Types</i> plugin XML file from our documentation to access this option.', 'maskitto-light' ); ?></p>
                <?php endif; ?>

            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option no-border">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'widget_group' ); ?>"><b><?php _e( 'Widget group', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <select id="<?php echo $this->get_field_id( 'widget_group' ); ?>" name="<?php echo $this->get_field_name( 'widget_group' ); ?>"> 
                    
                    <option value=""><?php _e( 'Show all', 'maskitto-light' ); ?></option>
                    <?php foreach( get_terms( 'slider-group', array( 'hide_empty' => 0 ) ) as $item ) : ?>
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
        $instance['widget_group'] = ( ! empty( $new_instance['widget_group'] ) ) ? esc_attr( $new_instance['widget_group'] ) : '';
        $instance['desktop_height'] = ( ! empty( $new_instance['desktop_height'] ) && $new_instance['desktop_height'] > 400 ) ? intval( $new_instance['desktop_height'] ) : '500';

        return $instance;
    }

}
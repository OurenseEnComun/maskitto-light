<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Services extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_services',
            __('Maskitto: Services', 'maskitto-light'),
            array(
                'description' => __( 'Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-clipboard',
                'panels_groups' => 'theme-widgets'
            )
        );
    }


    /* Front-end display of widget. */
    public function widget( $args, $instance ) {

        $sliders = wp_count_posts( 'services' );
        $remove_border = isset( $instance['remove_border'] ) ? intval( $instance['remove_border'] ) : '';
        $limit = isset( $instance['limit'] ) ? intval( $instance['limit'] ) : '';
        $widget_group = ( isset( $instance['widget_group'] ) ) ? esc_attr( $instance['widget_group'] ) : '';

        if( isset( $sliders->publish ) && $sliders->publish > 0){
            if( !isset($instance['limit']) || !$instance['limit']) :
                $limit = 3;
            endif;
    ?>

        <div class="page-section page-section-services">
            <div class="container">
                <div class="row services-list <?php if( $remove_border ) { echo 'services-list-no-border'; } ?>">
                    <?php
                        $i = 1;

                        $loop_array = array(
                            'post_type' => 'services',
                            'posts_per_page' => $limit,
                        );
                        if( isset( $widget_group ) && $widget_group != '' ) :  
                            $loop_array2 = array(
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'services-group',
                                        'field'    => 'slug',
                                        'terms'    =>  $widget_group,
                                    ),
                                ),
                            );
                            $loop_array = array_merge( $loop_array, $loop_array2 );
                        endif;

                        $loop = new WP_Query( $loop_array );
                        while ( $loop->have_posts() ) : $loop->the_post();
                        $url = esc_url( get_post_meta( get_the_ID(), 'wpcf-url', true ));
                    ?>
                        <div class="col-md-4 text-center service-item">

                            <?php if( $url ) { ?>
                                <a href="<?php echo $url; ?>" class="grey">
                            <?php } ?>

                                <div class="service-column-left">
                                    <div class="service-line"></div>
                                    <div class="service-icon">
                                        <i class="fa <?php
                                            $icon = get_post_meta( get_the_ID(), 'wpcf-icon', true );
                                            if( $icon ){
                                                echo $icon;
                                            } else {
                                                echo 'fa-pencil';
                                            }
                                        ?>"></i>
                                    </div>
                                    <div class="service-line-bottom"></div>
                                </div>
                                <div class="service-column-right">
                                    <h5><?php the_title(); ?><?php echo maskitto_light_admin_edit(get_the_ID()); ?></h5>
                                    <?php the_content(); ?>
                                </div>

                            <?php if( $url ) { ?>
                                </a>
                            <?php } ?>

                        </div>

                        <?php if( $i%3 == 0 ) : ?>
                            </div>
                            <div class="row services-list">
                        <?php endif; $i++; ?>

                    <?php endwhile; ?>
                </div>
            </div>
        </div>

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

        if ( isset( $instance[ 'subtitle' ] ) ) {
            $subtitle = $instance[ 'subtitle' ];
        }

        if ( isset( $instance[ 'limit' ] ) ) {
            $limit = $instance[ 'limit' ];
        }

        if ( isset( $instance[ 'remove_border' ] ) ) {
            $remove_border = $instance[ 'remove_border' ];
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

                <?php if ( post_type_exists( 'services' ) ) : ?>
                    <a href="<?php echo admin_url( 'edit.php?post_type=services' ); ?>" target="_blank" class="widget-edit-button">
                        <?php _e( 'Manage services content', 'maskitto-light' ); ?>
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
                <label for="<?php echo $this->get_field_id( 'remove_border' ); ?>"><b><?php _e( 'Dotted line', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input type="checkbox" name="<?php echo $this->get_field_name( 'remove_border' ); ?>" value="1" <?php if( $remove_border ){ echo 'checked'; } ?>><?php _e( 'disable the top dotted lines', 'maskitto-light' ); ?>
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
                    <?php foreach( get_terms( 'services-group', array( 'hide_empty' => 0 ) ) as $item ) : ?>
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
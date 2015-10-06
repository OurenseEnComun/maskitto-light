<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Projects extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_projects',
            __('Maskitto: Portfolio', 'maskitto-light'),
            array(
                'description' => __( 'Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-media-interactive',
                'panels_groups' => 'theme-widgets'
            )
        );
    }

    /* Front-end display of widget. */
    public function widget( $args, $instance ) {

        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $subtitle = isset( $instance['subtitle'] ) ? esc_attr( $instance['subtitle'] ) : '';
        $limit = isset( $instance['limit'] ) ? intval( $instance['limit'] ) : '';
        $widget_group = ( isset( $instance['widget_group'] ) ) ? esc_attr( $instance['widget_group'] ) : '';
        $show_categories = isset( $instance['show_categories'] ) ? esc_attr( $instance['show_categories'] ) : '';
        $show_spaces = isset( $instance['show_spaces'] ) ? esc_attr( $instance['show_spaces'] ) : '';
        $hide_text = isset( $instance['hide_text'] ) ? esc_attr( $instance['hide_text'] ) : '';


        if( $widget_group ) :
            $terms = get_terms('category', array(
                'post_type' => 'portfolio-item',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'porfolio-group',
                        'field'    => 'slug',
                        'terms'    =>  $widget_group,
                    ),
                ),
            ));
        else :
            $terms = get_terms('category', array(
                'post_type' => 'portfolio-item'
            ));
        endif;


        $random = rand(11111,88888)

    ?>

    <?php echo $args['before_widget']; ?>
        <?php if( $title || $subtitle || $show_categories ) : ?>
            <div class="page-section<?php echo ( $show_categories ) ? ' portfolio-categories-enabled' : ''; ?>" style="padding-bottom: 0;">
                <div class="container<?php echo ( !$subtitle ) ? ' page-no-subtitle' : ''; ?>">
                    <?php if( $title || $subtitle ) : ?>
                        <div class="row projects-list">
                            <div class="section-title text-center">
                                <h3><?php echo $title; ?></h3>
                                <?php if( isset( $subtitle ) && $subtitle ) : ?>
                                    <div class="subtitle"><p><?php echo $subtitle; ?></p></div>
                                <?php endif; ?>
                                <div class="section-title-line"></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if( $show_categories ) : ?>
                        <div class="portfolio-categories-container">
                            <div  class="portfolio-categories" role="tabpanel">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#all<?php echo $random; ?>" role="tab" data-toggle="tab"><?php _e( 'All', 'maskitto-light'); ?></a></li>
                                    <?php foreach( $terms as $term ) : ?>
                                        <li role="presentation"><a href="#<?php echo $term->slug.$random; ?>" role="tab" data-toggle="tab"><?php echo $term->name; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="page-section" style="padding: 0;">

        <?php if( $show_spaces == 4 ) : ?>
            <style type="text/css">
                /* Portfolio items */

                .portfolio-item {
                    border-right: 3px solid rgba(255,255,255,0);
                    border-bottom: 3px solid rgba(255,255,255,0);
                }

                .portfolio-item:nth-child(4n+0) {
                    border-right: 0px rgba(255,255,255,0);
                }

                .portfolio-item:nth-child(-n+4) {
                    border-top: 3px solid rgba(255,255,255,0);
                }
            </style>
        <?php endif; ?>

            <div role="tabpanel"<?php echo ( $show_spaces ) ? ' class="portfolio-white-space"' : ''; ?>>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="all<?php echo $random; ?>">
                        <div class="row portfolio-list">
                            <?php echo maskitto_light_portfolio_items( '', $limit, $widget_group, $hide_text ); ?>
                        </div>
                    </div>

                    <?php if( $show_categories ) : ?>
                        <?php foreach( $terms as $term ) : ?>
                            <div role="tabpanel" class="tab-pane fade" id="<?php echo $term->slug.$random; ?>">
                                <div class="row portfolio-list">
                                    <?php echo maskitto_light_portfolio_items( $term->slug, $limit, $widget_group, $hide_text ); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    <?php echo $args['after_widget']; ?>
    <?php }


    /* Back-end widget form. */
    public function form( $instance ) {

        $title = (string) NULL;
        if ( isset( $instance[ 'title' ] ) ) :
            $title = esc_attr( $instance[ 'title' ] );
        endif;

        $subtitle = (string) NULL;
        if ( isset( $instance[ 'subtitle' ] ) ) :
            $subtitle = esc_attr( $instance[ 'subtitle' ] );
        endif;

        $limit = (string) NULL;
        if ( isset( $instance[ 'limit' ] ) ) :
            $limit = intval( $instance[ 'limit' ] );
        endif;

        $widget_group = (string) NULL;
        if ( isset( $instance[ 'widget_group' ] ) ) {
            $widget_group = $instance[ 'widget_group' ];
        }

        $show_categories = ( isset( $instance['show_categories'] ) ) ? esc_attr( $instance['show_categories'] ) : '';
        $show_spaces = ( isset( $instance['show_spaces'] ) ) ? esc_attr( $instance['show_spaces'] ) : '';
        $hide_text = ( isset( $instance['hide_text'] ) ) ? esc_attr( $instance['hide_text'] ) : '';

    ?>

        <div class="widget-option no-border">
            <div class="widget-th">
                <label for=""><b><?php _e( 'Content', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">

                <?php if ( post_type_exists( 'portfolio-item' ) ) : ?>
                    <a href="<?php echo admin_url( 'edit.php?post_type=portfolio-item' ); ?>" target="_blank" class="widget-edit-button">
                        <?php _e( 'Manage portfolio content', 'maskitto-light' ); ?> 
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
                    <option value="4" <?php if( $limit == '4' ) echo 'selected'; ?>><?php _e( '4 items', 'maskitto-light' ); ?></option>
                    <option value="8" <?php if( $limit == '8' ) echo 'selected'; ?>><?php _e( '8 items', 'maskitto-light' ); ?></option>
                    <option value="12" <?php if( $limit == '12' ) echo 'selected'; ?>><?php _e( '12 items', 'maskitto-light' ); ?></option>
                    <option value="-1" <?php if( $limit == '-1' ) echo 'selected'; ?>><?php _e( 'No limits', 'maskitto-light' ); ?></option>
                </select>
                <p><?php _e( 'This field is optional', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'show_categories' ); ?>"><b><?php _e( 'Categories switch', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input type="checkbox" name="<?php echo $this->get_field_name( 'show_categories' ); ?>" value="1" <?php if( $show_categories ){ echo 'checked'; } ?>><?php _e( 'Show categories switch', 'maskitto-light' ); ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'show_spaces' ); ?>"><b><?php _e( 'White spaces', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input type="checkbox" name="<?php echo $this->get_field_name( 'show_spaces' ); ?>" value="1" <?php if( $show_spaces ){ echo 'checked'; } ?>><?php _e( 'Add white spaces between images', 'maskitto-light' ); ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'hide_text' ); ?>"><b><?php _e( 'Remove Item Details', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input type="checkbox" name="<?php echo $this->get_field_name( 'hide_text' ); ?>" value="1" <?php if( $hide_text ){ echo 'checked'; } ?>><?php _e( 'Hide all item details', 'maskitto-light' ); ?>
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
                    <?php foreach( get_terms( 'porfolio-group', array( 'hide_empty' => 0 ) ) as $item ) : ?>
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
        $instance['widget_group'] = ( ! empty( $new_instance['widget_group'] ) ) ? esc_attr( $new_instance['widget_group'] ) : '';
        $instance['show_categories'] = ( ! empty( $new_instance['show_categories'] ) ) ? intval( $new_instance['show_categories'] ) : '';
        $instance['show_spaces'] = ( ! empty( $new_instance['show_spaces'] ) ) ? esc_attr( $new_instance['show_spaces'] ) : '';
        $instance['hide_text'] = ( ! empty( $new_instance['hide_text'] ) ) ? esc_attr( $new_instance['hide_text'] ) : '';

        return $instance;
    }

}
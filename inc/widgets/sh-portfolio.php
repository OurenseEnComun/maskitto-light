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

    ?>

        <?php if( $title || $subtitle ) : ?>
            <div class="page-section" style="padding-bottom: 0;">
                <div class="container">
                    <div class="row projects-list">
                        <div class="section-title text-center">
                            <h3 style="<?php echo $style2; ?>"><?php echo $title; ?></h3>
                            <?php if( isset( $subtitle ) && $subtitle ) : ?>
                                <div class="subtitle"><p><?php echo $subtitle; ?></p></div>
                            <?php endif; ?>
                            <div class="section-title-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="page-section" style="padding: 0;">
            <div class="row portfolio-list">
                <?php
                    if( !isset($limit) ||  !$limit || $limit == '-1' ) :
                        $limit = 200;
                    endif;

                    $count_portfolio = wp_count_posts( 'portfolio-item' );
                    if( isset( $count_portfolio->publish ) && $count_portfolio->publish > 0) :
                        $post_type = 'portfolio-item';
                    else :
                        $post_type = 'portfolio';
                    endif;

                    $loop_array = array(
                        'post_type' => $post_type,
                        'posts_per_page' => $limit,
                    );
                    if( isset( $widget_group ) && $widget_group != '' ) : 
                        $loop_array2 = array(
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'porfolio-group',
                                    'field'    => 'slug',
                                    'terms'    =>  $widget_group,
                                ),
                            ),
                        );
                        $loop_array = array_merge( $loop_array, $loop_array2 );
                    endif;

                    $loop = new WP_Query( $loop_array );
                    while ( $loop->have_posts() ) : $loop->the_post();

                        $style1 = (string) NULL;
                        $image = esc_url( get_post_meta( get_the_ID(), 'wpcf-background-image', true ));
                        $caption = esc_attr( get_post_meta( get_the_ID(), 'wpcf-caption', true ));
                        $url = esc_url( get_post_meta( get_the_ID(), 'wpcf-url', true ));

                        if( $image ) :
                            $style1.= "background-image: url($image);";
                        endif;

                        if( $url ) :
                            $image = $url;
                        endif;

                ?>
                <a href="<?php echo $image; ?>" alt="<?php the_title(); ?>" class="col-md-3 col-sm-6 portfolio-item">
                    <div class="portfolio-thumb" style="<?php echo $style1; ?>"></div>
                    <div class="portfolio-details">
                        <div class="portfolio-details-align">
                            <div class="portfolio-title"><?php the_title(); ?></div>
                            <?php if( $caption ) : ?>
                                <div class="portfolio-info"><?php echo $caption; ?></div>
                            <?php endif; ?>
                            <?php
                            $cat = get_the_category();
                            if( count($cat) && $cat[0]->name ) : ?>
                                <div class="portfolio-line"></div>
                                <div class="portfolio-cat"><?php echo $cat[0]->name; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>

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

        return $instance;
    }

}
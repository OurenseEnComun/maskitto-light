<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Blog extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_blog',
            __('Maskitto: Blog', 'maskitto-light'),
            array(
                'description' => __( 'Displays latest blog posts. Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-admin-post',
                'panels_groups' => 'theme-widgets'
            )
        );
    }


    /* Front-end display of widget. */
    public function widget( $args, $instance ) {
        if( !isset($instance['limit']) || !$instance['limit']) :
            $limit = 6;
        else :
            $limit = intval( $instance['limit'] );
        endif;

        $loop = new WP_Query( array('post_type' => 'post', 'posts_per_page' => $limit ) );
        $count_posts = wp_count_posts();
    ?>

        <div class="page-section page-blog" style="background-color: #f2f4f5; padding: 70px 0!important;">
            <div class="container" style="max-width: 980px;">

                <div class="section-title text-center">
                    <?php if( $instance['title'] ){ ?>
                        <h3><?php echo esc_attr( $instance['title'] ); ?></h3>
                    <?php } ?>
                    <?php if( $instance['subtitle'] ){ ?>
                        <div class="subtitle"><p><?php echo esc_attr( $instance['subtitle'] ); ?></p></div>
                    <?php } ?>
                    <?php if( $instance['title'] || $instance['subtitle'] ){ ?>
                        <div class="section-title-line"></div>
                    <?php } ?>
                </div>

                <div class="row blog-list">
                    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
                        <?php get_template_part( 'content' ); ?>
                    <?php endwhile; ?>
                </div>

                <?php if( $count_posts->publish > 6 ) : ?>
                    <a href="<?php echo (get_option( 'show_on_front' ) == 'page') ? get_permalink( get_option('page_for_posts' ) ) : esc_url( home_url() ); ?>" class="btn btn-default">
                        <i class="fa fa-angle-right"></i>
                        <?php _e( 'Load more', 'maskitto-light' ); ?>
                    </a>
                <?php endif; ?>

            </div>
        </div>

    <?php }


    /* Back-end widget form. */
    public function form( $instance ) {
        $title = (string) NULL;
        $subtitle = (string) NULL;
        $limit = (string) NULL;

        if ( isset( $instance[ 'title' ] ) ) {
            $title = esc_attr( $instance[ 'title' ] );
        }

        if ( isset( $instance[ 'subtitle' ] ) ) {
            $subtitle = esc_attr( $instance[ 'subtitle' ] );
        }

        if ( isset( $instance[ 'limit' ] ) ) {
            $limit = intval( $instance[ 'limit' ] );
        }
        
        ?>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><b><?php _e( 'Title', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                <p><?php _e( 'This field is optional', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><b><?php _e( 'Subitle', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>">
                <p><?php _e( 'This field is optional', 'maskitto-light' ); ?></p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><b><?php _e( 'Limit items', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <select id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>">
                    <option><?php _e( 'Select limit', 'maskitto-light' ); ?></option>
                    <option value="3" <?php selected( $limit, 3 ); ?>><?php _e( '3 items', 'maskitto-light' ); ?></option>
                    <option value="6" <?php selected( $limit, 6 ); ?>><?php _e( '6 items', 'maskitto-light' ); ?></option>
                    <option value="9" <?php selected( $limit, 9 ); ?>><?php _e( '9 items', 'maskitto-light' ); ?></option>
                </select>
                <p><?php _e( 'This field is optional', 'maskitto-light' ); ?></p>
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

        return $instance;
    }

}
<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Partners extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_partners',
            __('Maskitto: Partners', 'maskitto-light'),
            array(
                'description' => __( 'Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-groups',
                'panels_groups' => 'theme-widgets'
            )
        );
    }


    /* Front-end display of widget. */
    public function widget( $args, $instance ) {

        $title = (string) NULL;
        $subtitle = (string) NULL;

        if ( isset( $instance[ 'title' ] ) ) {
            $title = esc_attr( $instance[ 'title' ] );
        }

        if ( isset( $instance[ 'subtitle' ] ) ) {
            $subtitle = esc_attr( $instance[ 'subtitle' ] );
        }

        $partners = wp_count_posts( 'partners' );
        if( isset( $partners->publish ) && $partners->publish > 0){
    ?>
    <?php echo $args['before_widget']; ?>
        <div class="page-section page-section-partners">
            <div class="container<?php echo ( !$subtitle ) ? ' page-no-subtitle' : ''; ?>">

                <?php if( $title || $subtitle ) : ?>
                <div class="section-title text-center">
                    <?php if( $title ) : ?>
                        <h3><?php echo $title; ?></h3>
                    <?php endif; ?>
                    <?php if( $subtitle ) : ?>
                        <div class="subtitle"><p><?php echo $subtitle; ?></p></div>
                    <?php endif; ?>
                    <?php if( $instance['title'] || $instance['subtitle'] ){ ?>
                        <div class="section-title-line"></div>
                    <?php } ?>
                </div>
                <?php endif; ?>
                
                <div class="partners-list">
                    <?php
                        $loop = new WP_Query( array('post_type' => 'partners', 'posts_per_page' => 20 ) );
                        while ( $loop->have_posts() ) : $loop->the_post();
                        
                            $image = esc_url( get_post_meta( get_the_ID(), 'wpcf-background-image', true ));
                            $url = esc_url( get_post_meta( get_the_ID(), 'wpcf-url', true ));
                            if( !$url ) $url = '#';
                    ?>
                        <?php if( $image ) : ?>
                            <div class="partner-item">
                                <a href="<?php echo $url; ?>">
                                    <img height="100" src="<?php echo $image; ?>" class="attachment-post-thumbnail wp-post-image" alt="<?php the_title(); ?>">
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endwhile;?>
                </div>
            </div>
        </div>
    <?php echo $args['after_widget']; ?>
    <?php } }


    /* Back-end widget form. */
    public function form( $instance ) {

        $title = (string) NULL;
        $subtitle = (string) NULL;

        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }

        if ( isset( $instance[ 'subtitle' ] ) ) {
            $subtitle = $instance[ 'subtitle' ];
        }
        
        ?>

        <div class="widget-option">
            <div class="widget-th">
                <label for=""><b><?php _e( 'Content', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">

                <?php if ( post_type_exists( 'partners' ) ) : ?>
                    <a href="<?php echo admin_url( 'edit.php?post_type=partners' ); ?>" target="_blank" class="widget-edit-button">
                        <?php _e( 'Manage partners content', 'maskitto-light' ); ?> 
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

        <div class="widget-option no-border">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'widget_group' ); ?>"><b><?php _e( 'Widget group', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <p><?php _e( 'In next theme versions', 'maskitto-light' ); ?></p>
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

        return $instance;
    }

}
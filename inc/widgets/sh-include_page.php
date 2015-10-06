<?php
/* Adds Maskitto_Services widget. */
class Maskitto_Include_Other_Page extends WP_Widget {

    /* Register widget with WordPress. */
    function __construct() {
        parent::__construct(
            'maskitto_include_other_page',
            __('Maskitto: Include page', 'maskitto-light'),
            array(
                'description' => __( 'Include other page. Only for page builder.', 'maskitto-light' ),
                'panels_icon' => 'dashicons dashicons-admin-page',
                'panels_groups' => 'theme-widgets'
            )
        );
    }


    /* Front-end display of widget. */
    public function widget( $args, $instance ) {

        $id = intval( $instance['pageid'] );
        $style1 = (string) NULL;
        $style2 = (string) NULL;
        $style3 = (string) NULL;

        global $wp_query;
        $type = get_post_type( $id );
        $main_post = $wp_query->get_queried_object();

        if( $id > 0 && $type == 'page' && $id != $main_post->ID ) : 
            echo maskitto_light_generate_page( $id, 1 );
        endif;

    }

    /* Back-end widget form. */
    public function form( $instance ) {
        $title = (string) NULL;
        $subtitle = (string) NULL;

        if ( isset( $instance[ 'pageid' ] ) ) {
            $pageid = $instance[ 'pageid' ];
        }

        if ( isset( $instance[ 'subtitle' ] ) ) {
            $subtitle = $instance[ 'subtitle' ];
        }
        ?>

        <div class="widget-option">
            <div class="widget-th">
                <label for="<?php echo $this->get_field_id( 'pageid' ); ?>"><b><?php _e( 'Pages', 'maskitto-light' ); ?></b></label> 
            </div>
            <div class="widget-td">
                <select id="<?php echo $this->get_field_id( 'pageid' ); ?>" name="<?php echo $this->get_field_name( 'pageid' ); ?>"> 
                    <?php $args = array(
                        'sort_order' => 'ASC',
                        'sort_column' => 'post_title',
                        'hierarchical' => 1,
                        'exclude' => '',
                        'include' => '',
                        'meta_key' => '',
                        'meta_value' => '',
                        'authors' => '',
                        'child_of' => 0,
                        'parent' => -1,
                        'exclude_tree' => '',
                        'number' => '',
                        'offset' => 0,
                        'post_type' => 'page',
                        'post_status' => 'publish'
                    ); 
                    $pages = get_pages($args);
                        foreach ( $pages as $page ) {
                            if( $page->ID == $pageid ) {
                                $selected = ' selected';
                            }

                            $option = '<option value="' . $page->ID . '"'.$selected.'>';
                            $option .= $page->post_title;
                            $option .= '</option>';
                            echo $option;

                            $selected = '';
                        }
                    ?>
                </select>
                <p><?php _e( 'Choose page you want to include', 'maskitto-light' ); ?></p>
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
        $instance['pageid'] = ( ! empty( $new_instance['pageid'] ) ) ? intval( $new_instance['pageid'] ) : 0;
        if( $instance['pageid'] > 0 ) {
            $title = get_the_title( $instance['pageid'] );
            if( $title ) {
                $instance['title'] = $title;
            } else {
                $instance['title'] = 'Unknown';
            }
        }

        return $instance;
    }

}
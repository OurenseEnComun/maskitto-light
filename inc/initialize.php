<?php
/**
 * Maksitto Light functions and definitions 
 *
 * @package Maksitto Light
 */


/* Load TGM */
require_once get_template_directory() . '/inc/plugins/tgm.php';

function maskitto_light_register_required_plugins() {

    $plugins = array(
        array(
            'name'      => __( 'Redux Framework', 'maskitto-light' ),
            'slug'      => 'redux-framework',
            'required'  => false,
        ),

        array(
            'name'      => __( 'Page Builder by SiteOrigin', 'maskitto-light' ),
            'slug'      => 'siteorigin-panels',
            'required'  => false,
        ),

        array(
            'name'      => __( 'Types - Custom Fields and Custom Post Types Management', 'maskitto-light' ),
            'slug'      => 'types',
            'required'  => false,
        ),
    );

    $config = array(
        'domain'            => 'maskitto-light',           // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                          // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'plugins.php',               // Default parent menu slug
        'parent_url_slug'   => 'plugins.php',               // Default parent URL slug
        'menu'              => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'is_automatic'      => true,                        // Automatically activate plugins after installation or not
    );

    tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'maskitto_light_register_required_plugins' );


/**
 * Initialize framework
 */
if ( class_exists( 'ReduxFramework' ) ) :
    require_once get_template_directory() . '/inc/plugins/redux.php';
endif;
global $maskitto;


/**
 * Initialize basic theme functionality
 */
if ( ! function_exists( 'maskitto_light_setup' ) ) :

    function maskitto_light_setup() {

        /* Add theme text domain support */
        load_theme_textdomain('maskitto-light', get_template_directory() . '/languages');


    	/* Add default posts and comments RSS feed links to head. */
    	add_theme_support( 'automatic-feed-links' );


    	/* Enable support for Post Thumbnails on posts and pages. */
    	add_theme_support( 'post-thumbnails' );


        /* Enable support for custom header - if redux framework is disabled */
        if ( !class_exists('ReduxFramework') ) :
            add_theme_support( "custom-header", array( 'default-image' => get_template_directory_uri() . '/img/logo.png', 'header-text' => false, ) );
        endif;


        /* Enables plugins and themes to manage the document title */
        add_theme_support( 'title-tag' );


    	/* This theme uses wp_nav_menu() in one location. */
    	register_nav_menus( array(
    		'primary' => __( 'Primary navigation', 'maskitto-light' ),
    	) );


        /* Bootstrap navigation */
        require_once get_template_directory() . '/inc/plugins/navwalker.php';


    	/* Switch default core markup for search form, comment form, and comments. */
    	add_theme_support( 'html5', array(
    		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    	) );


    	/* Set up the WordPress core custom background feature. */
    	add_theme_support( 'custom-background', apply_filters( 'maskitto_custom_background_args', array(
    		'default-color' => 'ffffff',
    		'default-image' => '',
    	) ) );


        /* Custom editor style */
        add_editor_style( );


        /* Support post formats */
        add_theme_support( 'post-formats', array(
            'aside',
            'video',
            'audio'
        ) ); 
    }

    add_action( 'after_setup_theme', 'maskitto_light_setup' );

endif;



/**
 * Initialize widget area
 */
function maskitto_light_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'maskitto-light' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'maskitto_light_widgets_init' );
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Load custom template tags
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Load functions that act independently of the theme templates
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load customizer additions
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file
 */
require get_template_directory() . '/inc/jetpack.php';


/** 
 * Load media files
 */

if( !function_exists('maskitto_light_enqueue_styles') ) :
        
    function maskitto_light_enqueue_styles() {


        global $maskitto_light;

        wp_enqueue_script( 'maskitto-light-hammer', get_template_directory_uri() . '/js/hammer.min.js', array( 'jquery' ) );

        if( !isset( $maskitto_light['minity-status'] ) || $maskitto_light['minity-status'] == 1 ) :

            wp_enqueue_style( 'maskitto-light-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );
            wp_enqueue_style( 'maskitto-light-default-style', get_template_directory_uri() . '/css/style.css' );
            wp_enqueue_style( 'maskitto-light-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
            wp_enqueue_style( 'maskitto-light-animate', get_template_directory_uri() . '/css/animate.min.css' );
            wp_enqueue_style( 'maskitto-light-jquery-tosrus-min', get_template_directory_uri() . '/css/jquery.tosrus.all.css' );

            wp_enqueue_script( 'maskitto-light-bootstrap-min', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ) );
            wp_enqueue_script( 'maskitto-light-jquery-tosrus-min', get_template_directory_uri() . '/js/jquery.tosrus.min.all.js', array( 'jquery' ));
            wp_enqueue_script( 'maskitto-light-waypoint-min', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ));
            wp_enqueue_script( 'maskitto-light-counterup-min', get_template_directory_uri() . '/js/jquery.counterup.min.js', array( 'jquery' ));

            //wp_enqueue_script( 'maskitto-light-google-maps', '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7' );
            //wp_enqueue_script( 'maskitto-light-maplace-min', get_template_directory_uri() . '/js/jquery.maplace.min.js', array( 'jquery' ));

        else :

            wp_enqueue_style( 'maskitto-light-bootstrap', get_template_directory_uri() . '/css/bootstrap.css' );
            wp_enqueue_style( 'maskitto-light-default-style', get_template_directory_uri() . '/css/style.css' );
            wp_enqueue_style( 'maskitto-light-font-awesome', get_template_directory_uri() . '/css/font-awesome.css' );
            wp_enqueue_style( 'maskitto-light-animate', get_template_directory_uri() . '/css/animate.css' );
            wp_enqueue_style( 'maskitto-light-jquery-tosrus', get_template_directory_uri() . '/css/jquery.tosrus.all.css' );

            wp_enqueue_script( 'maskitto-light-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ));
            wp_enqueue_script( 'maskitto-light-jquery-tosrus', get_template_directory_uri() . '/js/jquery.tosrus.min.all.js', array( 'jquery' ));
            wp_enqueue_script( 'maskitto-light-waypoint', get_template_directory_uri() . '/js/jquery.waypoints.js', array( 'jquery' ));
            wp_enqueue_script( 'maskitto-light-counterup', get_template_directory_uri() . '/js/jquery.counterup.js', array( 'jquery' ));

            //wp_enqueue_script( 'maskitto-light-google-maps', '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7' );
            //wp_enqueue_script( 'maskitto-light-maplace', get_template_directory_uri() . '/js/jquery.maplace.js', array( 'jquery' ));

        endif;


        wp_enqueue_style( 'maskitto-light-responsive-style', get_template_directory_uri() . '/css/responsive.css' );
        wp_enqueue_style( 'maskitto-light-owl-carousel', get_template_directory_uri() . '/css/slick.css' );

        wp_enqueue_script( 'maskitto-light-slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'maskitto-light-smoothscroll', get_template_directory_uri() . '/js/smoothscroll.js', array( 'jquery' ) );
        wp_enqueue_script( 'maskitto-light-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'masonry' ) );


        if( isset($maskitto_light['body-font']['font-family']) && $maskitto_light['body-font']['font-family'] ) {
            wp_enqueue_style( 'maskitto-light-google-fonts', '//fonts.googleapis.com/css?family='.preg_replace("/ /", "+", $maskitto_light['body-font']['font-family']).':300italic,400italic,300,400,600,700' );
        } else {
            wp_enqueue_style( 'maskitto-light-google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,300,400,600,700' );
        }


    }

    add_action( 'wp_enqueue_scripts', 'maskitto_light_enqueue_styles' ); 

endif;


/** 
 * Load admin media files
 */

if( !function_exists('maskitto_light_admin_enqueue_styles') && is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'maskitto_options' ) :

    function maskitto_light_admin_enqueue_styles() {
        wp_enqueue_style( 'maskitto-light-theme-options', get_template_directory_uri() . '/css/theme-options.css' );
    }

    add_action( 'admin_enqueue_scripts', 'maskitto_light_admin_enqueue_styles' );

endif;


/**
 * Initialize custom post types if Redux framework activated
 */

if (class_exists('ReduxFramework')) {

    /**
     * Initialize slider columns
     */

    if ( ! function_exists( 'maskitto_light_add_slider_columns' ) && !function_exists( 'maskitto_light_manage_slider_columns' ) ) :

        add_filter('manage_edit-slider_columns', 'maskitto_light_add_slider_columns');

        function maskitto_light_add_slider_columns($slider_columns) {
            $new_columns['cb'] = '<input type="checkbox" />';
             
            $new_columns['title'] = __( 'Slide name', 'maskitto-light' );
            $new_columns['caption'] = __( 'Caption', 'maskitto-light' );
            $new_columns['image'] = __( 'Image', 'maskitto-light' );
            $new_columns['author'] = __( 'Author', 'maskitto-light' );
            $new_columns['group'] = __( 'Group', 'maskitto-light' );
            $new_columns['date'] = __( 'Date', 'maskitto-light' );
         
            return $new_columns;
        }

        add_action('manage_slider_posts_custom_column', 'maskitto_light_manage_slider_columns', 10, 2);
         
        function maskitto_light_manage_slider_columns($column_name, $id) {
            global $wpdb;
            switch ($column_name) {
            case 'caption':
                $caption = esc_attr( get_post_meta( $id, "wpcf-caption", true ) );

                if($caption)echo $caption;
                break;
            case 'image':
                $image = esc_url( get_post_meta( $id, 'wpcf-background-image', true ) );
                if($image)echo '<img src="'.$image.'" alt="" height="85" />';
                break;
            case 'group' :

                $terms = get_the_terms( $id, 'slider-group' );
                if ( !empty( $terms ) && count($terms) > 0 ) :
                    $o = array();
                    foreach ( $terms as $term ) {
                        $o[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => get_post_type( $id ), 'genre' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
                        );
                    }
                    echo join( ', ', $o );
                else :
                    _e( '-', 'maskitto-light' );
                endif;

                break;
            default:
                break;
            }
        }  

    endif;


    /**
     * Initialize service columns
     */

    if ( !function_exists( 'maskitto_light_add_services_columns' ) && !function_exists( 'maskitto_light_manage_services_columns' ) ) :

        add_filter('manage_edit-services_columns', 'maskitto_light_add_services_columns');

        function maskitto_light_add_services_columns($services_columns) {
            $new_columns['cb'] = '<input type="checkbox" />';
             
            $new_columns['title'] = __( 'Slide name', 'maskitto-light' );
            $new_columns['icon'] = __( 'Icon', 'maskitto-light' );
            $new_columns['author'] = __( 'Author', 'maskitto-light' );
            $new_columns['group'] = __( 'Group', 'maskitto-light' );
            $new_columns['date'] = __( 'Date', 'maskitto-light' );
         
            return $new_columns;
        }

        add_action('manage_services_posts_custom_column', 'maskitto_light_manage_services_columns', 10, 2);
         
        function maskitto_light_manage_services_columns($column_name, $id) {
            global $wpdb;
            switch ($column_name) {
            case 'icon':
                $icon = esc_attr( get_post_meta( $id, 'wpcf-icon', true ) );
                if($icon)echo $icon;
                break;
            case 'group' :

                $terms = get_the_terms( $id, 'services-group' );
                if ( !empty( $terms ) && count($terms) > 0 ) :
                    $o = array();
                    foreach ( $terms as $term ) {
                        $o[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => get_post_type( $id ), 'genre' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
                        );
                    }
                    echo join( ', ', $o );
                else :
                    _e( '-', 'maskitto-light' );
                endif;

                break;
            default:
                break;
            }
        }

    endif;


    /**
     * Initialize portfolio columns
     */


    if ( !function_exists( 'maskitto_light_manage_portfolio_columns' ) && !function_exists( 'maskitto_light_manage_portfolio_columns' ) ) :

        $count_portfolio = count( get_posts( array( 'post_type' => 'portfolio-item', 'posts_per_page' => 1, 'fields' => 'ids' ) ) );
        if( isset( $count_portfolio ) && $count_portfolio > 0) :
            add_filter('manage_edit-portfolio-item_columns', 'maskitto_light_add_portfolio_columns');
        else :
            add_filter('manage_edit-portfolio_columns', 'maskitto_light_add_portfolio_columns');
        endif;


        function maskitto_light_add_portfolio_columns($projects_columns) {
            $new_columns['cb'] = '<input type="checkbox" />';
            $new_columns['title'] = __( 'Slide name', 'maskitto-light' );
            $new_columns['caption'] = __( 'Caption', 'maskitto-light' );
            $new_columns['image'] = __( 'Image', 'maskitto-light');
            $new_columns['author'] = __( 'Author', 'maskitto-light' );
            $new_columns['categories'] = __( 'Categories', 'maskitto-light' );
            $new_columns['group'] = __( 'Group', 'maskitto-light' );
            $new_columns['date'] = __( 'Date', 'maskitto-light' );
         
            return $new_columns;
        }


        if( isset( $count_portfolio ) && $count_portfolio > 0) :
            add_action('manage_portfolio-item_posts_custom_column', 'maskitto_light_manage_portfolio_columns', 10, 2);
        else :
            add_action('manage_portfolio_posts_custom_column', 'maskitto_light_manage_portfolio_columns', 10, 2);
        endif;

         
        function maskitto_light_manage_portfolio_columns($column_name, $id) {
            global $wpdb;
            switch ($column_name) {
            case 'caption':
                $caption = esc_attr( get_post_meta( $id, 'wpcf-caption', true ) );
                if($caption)echo $caption;
                break;
            case 'image':
                $image = esc_url( get_post_meta( $id, 'wpcf-background-image', true ) );
                if($image)echo '<img src="'.$image.'" alt="" height="85" />';
                break;
            case 'group' :

                $terms = get_the_terms( $id, 'porfolio-group' );
                if ( !empty( $terms ) && count($terms) > 0 ) :
                    $o = array();
                    foreach ( $terms as $term ) {
                        $o[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => get_post_type( $id ), 'genre' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
                        );
                    }
                    echo join( ', ', $o );
                else :
                    _e( '-', 'maskitto-light' );
                endif;

                break;
            default:
                break;
            }
        } 

    endif;


    /**
     * Initialize partners columns
     */

    if ( !function_exists( 'maskitto_light_add_partners_columns' ) && !function_exists( 'maskitto_light_manage_partners_columns' ) ) :

        add_filter('manage_edit-partners_columns', 'maskitto_light_add_partners_columns');

        function maskitto_light_add_partners_columns($partners_columns) {
            $new_columns['cb'] = '<input type="checkbox" />';
             
            $new_columns['title'] = __( 'Slide name', 'maskitto-light' );
            $new_columns['image'] = __( 'Image', 'maskitto-light' );
            $new_columns['author'] = __( 'Author', 'maskitto-light' );
            $new_columns['date'] = __( 'Date', 'maskitto-light' );
         
            return $new_columns;
        }


        add_action('manage_partners_posts_custom_column', 'maskitto_light_manage_partners_columns', 10, 2);
         
        function maskitto_light_manage_partners_columns($column_name, $id) {
            global $wpdb;
            switch ($column_name) {
            case 'image':
                $image = esc_url( get_post_meta( $id, 'wpcf-background-image', true ) );
                if($image)echo '<img src="'.$image.'" alt="" height="85" />';
                break;
            default:
                break;
            }
        }  

    endif;


    /**
     * Initialize testimonials columns
     */

    if ( !function_exists( 'maskitto_light_add_testimonials_columns' ) && !function_exists( 'maskitto_light_manage_testimonials_columns' ) ) :

        add_filter('manage_edit-testimonials_columns', 'maskitto_light_add_testimonials_columns');

        function maskitto_light_add_testimonials_columns($testimonials_columns) {

            $new_columns['cb'] = '<input type="checkbox" />';
            $new_columns['title'] = __( 'Author name', 'maskitto-light' );
            $new_columns['author-description'] = __( 'Author description', 'maskitto-light' );
            $new_columns['quote'] = __( 'Quote', 'maskitto-light' );
            $new_columns['image'] = __( 'Image', 'maskitto-light' );
            $new_columns['author'] = __( 'Author', 'maskitto-light' );
            $new_columns['date'] = __( 'Date', 'maskitto-light' );
         
            return $new_columns;
        }


        add_action('manage_testimonials_posts_custom_column', 'maskitto_light_manage_testimonials_columns', 10, 2);
         
        function maskitto_light_manage_testimonials_columns($column_name, $id) {
            global $wpdb;
            switch ($column_name) {
            case 'author-description':
                $caption = esc_attr( get_post_meta( $id, 'wpcf-author-description', true ) );
                if($caption)echo $caption;
                break;
            case 'quote':
                $caption = esc_attr( get_post_meta( $id, 'wpcf-quote', true ) );
                if($caption)echo $caption;
                break;
            case 'image':
                $image = esc_url( get_post_meta( $id, 'wpcf-quote-image', true ) );
                if($image)echo '<img src="'.$image.'" alt="" height="85" />';
                break;
            default:
                break;
            }
        }  

    endif;


}


/**
 * Initialize maskitto widgets
 */

require get_template_directory() . "/inc/widgets/sh-slider.php";
add_action('widgets_init',
    create_function('', 'return register_widget("Maskitto_Slider");')
);

require get_template_directory() . "/inc/widgets/sh-services.php";
add_action('widgets_init',
    create_function('', 'return register_widget("Maskitto_Services");')
);

require get_template_directory() . "/inc/widgets/sh-portfolio.php";
add_action('widgets_init',
    create_function('', 'return register_widget("Maskitto_Projects");')
);

require get_template_directory() . "/inc/widgets/sh-partners.php";
add_action('widgets_init',
    create_function('', 'return register_widget("Maskitto_Partners");')
);

require get_template_directory() . "/inc/widgets/sh-blog.php";
add_action('widgets_init',
    create_function('', 'return register_widget("Maskitto_Blog");')
);

require get_template_directory() . "/inc/widgets/sh-include_page.php";
add_action('widgets_init',
     create_function('', 'return register_widget("Maskitto_Include_Other_Page");')
);

require get_template_directory() . "/inc/widgets/sh-counter.php";
add_action('widgets_init',
     create_function('', 'return register_widget("Maskitto_Counter");')
);

require get_template_directory() . "/inc/widgets/sh-testimonials.php";
add_action('widgets_init',
     create_function('', 'return register_widget("Maskitto_Testimonials");')
);

require get_template_directory() . "/inc/widgets/sh-slogan.php";
add_action('widgets_init',
     create_function('', 'return register_widget("Maskitto_Slogan");')
);


/**
 * Initialize maskitto custom login
 */

if(isset($maskitto_light['admin-login-logo']) && isset($maskitto_light['admin-login-logo']['url']) && $maskitto_light['admin-login-logo']['url']){

    function maskitto_light_login_logo() {

        GLOBAL $maskitto_light;

        echo '<style type="text/css">
        h1 a { background-image: url('.esc_url( $maskitto_light['admin-login-logo']['url'] ).') !important; background-position: 50% 50%!important; width: 75%!important; background-size: cover!important }
        </style>';
    }
    add_action('login_head', 'maskitto_light_login_logo');

}


/**
 * Remove WordPress.org restricted menu
 */

function maskitto_light_remove_submenu_item() {
    remove_submenu_page( 'themes.php', 'install-required-plugins' );
}
add_action( 'admin_menu', 'maskitto_light_remove_submenu_item', 999 );


/**
 * Adds theme widgets group
 */

function maskitto_light_add_widget_tabs($tabs) {
    $tabs[] = array(
        'title' => __('Theme Widgets', 'maskitto-light'),
        'filter' => array(
            'groups' => 'theme-widgets'
        )
    );

    return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'maskitto_light_add_widget_tabs', 20);


/* Header search form */

function maskitto_light_header_seach_form( $form ) {
    $form = '<form role="search" method="get" action="'.esc_url( home_url() ).'">';
    $form .= '<input type="text" class="top-search-field" name="s" value="'.esc_html( get_search_query( false ) ).'" placeholder="'.__( 'Search here..', 'maskitto-light' ).'" />';
    $form .= '</form>';
    return $form;
}
add_filter( 'get_search_form', 'maskitto_light_header_seach_form' );


/* Custom read more button */

add_filter( 'the_content_more_link', 'maskitto_light_modify_read_more_link' );
function maskitto_light_modify_read_more_link() {
    return '<a href="' . get_permalink() . '" class="btn btn-default page-node"><i class="fa fa-angle-right"></i>'.__( 'Read more', 'maskitto-light' ).'</a>';
}


/* Preview word limit */

if( isset($maskitto_light['preview-word-limit']) && $maskitto_light['preview-word-limit'] >= 10 ) :
    function maskitto_light_custom_excerpt_length( $length ) {
        GLOBAL $maskitto_light;
        return intval( $maskitto_light['preview-word-limit'] );
    }
    add_filter( 'excerpt_length', 'maskitto_light_custom_excerpt_length', 999 );
endif;

?>
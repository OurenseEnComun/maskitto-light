<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Maskitto Light
 */


if ( ! function_exists( 'maskitto_light_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */

function maskitto_light_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'maskitto-light' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'maskitto-light' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'maskitto-light' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;


/**
 * Display navigation to next/previous post
 */

if ( ! function_exists( 'maskitto_light_post_nav' ) ) :

    function maskitto_light_post_nav() {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
        $next     = get_adjacent_post( false, '', false );

        if ( ! $next && ! $previous ) {
            return;
        }
        ?>
        <div class="post-navigation">
            <div class="row">
                <?php
                    previous_post_link( '<div class="col-md-6 col-sm-6 nav-previous grey">%link</div>', '<i class="fa fa-angle-left"></i>Previous post', ''  );
                    next_post_link(     '<div class="col-md-6 col-sm-6 nav-next text-right grey">%link</div>', 'Next post<i class="fa fa-angle-right"></i>', '' );
                ?>
            </div>
        </div>
        <?php
    }

endif;


if ( ! function_exists( 'maskitto_light_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function maskitto_light_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( 'Posted on %s', 'post date', 'maskitto-light' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', 'maskitto-light' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;


if ( ! function_exists( 'maskitto_light_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function maskitto_light_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'maskitto-light' ) );
		if ( $categories_list && maskitto_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'maskitto-light' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'maskitto-light' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'maskitto-light' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'maskitto-light' ), __( '1 Comment', 'maskitto-light' ), __( '% Comments', 'maskitto-light' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'maskitto-light' ), '<span class="edit-link">', '</span>' );
}
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function maskitto_light_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'maskitto_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'maskitto_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so maskitto_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so maskitto_categorized_blog should return false.
		return false;
	}
}


/**
 * Initialize admin edit button
 */

if ( ! function_exists( 'maskitto_light_admin_edit' ) ) :

    function maskitto_light_admin_edit( $blog_id ) {
        if ( current_user_can('edit_post', $blog_id) ) { ?>
            <a href="<?php echo get_edit_post_link( $blog_id ); ?>" class="admin-edit"><i class="fa fa-pencil-square-o"></i> <?php _e( 'edit', 'maskitto-light' ); ?></a>
    <?php } }

endif;


/**
 * Social icons
 */

if ( ! function_exists( 'maskitto_light_social_icons' ) ) :

    function maskitto_light_social_icons() {
    	global $maskitto_light;
    ?>

		<?php 
			if( !isset($maskitto_light['header-social-links']) || $maskitto_light['header-social-links'] == 1 ) :
				$new_tab = ' target = "_blank" ';
			else :
				$new_tab = '';
			endif;
		?>

		<?php if(isset($maskitto_light['social-network-facebook']) && $maskitto_light['social-network-facebook']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-facebook']); ?>"<?php echo $new_tab; ?>><i class="fa fa-facebook"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-twitter']) && $maskitto_light['social-network-twitter']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-twitter']); ?>"<?php echo $new_tab; ?>><i class="fa fa-twitter"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-google']) && $maskitto_light['social-network-google']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-google']); ?>"<?php echo $new_tab; ?>><i class="fa fa-google-plus"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-youtube']) && $maskitto_light['social-network-youtube']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-youtube']); ?>"<?php echo $new_tab; ?>><i class="fa fa-youtube"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-instagram']) && $maskitto_light['social-network-instagram']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-instagram']); ?>"<?php echo $new_tab; ?>><i class="fa fa-instagram"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-flickr']) && $maskitto_light['social-network-flickr']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-flickr']); ?>"<?php echo $new_tab; ?>><i class="fa fa-flickr"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-foursquare']) && $maskitto_light['social-network-foursquare']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-foursquare']); ?>"<?php echo $new_tab; ?>><i class="fa fa-foursquare"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-skype']) && $maskitto_light['social-network-skype']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-skype']); ?>"<?php echo $new_tab; ?>><i class="fa fa-skype"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-vk']) && $maskitto_light['social-network-vk']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-vk']); ?>"<?php echo $new_tab; ?>><i class="fa fa-vk"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-wordpress']) && $maskitto_light['social-network-wordpress']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-wordpress']); ?>"<?php echo $new_tab; ?>><i class="fa fa-wordpress"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-linkedin']) && $maskitto_light['social-network-linkedin']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-linkedin']); ?>"<?php echo $new_tab; ?>><i class="fa fa-linkedin"></i></a>
		<?php } ?>

		<?php if(isset($maskitto_light['social-network-pinterest']) && $maskitto_light['social-network-pinterest']){ ?>
			<a href="<?php echo esc_url($maskitto_light['social-network-pinterest']); ?>"<?php echo $new_tab; ?>><i class="fa fa-pinterest"></i></a>
		<?php } ?>

    <?php }

endif;


/**
 * Generate custom CSS
 */

if ( ! function_exists( 'maskitto_light_generate_css' ) ) :

    function maskitto_light_generate_css() {
    	global $maskitto_light;

		if( isset( $maskitto_light ) ) : ob_start(); ?>
			<style type="text/css">
				<?php if( isset($maskitto_light['primary-color']) && $maskitto_light['primary-color'] && $maskitto_light['primary-color'] != '#e15454' ) { ?>
					body a, body a:hover {
						color: <?php echo esc_attr($maskitto_light['primary-color']); ?>;
					}
					.service-icon i, header .navbar-nav .dropdown.open .dropdown-toggle, .active-category, .post-gallery-title, .comment-navigation .current, .contact-social-icons a, .countup-circle {
						color: <?php echo esc_attr($maskitto_light['primary-color']); ?>!important;
					}

					.service-icon, .service-line, .service-line-bottom, .current-menu-item a, .current_page_parent a, blockquote, header .navbar-nav .dropdown.open .dropdown-toggle, .contact-social-icons a {
						border-color: <?php echo esc_attr($maskitto_light['primary-color']); ?>!important;
					}

					.page-404, .btn-danger, .section-title-line, .blog-category, header .navbar-nav .dropdown.open .current-menu-item, .widget-area-2 .widget_search, .widget-area-2 .search-field, #wp-calendar #today, .blog-layout-2 #submit, .blog-layout-2 .comments-title, .wpcf7-submit {
						background-color: <?php echo esc_attr($maskitto_light['primary-color']); ?>!important;
					}

					@media (min-width: 1000px) {

						 header .navbar-nav .current-menu-item a, header .navbar-nav .current_page_parent a {
							color: <?php echo esc_attr($maskitto_light['primary-color']); ?>!important;
						}

					}

					@media (max-width: 1000px) {

						header .navbar-nav .current-menu-item, header .navbar-nav .current_page_parent {
							background-color: <?php echo esc_attr($maskitto_light['primary-color']); ?>;
						}

					}
				<?php } ?>

				<?php if( isset($maskitto_light['body-background-color']) && $maskitto_light['body-background-color'] != '#ffffff' ) { ?>
					body {
						background-color: <?php echo esc_attr($maskitto_light['body-background-color']); ?>!important;
					}
				<?php } ?>

				<?php if( isset($maskitto_light['header-background-color']) && $maskitto_light['header-background-color'] != '#ffffff' ) { ?>
					header nav.primary, header .header-details {
						background-color: <?php echo esc_attr($maskitto_light['header-background-color']); ?>;
					}
				<?php } ?>

				<?php if( isset($maskitto_light['footer-background-color']) && $maskitto_light['footer-background-color'] != '#565656' ) { ?>
					footer.bottom {
						background-color: <?php echo esc_attr($maskitto_light['footer-background-color']); ?>!important;
					}
				<?php } ?>

				<?php if( isset($maskitto_light['body-font']) ) { ?>
					body, .section-content {
						font-family: <?php echo esc_attr( $maskitto_light['body-font']['font-family'] ); ?>!important;
						color: <?php echo esc_attr( $maskitto_light['body-font']['color'] ); ?>!important;
					}
				<?php } ?>

				<?php if( isset($maskitto_light['blog-background']) ) { ?>
					.page-blog {
						<?php if( isset($maskitto_light['blog-background']['background-color']) && $maskitto_light['blog-background']['background-color'] ) : ?>
							background-color: <?php echo esc_attr($maskitto_light['blog-background']['background-color']); ?>!important;
						<?php endif; ?>
						<?php if( isset($maskitto_light['blog-background']['background-image']) && $maskitto_light['blog-background']['background-image'] ) : ?>
							background-image: url(<?php echo esc_url($maskitto_light['blog-background']['background-image']); ?>)!important;
						<?php elseif( isset($maskitto_light['blog-background']['background-image']) ) : ?>;
							background-image: none!important;
						<?php endif; ?>
					}
				<?php } ?>

				<?php if( isset($maskitto_light['custom-css']) && $maskitto_light['custom-css'] ) { ?>
					<?php echo html_entity_decode( esc_attr($maskitto_light['custom-css']) ); ?>
				<?php } ?>

			</style>
			<?php echo preg_replace( '/\s+/', ' ', ob_get_clean() ); 
		endif;
	}
endif;


/**
 * Generate custom JS
 */

if ( ! function_exists( 'maskitto_light_generate_js' ) ) :
    function maskitto_light_generate_js() {
    	global $maskitto_light; 
    	if( !isset( $maskitto_light['nacigation-dropdown'] ) || $maskitto_light['nacigation-dropdown'] == 1 ) : ?>

			<script type="text/javascript">
			jQuery( document ).ready(function( $ ) {
				$('.navbar .dropdown').hover(function() {
				  $(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
				}, function() {
				  $(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
				});

				$( '.dropdown-toggle' ).on( 'click', function() {
					window.location.href = $(this).attr( 'href' );
					return false;
				});
			});
			</script>

    <?php endif; }
endif;


/**
 * Modified function from WordPress 4.2 media.php to capture first media item
 */

function maskitto_light_get_media_embedded_in_content_first( $content, $types = null ) {
	$html = array();

	$allowed_media_types = apply_filters( 'get_media_embedded_in_content_allowed', array( 'audio', 'video', 'object', 'embed', 'iframe' ) );

	if ( ! empty( $types ) ) {
		if ( ! is_array( $types ) ) {
			$types = array( $types );
		}

		$allowed_media_types = array_intersect( $allowed_media_types, $types );
	}

	$tags = implode( '|', $allowed_media_types );

	if ( preg_match_all( '#<(?P<tag>' . $tags . ')[^<]*?(?:>[\s\S]*?<\/(?P=tag)>|\s*\/>)#', $content, $matches ) ) {
		foreach ( $matches[0] as $match ) {
			return $match;
		}
	}
}


if ( ! function_exists( 'maskitto_light_get_media' ) ) :
    function maskitto_light_get_media( $id ) {

		$media = '';
		$content = do_shortcode( apply_filters( 'the_content', get_the_content( $id ) ) );
		$embeds = maskitto_light_get_media_embedded_in_content_first( $content );
		return $embeds;
    }
endif;


if ( ! function_exists( 'maskitto_light_paginate_links' ) ) :
    function maskitto_light_paginate_links() {

		if( get_option('permalink_structure') ) :
			$format = '?paged=%#%';
		else :
			$format = 'page/%#%/';
		endif;


		$big = 999999999;
		if( $format =='?paged=%#%' ) :
			$base = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
		else :
			$base = @add_query_arg('paged','%#%');
		endif;


		$args = array(
			'base' => $base,
			'format' => $format,
		);


		// Disabled, because of other pagination
		if( 1 != 1 ) {
			$pagenate2 = wp_link_pages( $args );
		}

		return paginate_links( $args );

    }
endif;


/* Check for widgets */

if ( ! function_exists( 'maskitto_light_get_widgets' ) ) :
	function maskitto_light_get_widgets( $content ) {

		$content = do_shortcode( apply_filters( 'the_content', $content ) );
		preg_match('/div class="page-section"/s', $content, $i);
		$widgets_count = count( $i );

	?>
	<div>
		<?php if( $widgets_count > 0 ) : ?>
			<div class="entry-content">
				<?php echo $content; ?>
			</div>
		<?php endif; ?>
	</div> 
	<?php }
endif;


/*  */

if ( ! function_exists( 'maskitto_light_generate_page' ) ) :
	function maskitto_light_generate_page( $id, $front = 0 ) {

		$posts = new WP_Query( array( 'p' => $id, 'post_type' => 'page' ) );
		while($posts->have_posts()): $posts->the_post();
			$title = get_the_title();
			$content = get_the_content();
			$content = do_shortcode( apply_filters( 'the_content', $content ) );
		endwhile;


		if( $id > 0 ) :

	        $style1 = (string) NULL;
	        $style2 = (string) NULL;
	        $style3 = (string) NULL;

	        $subtitle = esc_attr( get_post_meta( $id, 'wpcf-subtitle', true ));
	        $titlebar = esc_attr( get_post_meta( $id, 'wpcf-title-layout', true ));
	        $title_color = esc_attr( get_post_meta( $id, 'wpcf-title-color', true ));
	        $background_color = esc_attr( get_post_meta( $id, 'wpcf-background-color', true ));
	        $background_image = esc_url( get_post_meta( $id, 'wpcf-background-image', true ));
	        $padding = esc_attr( get_post_meta( $id, 'wpcf-page-padding', true ));
	        $readmore = esc_attr( get_post_meta( $id, 'wpcf-read-more', true ));

	        $button_name = esc_attr( get_post_meta( $id, 'wpcf-button-name', true ));
	        $button_url = esc_url( get_post_meta( $id, 'wpcf-button-url', true ));
	        if( !$button_url ) $button_url = '#';
	        $button_icon = esc_attr( get_post_meta( $id, 'wpcf-button-icon', true ));
	        $button_type = esc_attr( get_post_meta( $id, 'wpcf-button-tone', true ));

	        $googlemaps_image = esc_url( get_post_meta( $id, 'wpcf-google-maps-image', true ));
	        $googlemaps_url = esc_url( get_post_meta( $id, 'wpcf-google-maps-url', true ));
	        $googlemaps_height = esc_attr( get_post_meta( $id, 'wpcf-google-maps-height', true ));
	        $sociallinks = esc_attr( get_post_meta( $id, 'wpcf-social-links', true ));


	        if( $padding == 'small' ) {
	            $style1.= "padding: 35px 0;";
	        } else if( $padding == 'large' ) {
	            $style1.= "padding: 150px 0;";
	        }

	        if( $background_color ) {
	            $style1.= "background-color: $background_color;";
	        }

	        if( $background_image ) {
	            $style1.= "background-image: url($background_image);";
	        }

	        if( $title_color ) {
	            $style2.= "color: $title_color;";
	        }

	        if( $googlemaps_height == 'extrasmall' ) {
	            $style3.= "height: 200px;";
	        } else if( $googlemaps_height == 'small' ) {
	            $style3.= "height: 325px;";
	        } else if( $googlemaps_height == 'large' ) {
	            $style3.= "height: 550px;";
	        } else if( $googlemaps_height == 'extralarge' ) {
	            $style3.= "height: 750px;";
	        } else {
	            $style3.= "height: 450px;";
	        }


	        /* Check content for widgets */
	        $widgets_count = 0;
			preg_match('/div class="page-section"/s', $content, $matches);
			if( count($matches) > 0 ) {
				$style1.= "padding-bottom: 0px!important;";
				$widgets_count = count( $matches );
			}

	    ?>
	        <div id="page-id-<?php echo $id; ?>" class="page-section" style="<?php echo $style1; ?>">
	            <div class="container page-list">


	                <?php if( $titlebar == 'small' ) : ?>

	                    <h4 class="page-node text-center" style="<?php echo $style2; ?>">
	                        <?php echo $title; ?>
	                        <?php echo maskitto_light_admin_edit($id); ?>
	                    </h4>
	                    <?php if( $content && $widgets_count == 0 ) : ?>
	                        <div class="page-node section-content" style="<?php echo $style2; ?>">
	                            <div class="post-inner" style=" font-size: 13px; padding-top: 10px;">
	                                <?php echo $content; ?>
	                            </div>
	                        </div>
	                    <?php endif; ?>

	                <?php elseif( $titlebar == 'large' ) : ?>

	                    <div class="page-node section-title-large text-center">
	                        <div class="title" style="<?php echo $style2; ?>">
	                            <?php echo $title; ?>
	                            <?php echo maskitto_light_admin_edit($id); ?>
	                        </div>
	                    </div>
	                    <?php if( $content && $widgets_count == 0 ) { ?>
	                        <div class="page-node section-content-large" style="<?php echo $style2; ?>">
	                            <div class="post-inner">
	                                <?php echo $content; ?>
	                            </div>
	                        </div>
	                    <?php } ?>

	                <?php else : ?>

	                    <?php if( $titlebar != 'none' ) : ?>
	                        <div class="page-node section-title text-center">
	                            <h3 style="<?php echo $style2; ?>">
	                                <?php echo $title; ?>
	                                <?php echo maskitto_light_admin_edit($id); ?>
	                            </h3>

	                            <?php if( $subtitle ) : ?>
	                                <div class="subtitle">
	                                    <p><?php echo $subtitle; ?></p>
	                                </div>
	                            <?php endif; ?>

	                            <?php if( ($titlebar || $subtitle ) ) : ?>
	                                <div class="section-title-line"></div>
	                            <?php endif; ?>
	                        </div>
	                    <?php elseif( current_user_can('edit_post', $id ) ) : ?>
	                        <div class="page-node text-center" style="margin-bottom: 10px;"><?php echo maskitto_light_admin_edit($id); ?></div>
	                    <?php endif; ?>

	                    <?php if( $content && $widgets_count == 0 && !$googlemaps_height ) : ?>
	                        <div class="page-node section-content" style="<?php echo $style2; ?>">
	                            <div class="post-inner">
	                                <?php echo $content; ?>
	                            </div>
	                        </div>
	                    <?php endif; ?>

	                <?php endif; ?>


	                <?php if( $googlemaps_height ) { ?>
	                    <div class="page-node contact-section">
	                        <div class="contact-subtitle"><?php _e( 'Information', 'maskitto-light' ); ?></div>
	                        <div><div class="post-inner"><?php echo $content; ?></div></div>

	                        <?php if( isset( $sociallinks ) && $sociallinks == '1' ) { ?>
	                            <div class="contact-social-icons">  
	                                <?php echo maskitto_light_social_icons(); ?>
	                            </div>
	                        <?php } ?>
	                    </div>
	                <?php } ?>            


					<?php if( $front == 1 ) : ?>
		                <?php if( $button_name ) { ?>
		                    <a href="<?php echo $button_url; ?>" class="btn btn-default page-node section-button section-button-<?php echo $button_type; ?>">
		                        <?php if( $button_icon ) { ?>
		                            <i class="fa <?php echo $button_icon; ?>"></i>
		                        <?php } ?>
		                        <?php echo $button_name; ?>
		                    </a>
		                <?php } ?>


		                <?php if( $readmore ) { ?>
		                    <a href="<?php echo esc_url( get_permalink( $id ) ); ?>" class="btn btn-default page-node">
		                        <i class="fa fa-angle-right"></i>
		                        <?php _e( 'Read more', 'maskitto-light' ); ?>
		                    </a>
		                <?php } ?>
	                <?php endif; ?>


	            </div>
	        </div>


	        <?php if( $googlemaps_url ) { ?>
	            <div class="page-section googlemap" style="<?php echo $style3; ?>">
	                <a href="<?php echo $googlemaps_url; ?>" target="_blank" class="google-maps-image" style="background-image: url(<?php echo $googlemaps_image; ?>);"></a>
	                <div class="googlemap-loading"><i class="fa fa-circle-o-notch fa-spin"></i></div>
	            </div>
	        <?php } ?>


		<?php endif; 
	}
endif;
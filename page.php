<?php
/**
 * The template for displaying all pages.
 *
 * @package Maskitto Light
 */

$maskitto_light;
get_header(); ?>

	<?php while ( have_posts() ) : the_post();

			$comments = get_post_meta( get_the_ID(), 'wpcf-comments', true );
	        echo maskitto_light_generate_page( get_the_ID() );
	        wp_reset_postdata();

			echo maskitto_light_get_widgets( get_the_content() );

		?>

		<?php if($comments  == 1 ) : ?>
			<div class="page-section" style="padding: 50px 0!important;">
				<div class="container page-list">
					<?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) : ?>
						<div class="blog-layout-2">
					<?php endif; ?>

						<?php
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						?>

					<?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) : ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

	<?php endwhile; ?>
<?php get_footer(); ?>
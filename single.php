<?php
/**
 * The template for displaying all single posts.
 *
 * @package Maskitto Light
 */

get_header();
$catnow = get_the_category(get_query_var('p'));
?>

<?php if( !isset( $maskitto_light['blog-categories'] ) || $maskitto_light['blog-categories'] == 1 ) : ?>
	<?php if( isset( $catnow ) && $catnow['0']->term_id && $catnow['0']->term_id > 0 ) : ?>
		<div class="page-category">
			<div class="container">
				<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="category-item<?php if( !get_query_var('cat') ) { echo ' active'; } ?>"><?php _e( 'All', 'maskitto-light' ); ?></a>
				<?php

				$args = array(
					'type'                     => 'post',
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'count',
					'order'                    => 'desc',
					'hide_empty'               => 1,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'category',
					'pad_counts'               => false 

				);
				$categories = get_categories( $args );
				foreach ($categories as $category) { ?>
					<?php if( isset( $catnow['0'] ) && $category->cat_ID == $catnow['0']->cat_ID ) { ?>
						<a href="<?php echo get_category_link( $category->term_id ); ?>" class="category-item active-category"><?php echo $category->cat_name; ?></a>
					<?php } else { ?>
						<a href="<?php echo get_category_link( $category->term_id ); ?>" class="category-item"><?php echo $category->cat_name; ?></a>
					<?php } ?>
				<?php } ?>
				<?php if( count( $categories ) > 6 ) { ?>
					<a href="#" class="category-item category-show-all"><?php _e( '(more)', 'maskitto-light' ); ?></a>
				<?php } ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

<div class="page-section page-blog">
	<div class="container">


		<?php if( get_post_format() == 'aside' ) : ?>
			<div class="row">
				<div class="col-md-8 blog-column-left" style="padding-right: 18px;">
		<?php endif; ?>


			<div class="blog-post">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

					<?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) : ?>
						<div class="blog-layout-2">
					<?php endif; ?>

						<?php maskitto_light_post_nav(); ?>
						<div class="blog-bottom-large">
							<?php
								// If comments are open or we have at least one comment, load up the comment template
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;

								if ( is_singular() ){
									wp_enqueue_script( 'comment-reply' );
								}
							?>
						</div>

					<?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) : ?>
						</div>
					<?php endif; ?>

				<?php endwhile; ?>
			</div>


		<?php if( get_post_format() == 'aside' ) : ?>
				</div>
				<div class="col-md-4 blog-column-right">

					<?php get_sidebar(); ?>

				</div>
			</div>
		<?php endif; ?>


	</div>
</div>

<?php get_footer(); ?>

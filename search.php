<?php
/**
 * The template for displaying search results pages.
 *
 * @package Maskitto Light
 */

get_header(); ?>

<div class="page-category">
	<a href="#" class="category-item"><?php printf( __( 'Search Results for: %s', 'maskitto-light' ), '<span>' . get_search_query() . '</span>' ); ?></a>
</div>

<div class="page-section page-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-<?php echo ( !isset( $maskitto_light['blog-widgets'] ) || $maskitto_light['blog-widgets'] == 1 ) ? '8' : '12'; ?> blog-column-left">
				<div class="row blog-list blog-search-page">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', get_post_format() ); ?>

					<?php endwhile; ?>

					<?php maskitto_light_paging_nav(); ?>

				<?php else : ?>

					<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

				</div>
				<div class="comment-navigation grey-light">
					<?php echo maskitto_light_paginate_links(); ?>
				</div>
			</div>
			<?php if( !isset( $maskitto_light['blog-widgets'] ) || $maskitto_light['blog-widgets'] == 1 ) : ?>
			<div class="col-md-4 blog-column-right">

				<?php get_sidebar(); ?>

			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
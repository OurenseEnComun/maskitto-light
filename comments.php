<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Maskitto Light
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">


	<?php comment_form(array(
		'comment_notes_after' => '',
		'comment_notes_before' => '',
		'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . __( 'Write your comment here ...', 'maskitto-light' ) . '"></textarea></p>',
		'fields' => apply_filters( 'comment_form_default_fields', array(
			'author' =>
				'<p class="comment-form-author">
					<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder = "' . __( 'Name ', 'maskitto-light' ) . ( $req ? '*' : '' ) . '" size="30" />
				</p>',

			'email' =>
				'<p class="comment-form-email">
					<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .'" placeholder = "' . __( 'Email ', 'maskitto-light' ) .( $req ? '*' : '' ) . '" size="30" />
				</p>',

			'url' =>
				'<p class="comment-form-url">
					<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder = "' . __( 'Website ', 'maskitto-light' ) .( $req ? '*' : '' ) . '" size="30" />
				</p>'
			)
		),
	)); ?>


	<?php if ( have_comments() ) : ?>

		<?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) : ?>
			<div class="blog-layout-2-comments">
				<div class="page-blog" style="height: 15px; margin: 0 -56px; padding: 0!important; margin-top: 30px;"></div>
		<?php endif; ?>


			<h2 class="comments-title">
				<i class="fa fa-comments-o" style="padding-right: 5px;"></i>
				<?php
					printf( _nx( '1 comment', '%1$s comments', get_comments_number(), 'comments title', 'maskitto-light' ),
						number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
				?>
			</h2>

			<ol class="comment-list">
				<?php

				function maskitto_light_comment($comment, $args, $depth) {
					$GLOBALS['comment'] = $comment;
					extract($args, EXTR_SKIP);

					if ( 'div' == $args['style'] ) {
						$tag = 'div';
						$add_below = 'comment';
					} else {
						$tag = 'li';
						$add_below = 'div-comment';
					}
				?>
					<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">

					<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
						<div class="comment-column-left">
							<div class="comment-thumb"><?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
						</div>
						<div class="comment-column-right">
							<div class="row">
								<div class="col-md-6">
									<?php printf( '<span class="comment-author">%s</span>', get_comment_author_link() ); ?>
									<?php if ( $comment->comment_approved == '0' ) : ?>
										<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'maskitto-light' ); ?></em>
									<?php endif; ?>

									<span class="comment-date comment-meta commentmetadata grey">
										<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
											<?php
												echo get_comment_date().' '.__('at', 'maskitto-light').' '.get_comment_time();
											?>
										</a>
									</span>
								</div>
							</div>

							<div class="comment-content">
								<?php comment_text(); ?>
							</div>

							<div class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
							<span class="grey">
								<?php
									edit_comment_link( __( 'Edit', 'maskitto-light' ), '  ', '' );
								?>
							</span>
							</div>
						</div>
					</div>

				<?php }
					wp_list_comments( array(
						'style'      => 'ol',
						'short_ping' => true,
						'avatar_size' => 54,
						'max_depth' => '5',
						'callback' => 'maskitto_light_comment',

					) );
				?>
			</ol><!-- .comment-list -->


			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<div class="comment-navigation grey-light"><?php paginate_comments_links(); ?></div>
			<?php endif; // check for comment navigation ?>

		<?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) : ?>
			</div>
		<?php endif; ?>

	<?php endif; // have_comments() ?>


		<?php if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'maskitto-light' ); ?></p>
		<?php endif; ?>

</div>
<?php
/**
 * @package Maskitto Light
 */
global $maskitto_light;

$blog_url = esc_url( get_permalink() );
$comments_count = get_comments_number( '0', '1', '%' );
$category = get_the_category();

if( count($category) ) :
    $category_name = $category[0]->name;
    $category_link = get_category_link( get_cat_ID( $category_name ) );
endif;
?>

<?php /* Old blog layout */ if( isset($maskitto_light['blog-layout']) && $maskitto_light['blog-layout'] != 1 ) : ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-4 blog-item' ); ?>>
        <div class="blog-item-container">

            <div class="blog-top">
                <a href="<?php echo $blog_url; ?>" class="blog-title">
                    <?php the_title(); ?>
                    <?php echo maskitto_light_admin_edit(get_the_ID()); ?>
                </a>
                <div class="blog-details">
                    <span><?php echo get_the_date(); ?></span>
                    <span class="post-author-name"><?php _e( 'By', 'maskitto-light' ); ?> <?php echo get_the_author(); ?></span>
                </div>
            </div>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="blog-media">
                    <?php echo the_post_thumbnail(); ?>

                    <?php if( count($category) && $category_name ) : ?>
                        <a href="<?php echo $category_link; ?>" class="blog-category"><?php echo $category_name; ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
           
            <div class="blog-bottom">
                <div class="blog-content">
                    <?php if ( !has_post_thumbnail() ) : ?>
                        <?php if( count($category) && $category_name ) : ?>
                            <a href="<?php echo $category_link; ?>" class="blog-category blog-category-right"><?php echo $category_name; ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php the_excerpt(); ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo $blog_url; ?>" class="blog-more">
                            <i class="fa fa-angle-right"></i>
                            <?php _e( 'Read more', 'maskitto-light' ); ?>
                        </a>
                    </div>
                    <div class="col-md-6 text-right blog-comments-count">
                        <a href="<?php comments_link(); ?>"><i class="fa fa-comment-o"></i> | <?php echo $comments_count; ?></a>
                    </div>
                </div>
            </div>

        </div>
    </article>

<?php /* New blog layout */ else : ?>

    <?php /* Get media */
        $media = maskitto_light_get_media( get_the_ID() );
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-4 blog-item blog-layout-2' ); ?> <?php if( isset( $maskitto_light['blog-widgets'] ) && $maskitto_light['blog-widgets'] == 0 ) : ?> style="width: 33.3%;"<?php endif; ?>>
        <div class="blog-item-container">

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="blog-media">
                    <a href="<?php echo $blog_url; ?>" class="blog-media-thumbnail">
                        <?php echo the_post_thumbnail(); ?>
                    </a>

                    <?php if( count($category) && $category_name ) : ?>
                        <a href="<?php echo $category_link; ?>" class="blog-category"><?php echo $category_name; ?></a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <?php if( ( get_post_format() == 'video' || get_post_format() == 'audio' ) && $media ) : ?>
                    <div class="post-inner-media post-media-small">
                        <?php echo $media; ?>
                    </div>
                <?php endif; ?> 

                <?php if( count($category) && $category_name ) : ?>
                    <a href="<?php echo $category_link; ?>" class="blog-category blog-category-left"><?php echo $category_name; ?></a>
                <?php endif; ?>
            <?php endif; ?>

            <div class="blog-top">
                <a href="<?php echo $blog_url; ?>" class="blog-title">
                    <?php the_title(); ?>
                    <?php echo maskitto_light_admin_edit(get_the_ID()); ?>
                </a>
                <div class="blog-details">
                    <span style="padding-right: 6px;"><?php echo get_the_date(); ?></span>
                    <span class="post-author-name"><?php _e( 'By', 'maskitto-light' ); ?> <?php echo get_the_author(); ?></span>
                </div>
            </div>

            <div class="blog-bottom">
                <div class="blog-content">
                    <?php the_excerpt(); ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo $blog_url; ?>" class="blog-more">
                            <?php _e( 'Read more', 'maskitto-light' ); ?>
                            <i class="fa fa-angle-right" style="padding-left: 5px;"></i>
                        </a>
                    </div>
                    <div class="col-md-6 text-right blog-comments-count">
                        <a href="<?php comments_link(); ?>"><i class="fa fa-comment-o" style="padding-right: 5px;"></i><?php echo $comments_count; ?></a>
                    </div>
                </div>
            </div>

        </div>
    </article>

<?php endif; ?>
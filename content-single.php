<?php
/**
 * @package Maskitto Light
 */
global $maskitto_light;

$comments_count = get_comments_number( '0', '1', '%' );
$category = get_the_category();

if( count($category) ) :
    $category_name = $category[0]->name;
    $category_link = get_category_link( get_cat_ID( $category_name ) );
endif;

$gallery = get_post_meta( get_the_ID(), 'wpcf-image');
?>

<?php /* Old blog layout */ if( isset($maskitto_light['blog-layout']) && $maskitto_light['blog-layout'] != 1 ) : ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="blog-item-container">

            <div class="blog-top blog-top-large">
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="blog-title"><?php the_title(); ?><?php echo maskitto_light_admin_edit(get_the_ID()); ?></a>

                <div class="row">
                    <div class="col-md-6">
    		            <div class="blog-details">
    		                <span><?php echo the_date(); ?></span>
    		                <span class="post-author-name"><?php _e( 'By', 'maskitto-light' ); ?> <?php echo get_the_author(); ?></span>
    		            </div>
    		        </div>
                    <div class="col-md-6 text-right">
                        <a href="#"><i class="fa fa-comment-o"></i> | <?php comments_number( '0', '1', '%' ); ?></a>
                    </div>
                </div>
            </div>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="blog-media">
                    <?php echo the_post_thumbnail(); ?>

                    <?php 
                        $cat = get_the_category();
                        if( $cat[0]->name ) :
                    ?>
                        <a href="<?php echo get_category_link( get_cat_ID( $cat[0]->name ) ); ?>" class="blog-category"><?php echo $cat[0]->name; ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
           
            <div class="blog-bottom blog-bottom-large">
                <div class="blog-content">
                    <?php if ( !has_post_thumbnail() ) {
                        $cat = get_the_category();
                        if( isset( $cat[0] ) && $cat[0]->name ){
                    ?>
                        <a href="<?php echo get_category_link( get_cat_ID( $cat[0]->name ) ); ?>" class="blog-category blog-category-right"><?php echo $cat[0]->name; ?></a>
                    <?php } } ?>
                    <div class="post-inner"><?php the_content(); ?></div>
                </div>
            </div>

            <?php 
            $gallery_count = 0;
            if( isset( $gallery ) ) :
                foreach($gallery as $media) :
                    if( esc_url( $media ) ) :
                        $gallery_count++;
                    endif;
                endforeach;
            endif;

            if( $gallery_count > 0) : ?>
            <div class="blog-bottom blog-bottom-large post-gallery">
                <div class="post-gallery-title"><?php _e( 'Gallery', 'maskitto-light' ); ?></div>
                <div class="post-gallery-list group">
                    <?php foreach($gallery as $media) : ?>
                        <a href="<?php echo esc_url( $media ); ?>" class="post-gallery-item" style="background-image:url(<?php echo esc_url( $media ); ?>);"></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </article>

<?php /* New blog layout */ else : ?>

    <?php /* Get media */
        if( get_post_format() == 'video' || get_post_format() == 'audio' ) :
            $media = maskitto_light_get_media( get_the_ID() );
        else :
            $media = '';
        endif;
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-layout-2' ); ?>>
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="blog-media">
                <?php echo the_post_thumbnail(); ?>

                <?php if( count($category) && $category_name ) : ?>
                    <a href="<?php echo $category_link; ?>" class="blog-category"><?php echo $category_name; ?></a>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <?php if( $media ) : ?>
                <div class="post-inner-media">
                    <?php echo $media; ?>
                </div>
            <?php endif; ?> 
            <?php if( count($category) && $category_name ) : ?>
                <a href="<?php echo $category_link; ?>" class="blog-category blog-category-left"><?php echo $category_name; ?></a>
            <?php endif; ?>
        <?php endif; ?>


        <div class="blog-top">
            <span class="blog-title" style="font-size: 21px;">
                <?php the_title(); ?>
                <?php echo maskitto_light_admin_edit(get_the_ID()); ?>
            </span>
        
            <div class="row">
                <div class="col-md-6 col-sm-6 blog-details" style="padding-left: 0;">
                    <span style="padding-right: 6px;"><?php echo get_the_date(); ?></span>
                    <span class="post-author-name"><?php _e( 'By', 'maskitto-light' ); ?> <?php echo get_the_author(); ?></span>
                </div>
                <div class="col-md-6 col-sm-6 text-right blog-comments-count-single" style="padding-right: 0;">
                    <a href="<?php comments_link(); ?>"><i class="fa fa-comment-o" style="padding-right: 5px;"></i><?php echo $comments_count; ?></a>
                </div>
            </div>
        </div>


        <div class="blog-bottom" style="padding-bottom: 25px;">
            <div class="blog-content" style="font-size: 14px; padding-top: 30px; margin-bottom: 0;">
                <div class="post-inner"><?php echo str_replace( $media, '', apply_filters( 'the_content', get_the_content() ) ); ?></div>
            </div>

            <?php
                $posttags = get_the_tags();
                if( $posttags && count( $posttags ) > 0 ) : ?>
                <div class="post-tags group">
                    <div class="post-tag-name"><?php _e( 'Tags', 'maskitto-light' ); ?>:</div>
                    <?php foreach($posttags as $tag) : ?>
                        <a href="<?php echo get_tag_link($tag->term_id); ?>" class="post-tag"><?php echo $tag->name . ' '; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        

    </article>

<?php endif; ?>
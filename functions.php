<?php

/**
 * Initialize theme core
 */

require get_template_directory() . '/inc/initialize.php';


/**
 * http://codex.wordpress.org/Content_Width
 */

if ( ! isset($content_width)) {
    $content_width = 980;
}

function my_myme_types($mime_types){
    $mime_types['epub'] = 'application/zip'; //Adding epub extension
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);
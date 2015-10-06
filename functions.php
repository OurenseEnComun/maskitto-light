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
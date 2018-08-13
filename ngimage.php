<?php
/*
Plugin Name: Ngimage
Plugin URI: http://tingplenting.github.io
description: Show image library bellow the_content()
Version: 1.2
Author: Mr. Tingplenting
Author URI: http://tingplenting.github.io
License: GPL2
*/

/**
 * Ambil semua gambar yang ada di post id
 * $post data post yang digunakan
 * $att_array data attachment post
 * $img_src url image
 */
function the_ngimage($class = '') {

	global $post;

	$att_array = array(
		'post_parent' => $post->ID,
		'post_type' => 'attachment',
		'numberposts' => -1,
		'post_mime_type' => 'image',
		'order' => 'ASC',
		'orderby' => 'ID',
	);
 
	$attachments = get_children($att_array);
 	$att_gallery = '';

	if (is_array($attachments)) {

		foreach($attachments as $att){

			if ( $att->menu_order == 0){
				$image_src_thumbnail = wp_get_attachment_image_src($att->ID, 'thumbnail'); 
				$image_src_medium = wp_get_attachment_image_src($att->ID, 'medium'); 

				$att_link = get_attachment_link($att->ID);
				$img_src = $image_src_thumbnail[0];
				$caption = htmlspecialchars($att->post_title);
 
				$post_link = get_permalink($post->ID);
				$image_medium_url = $image_src_medium[0];

				if (is_single()) {
					$medium_html = '<p class="caption"><a href="' . $att_link . '"><img src="' . $image_medium_url . '" alt="' . $caption . '" class="' . $class . '" /></a></p> ';
					$images_html .= '<div class="ng_gallery"><a href="' . $att_link . '"><img src="' . $img_src. '" alt="' . $caption . '" class="' . $class . '" /></a></div> ';
         		} else {
 					$medium_html = '<p class="caption"><a href="' . $post_link . '"><img src="' . $image_medium_url . '" alt="' . $caption . '" class="' . $class . '" /></a></p> ';
					$images_html .= '<div class="ng_gallery"><a href="' . $post_link . '"><img src="' . $img_src . '" alt="' . $caption . '" class="' . $class . '" /></a></div> ';       			
         		}
			}
		}
	}
	return [$images_html, $medium_html];
 
}

/**
 * Wrapper ngimage function
 */
function the_ngimage_gallery() {
	$ngimage_medium = '<div class="ngimage_medium">' . the_ngimage()[1] . '</div>';
	$ngimage_gallery = '<div class="ngimage_gallery">' . the_ngimage('ngimage_img')[0] . '</div><div class="clearboth"></div>';

	return $ngimage_medium . $ngimage_gallery;
}

add_action( 'the_content', 'the_ngimage_gallery', 5 );

/**
 * Register style sheet.
 * plugins_url url plugin
 */
function register_plugin_styles() {
	wp_register_style( 'ngimage-style', plugins_url( '/ngimage.css', __FILE__ ) );
	wp_enqueue_style( 'ngimage-style' );
}

add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

?>

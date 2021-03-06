<?php
/**
 * Functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Insert Default Terms
 * @return [type] [description]
 */


/**
 * set_featured_image_from_url()
 * Set Featured Image From URl
 * @author Alexander Zizzo
 * @since 1.3
 */
function ffw_media_set_featured_image_from_url( $img_url = NULL, $curr_post_id = NULL, $debug = true )
{

  // Needed global vars
  global $post;
  
  // Add Featured Image to Post
  $image_url                = $img_url;                               // Define the image URL here
  $upload_dir               = wp_upload_dir();                        // Set upload folder
  $image_data               = file_get_contents($image_url);          // Get image data
  $filename                 = basename($image_url);                   // Create image file name
  $the_post_id              = $curr_post_id;                          // Alt var for $post->ID
  $attach_id_orig           = get_post_thumbnail_id( $the_post_id );  // Get the attachment ID
  $attachment_metadata      = get_post_meta( $attach_id_orig, '_wp_attachment_metadata', $single = false );
  $attachment_metadata_file = substr( $attachment_metadata[0]['file'], 8 );

  // Check folder permission and define file location
  if( wp_mkdir_p( $upload_dir['path'] ) ) {
      $file = $upload_dir['path'] . '/' . ($the_post_id . '-' .$filename);
  } else {
      $file = $upload_dir['basedir'] . '/' . ($the_post_id . '-' .$filename);
  }

    // if ( !has_post_thumbnail( $the_post_id ) ) {
    if ( !has_post_thumbnail( $post->ID ) && ( $attachment_metadata_file != $filename ) ) {

        // Delete the attachment metadata (redundancy measure)
        delete_post_thumbnail( $the_post_id );
        wp_delete_attachment( $attach_id_orig );

        // Create the image file on the server
        file_put_contents( $file, $image_data );

        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );

        // Set attachment data
        $attachment = array(
            'guid'           => $upload_dir['url'] . '/' . basename( $filename ), 
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => $the_post_id . '-' . sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Create the attachment
        $attach_id = wp_insert_attachment( $attachment, $file, $the_post_id );

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id, $attach_data );

        // And finally assign featured image to post
        set_post_thumbnail( $the_post_id, $attach_id );

    } 
    // elseif( has_post_thumbnail( $the_post_id ) ) {
    //     // Delete the post attachment
    //     delete_post_thumbnail( $the_post_id );
    //     wp_delete_attachment( $the_post_id );
    // }
}


/**
 * Get the slud to return on the front end for themes
 * @return [type] [description]
 */
function ffw_get_media_slug()
{
  global $ffw_media_settings;

  $ffw_media_slug = defined( 'FFW_MEDIA_SLUG' ) ? FFW_MEDIA_SLUG : $ffw_media_settings['media_slug'];

  return $ffw_media_slug;
}





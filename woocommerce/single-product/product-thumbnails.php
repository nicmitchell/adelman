<?php
/**
 * Single Product Thumbnails
 *
 * Why we need this override:
 * Grid needs to be 6 instead of 3:
  ```
   function getGridSize() {
      return (window.innerWidth < 600) ? 3 :
        (window.innerWidth < 1200) ? 6 : 6; // Needs to be 6 instead of 3
   }
   ```
 *
 *
 * Would be better to solve this with JS

 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version     3.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_image_ids();

$zoom = etheme_get_option('zoom_effect');

$has_video = false;

$video_attachments = array();
$videos = et_get_attach_video( $product->get_id() ); 

if(isset($videos[0]) && $videos[0] != '') {
  $video_attachments = get_posts( array(
    'post_type' => 'attachment',
    'include' => $videos[0]
  ) ); 
}

if(count($video_attachments)>0 || et_get_external_video( $product->get_id() ) != '') {
  $has_video = true;
}


if ( (has_post_thumbnail() && ( $has_video || $attachment_ids)) || ( $has_video && $attachment_ids) ) {
  ?>
  <div class="thumbnails"><?php

    $loop = 0;
    $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

    $image_size = apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' );

    $image = get_the_post_thumbnail_url( $post->ID, 'shop_catalog' );

    
  ?>
  
  <div class="product-thumbnails-slider">
      
    <ul class="slides">
      <?php if(has_post_thumbnail() ): ?>
        <li><a href="#" class="main-image"><img src="<?php echo $image; ?>"></a></li>
        <?php if ($attachment_ids): ?>
          <?php foreach ($attachment_ids as $key => $value): ?>
            <?php 

            $full_size_image = wp_get_attachment_image_src( $value, 'full' );
            $attributes      = array(
            'title'                   => get_post_field( 'post_title', $value ),
            'data-caption'            => get_post_field( 'post_excerpt', $value ),
            'data-src'                => $full_size_image[0],
            'data-large_image'        => $full_size_image[0],
            'data-large_image_width'  => $full_size_image[1],
            'data-large_image_height' => $full_size_image[2],
            );


             ?>
            <?php $value = wp_get_attachment_image_url( $value, $image_size, false, $attributes ); ?>
            <li><a href="#" class="main-image"><img src="<?php echo $value; ?>" ></a></li>
          <?php endforeach ?>
        <?php endif ?>
      <?php endif; ?>
      
      <?php if(et_get_external_video( $product->get_id() )): ?>
        <li class="video-thumbnail">
          <span><?php _e('Video', 'legenda'); ?></span>
        </li>
      <?php endif; ?>
      
      <?php if(count($video_attachments)>0): ?>
        <li class="video-thumbnail">
          <span><?php _e('Video', 'legenda'); ?></span>
        </li>
      <?php endif; ?>
      
      
      <?php if(!has_post_thumbnail() ): ?>
        <?php if ($attachment_ids): ?>
          <?php foreach ($attachment_ids as $key => $value): ?>
            <?php 
            
            $full_size_image = wp_get_attachment_image_src( $value, 'full' );
            $attributes      = array(
            'title'                   => get_post_field( 'post_title', $value ),
            'data-caption'            => get_post_field( 'post_excerpt', $value ),
            'data-src'                => $full_size_image[0],
            'data-large_image'        => $full_size_image[0],
            'data-large_image_width'  => $full_size_image[1],
            'data-large_image_height' => $full_size_image[2],
            );


             ?>
            <?php $value = wp_get_attachment_image_url( $value, $image_size, false, $attributes ); ?>
            <li><a href="#" class="main-image"><img src="<?php echo $value; ?>" ></a></li>
          <?php endforeach ?>   
        <?php endif ?>        
      <?php endif ?>
    </ul>
  </div>
      
  <script type="text/javascript">
  "use strict";

    jQuery(document).ready(function($) {
      
      var $window = jQuery(window),
          flexslider = { vars:{} };
     
      function getGridSize() {
        return (window.innerWidth < 600) ? 3 :
               (window.innerWidth < 1200) ? 3 : 3;
      }
         
      jQuery('.product-thumbnails-slider').flexslider({
            animation: "slide",
            slideshow: false,
            animationLoop: false,
            controlNav: true,
            directionNav:true,
            itemWidth:120,
            itemMargin:30,
            minItems: getGridSize(),
            maxItems: getGridSize(),
            asNavFor: '.main-image-slider'
          });
     
      $window.resize(function() {

        var gridSize = getGridSize();
     
        flexslider.vars.minItems = gridSize;
        flexslider.vars.maxItems = gridSize;
      });

    });
  </script>
</div>
  <?php
}
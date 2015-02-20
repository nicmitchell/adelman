<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$providers = get_field('provider');
  if( $providers ):
    foreach( $providers as $provider ): 
      $providerId = $provider->ID;
      $provider_type = get_post_type($providerId);
    endforeach;
  endif;

// $related = $product->get_related(10);

// if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 	    => 30,
	'orderby' 				=> $orderby,
	// 'post__in' 				=> $related,
	'post__not_in'			=> array($product->id),
  'post_status'      => 'publish',
  'meta_query' => array(
    array(
      'key' => 'provider', // name of custom field
      'value' => '"' . $providerId . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
      'compare' => 'LIKE'
    )
  )
) );

$slider_args = array(
	'title' =>__('More From This '. ucfirst($provider_type), ETHEME_DOMAIN)
);

etheme_create_slider($args, $slider_args);

wp_reset_postdata();

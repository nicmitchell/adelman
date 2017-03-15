<?php
/**
 * Related Products
 *
 * @author    WooThemes
 * @package   WooCommerce/Templates
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

$args = apply_filters('woocommerce_related_products_args', array(
  'post_type' => 'product',
  'post_status' => 'publish',
  'ignore_sticky_posts' => 1,
  'no_found_rows' => 1,
  'posts_per_page' => 30,
  'post__not_in' => array($product->id),
  'meta_query' => array(
    array(
      'relation' => 'AND',
      'sold_clause' => array(
        'key' => '_stock_status'
      ),
      'new_clause' => array(
        'key' => 'product_new'
      ),
      'provider_clause' => array(
        'key' => 'provider',
        'value' => '"' . $providerId . '"',
        'compare' => 'LIKE',  
      )
    )
  ),
  'orderby' => array(
    'sold_clause' => 'ASC',
    'new_clause' => 'DESC'
  )
));

$slider_args = array(
  'title' =>__('More From This '. ucfirst($provider_type), ETHEME_DOMAIN),
  'items' => '[[0, 1], [479,2], [619,2], [768,4], [1200, 6], [1600, 6]]',
  'style' => 'default'
);

etheme_create_slider($args, $slider_args);

wp_reset_postdata();

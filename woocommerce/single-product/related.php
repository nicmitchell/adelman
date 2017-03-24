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
$product = get_the_id();

if( $providers ):
  foreach( $providers as $provider ): 
    $provider = $provider->ID;
    $provider_type = get_post_type($provider);
  endforeach;
endif;

// get the query vars from functions.php
$args = apply_filters('woocommerce_related_products_args', adelman_get_slider_args_for_provider($provider, $product, 30));

$slider_args = array(
  'title' =>__('More From This '. ucfirst($provider_type), ETHEME_DOMAIN),
  'items' => '[[0, 1], [479,2], [619,2], [768,4], [1200, 6], [1600, 6]]',
  'style' => 'default'
);

etheme_create_slider($args, $slider_args);

wp_reset_postdata();

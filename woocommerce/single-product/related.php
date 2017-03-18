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

$args = adelman_get_slider_args_for_provider($providerId, $product->id, 30);
$slider_args = array(
  'title' =>__('More From This '. ucfirst($provider_type), ETHEME_DOMAIN),
  'items' => '[[0, 1], [479,2], [619,2], [768,4], [1200, 6], [1600, 6]]',
  'style' => 'default'
);

etheme_create_slider($args, $slider_args);

wp_reset_postdata();

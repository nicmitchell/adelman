<?php

// **********************************************************************// 
// ! Enqueue Files and Styles
// **********************************************************************// 

// Enqueue the parent styles
function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// admin.js for products 
// change menu order based on New status
function product_admin_script() {
  global $post_type;
  if( $post_type === 'product' )
  wp_enqueue_script( 'product-admin-script', get_stylesheet_directory_uri() . '/js/admin.js', array('jquery'), '1.2' );
}
add_action( 'admin_print_scripts-post-new.php', 'product_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'product_admin_script', 11 );

// Front end custom JS
function adelman_script() {
  wp_register_script('adelman-script', get_stylesheet_directory_uri() . '/js/adelman.js', array('jquery'),'1.14', true);
  wp_enqueue_script('adelman-script');
}
add_action( 'wp_enqueue_scripts', 'adelman_script' ); 

// Admin stylesheet
function adelman_admin_theme_style() {
  wp_enqueue_style('adelman-admin-style', get_stylesheet_directory_uri() . '/admin.css');
}
add_action('admin_enqueue_scripts', 'adelman_admin_theme_style');

// Hide SEO columns
add_filter( 'wpseo_use_page_analysis', '__return_false' );

// Fix font-awesome enqueuing in Legenda
function adelman_font_awesome() {
  wp_dequeue_style('et-font-awesome');
  wp_deregister_style('et-font-awesome');
  wp_enqueue_style('et-font-awesome', get_template_directory_uri().'/css/font-awesome.css', array());
}
add_action( 'wp_enqueue_scripts', 'adelman_font_awesome' );


// **********************************************************************// 
// ! CPTs for Artists and Jewelers
// **********************************************************************// 
add_action('init', 'cptui_register_my_cpt_artist');
  function cptui_register_my_cpt_artist() {
    register_post_type('artist', 
      array(
        'label' => 'Artists',
        'description' => '',
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        'rewrite' => array('slug' => 'artist', 'with_front' => true),
        'query_var' => true,
        'has_archive' => true,
        'supports' => array('title','editor','thumbnail'),
        'taxonomies' => array('artists'),
        'labels' => array (
          'name' => 'Artists',
          'singular_name' => 'Artist',
          'menu_name' => 'Artists',
          'add_new' => 'Add Artist',
          'add_new_item' => 'Add New Artist',
          'edit' => 'Edit',
          'edit_item' => 'Edit Artist',
          'new_item' => 'New Artist',
          'view' => 'View Artist',
          'view_item' => 'View Artist',
          'search_items' => 'Search Artists',
          'not_found' => 'No Artists Found',
          'not_found_in_trash' => 'No Artists Found in Trash',
          'parent' => 'Parent Artist',
        )
      ) 
  ); 
}

add_image_size( 'adelman_artist_image', 370, 370, true);

add_action('init', 'cptui_register_my_cpt_jeweler');
  function cptui_register_my_cpt_jeweler() {
    register_post_type('jeweler', array(
      'label' => 'Jewelers',
      'description' => '',
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'capability_type' => 'post',
      'map_meta_cap' => true,
      'hierarchical' => false,
      'rewrite' => array('slug' => 'jeweler', 'with_front' => true),
      'query_var' => true,
      'supports' => array('title','editor','thumbnail'),
      'labels' => array (
        'name' => 'Jewelers',
        'singular_name' => 'Jeweler',
        'menu_name' => 'Jewelers',
        'add_new' => 'Add Jeweler',
        'add_new_item' => 'Add New Jeweler',
        'edit' => 'Edit',
        'edit_item' => 'Edit Jeweler',
        'new_item' => 'New Jeweler',
        'view' => 'View Jeweler',
        'view_item' => 'View Jeweler',
        'search_items' => 'Search Jewelers',
        'not_found' => 'No Jewelers Found',
        'not_found_in_trash' => 'No Jewelers Found in Trash',
        'parent' => 'Parent Jeweler',
      )
    ) 
  ); 
}


// **********************************************************************// 
// ! Set the number of items on 'Artist' archive page to show all
// **********************************************************************// 
function adelman_artists_per_page( $query ) {
  if( $query->is_main_query() && $query->is_post_type_archive('artist') ) {
    $query->set( 'posts_per_page', -1 );
  }
}
add_filter( 'pre_get_posts', 'adelman_artists_per_page' );


// **********************************************************************// 
// ! Get provider for Product
// **********************************************************************// 
function adelman_product_artist() {
  $providers = get_field('provider'); ?>
  <?php if( $providers ): ?>
    <?php foreach( $providers as $provider ): ?>
      Artist: <a href="<?php echo get_permalink( $provider->ID ); ?>">
      <?php echo get_the_title( $provider->ID ); ?>
      </a>
    <?php endforeach; ?>
  <?php endif; 
}


// **********************************************************************// 
// ! Get single product page sidebar position
// **********************************************************************// 
if(!function_exists('etheme_get_single_product_sidebar')) {
  function etheme_get_single_product_sidebar() {

    $orientation = get_field('orientation');

    $result = array(
      'position' => 'left',
      'responsive' => '',
      'images_span' => '5',
      'meta_span' => '4'
    );
    
    $result['single_product_sidebar'] = is_active_sidebar('single-sidebar');
    $result['responsive'] = etheme_get_option('blog_sidebar_responsive');         
    $result['position'] = etheme_get_option('single_sidebar');

    $result['single_product_sidebar'] = apply_filters('single_product_sidebar', $result['single_product_sidebar']);
    
    if(!$result['single_product_sidebar'] || $result['position'] == 'no_sidebar') {
      if($orientation === 'landscape') {
        $result['position'] = 'without';
        $result['images_span'] = '9';
        $result['meta_span'] = '3';
      }
      else {
        $result['position'] = 'without';
        $result['images_span'] = '8';
        $result['meta_span'] = '4';
      }
    }
    return $result;
  }
}

// **********************************************************************// 
// ! Create products slider by args
// **********************************************************************//

function etheme_create_slider($args, $slider_args = array()){
//, $title = false, $shop_link = true, $slider_type = false, $items = '[[0, 1], [479,2], [619,2], [768,4],  [1200, 4], [1600, 4]]', $style = 'default'
  global $wpdb, $woocommerce_loop;

  $product_per_row = etheme_get_option('prodcuts_per_row');
  extract(shortcode_atts(array( 
    'title' => false,
    'shop_link' => false,
    'slider_type' => false,
    'items' => '[[0, 1], [479,2], [619,2], [768,4],  [1200, 6], [1600, 6]]',
    'style' => 'default',
    'block_id' => false
  ), $slider_args));

    $box_id = rand(1000,10000);
    $multislides = new WP_Query( $args );
    $shop_url = get_permalink(wc_get_page_id('shop'));
    $class = $title_output = '';
    if(!$slider_type) {
      $woocommerce_loop['lazy-load'] = true;
      $woocommerce_loop['style'] = $style;
    }
    
    if($multislides->post_count > 1) {
        $class .= ' posts-count-gt1';
    }
    if($multislides->post_count < 4) {
        $class .= ' posts-count-lt4';
    }
    if ( $multislides->have_posts() ) :
        if ($title) {
            $title_output = '<h2 class="title"><span>'.$title.'</span></h2>';
        }   
          echo '<div class="slider-container '.$class.'">';
              echo $title_output;
              if($shop_link && $title)
                echo '<a href="'.$shop_url.'" class="show-all-posts hidden-tablet hidden-phone">'.__('View more products', ETHEME_DOMAIN).'</a>';
              echo '<div class="items-slider products-slider '.$slider_type.'-container slider-'.$box_id.'">';
                    echo '<div class="slider '.$slider_type.'-wrapper">';
                    $_i=0;
                      if($block_id && $block_id != '' && et_get_block($block_id) != '') {
                          echo '<div class=" '.$slider_type.'-slide">';
                              echo et_get_block($block_id);
                          echo '</div><!-- slide-item -->';
                      }
                        while ($multislides->have_posts()) : $multislides->the_post();
                            $_i++;
                            
                            if(class_exists('Woocommerce')) {
                                global $product;
                                if (!$product->is_visible()) continue; 
                                echo '<div class="slide-item product-slide '.$slider_type.'-slide">';
                                    wc_get_template_part( 'content', 'product' );
                                echo '</div><!-- slide-item -->';
                            }

                        endwhile; 
                    echo '</div><!-- slider -->'; 
              echo '</div><!-- products-slider -->'; 
          echo '</div><!-- slider-container -->'; 
    endif;
    wp_reset_query();
    unset($woocommerce_loop['lazy-load']);
    unset($woocommerce_loop['style']);
    
    if(!$slider_type) {
      echo '

          <script type="text/javascript">
              jQuery(".slider-'.$box_id.' .slider").owlCarousel({
                  items: 4, 
                  lazyLoad : true,
                  navigation: true,
                  navigationText:false,
                  rewindNav: false,
                  itemsCustom: '.$items.'
              });

          </script>
      ';
    } elseif($slider_type == 'swiper') {
      echo '

          <script type="text/javascript">
            if(jQuery(window).width() > 767) {
              jQuery(".slider-'.$box_id.'").etFullWidth();
        var mySwiper'.$box_id.' = new Swiper(".slider-'.$box_id.'",{
        keyboardControl: true,
        centeredSlides: true,
        calculateHeight : true,
        slidesPerView: "auto"
        })
            } else {
        var mySwiper'.$box_id.' = new Swiper(".slider-'.$box_id.'",{
        calculateHeight : true
        })
            }

        jQuery(function($){
        $(".slider-'.$box_id.' .slide-item").click(function(){
          mySwiper'.$box_id.'.swipeTo($(this).index());
          $(".lookbook-index").removeClass("active");
          $(this).addClass("active");
        });
        
        $(".slider-'.$box_id.' .slide-item a").click(function(e){
          if($(this).parents(".swiper-slide-active").length < 1) {
            e.preventDefault();
          }
        });
        }, jQuery);
          </script>
      ';
    }
        
}



// **********************************************************************// 
// ! Filter products by type in Products admin
// **********************************************************************// 

function adelman_filter_products_by_featured_status() {

  global $typenow, $wp_query;

    if ($typenow=='product') :

      // Featured/ Not Featured
      $output .= "<select name='featured_status' id='dropdown_featured_status'>";
      $output .= '<option value="">'.__( 'Show All Featured Statuses', 'woocommerce' ).'</option>';

      $output .="<option value='featured' ";
      if ( isset( $_GET['featured_status'] ) ) $output .= selected('featured', $_GET['featured_status'], false);
      $output .=">".__( 'Featured', 'woocommerce' )."</option>";

      $output .="<option value='normal' ";
      if ( isset( $_GET['featured_status'] ) ) $output .= selected('normal', $_GET['featured_status'], false);
      $output .=">".__( 'Not Featured', 'woocommerce' )."</option>";

      $output .="</select>";

      echo $output;
    endif;
}
add_action('restrict_manage_posts', 'adelman_filter_products_by_featured_status');


// Filter the products in admin based on options
function adelman_featured_products_admin_filter_query( $query ) {
  global $typenow, $wp_query;

  if ( $typenow == 'product' ) {
    // Subtypes
    if ( ! empty( $_GET['featured_status'] ) ) {
      if ( $_GET['featured_status'] == 'featured' ) {
        $query->query_vars['meta_value']    = 'yes';
        $query->query_vars['meta_key']      = '_featured';
      } elseif ( $_GET['featured_status'] == 'normal' ) {
        $query->query_vars['meta_value']    = 'no';
        $query->query_vars['meta_key']      = '_featured';
      }
    }
  }
}
add_filter( 'parse_query', 'adelman_featured_products_admin_filter_query' );

// **********************************************************************// 
// ! Sold Out Products Order Shortcode (Home Page)
// **********************************************************************// 
/**
 * shortcode function.
 *
 * @access public
 * @return string
 */
function sold_out_products_shortcode( $atts ) {
  global $woocommerce_loop, $woocommerce, $sold_out_shortcode_used;
  $sold_out_shortcode_used = true;

  extract( shortcode_atts( array(
    'per_page'  => '12',
    'columns'   => '4',
    'orderby' => 'meta_value_num',
    'order' => 'desc'
  ), $atts) );

  $meta_query = array();
  $meta_query[] = array(
    'key'     => '_visibility',
    'value'   => array( 'visible', 'catalog' ),
    'compare' => 'IN'
  );
  $meta_query[] = array(
    'key'     => '_stock_status',
    'value'   => 'outofstock',
    'compare'   => '='
  );

  $args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page' => $per_page,
    'orderby' => $orderby,
    'order' => $order,
    'meta_key' => 'sold_date',
    'meta_query' => $meta_query
  );

  ob_start();

  $products = new WP_Query( $args );

  $woocommerce_loop['columns'] = $columns;

  if ( $products->have_posts() ) : ?>

    <?php woocommerce_product_loop_start(); ?>

      <?php while ( $products->have_posts() ) : $products->the_post(); ?>

        <?php wc_get_template_part( 'content', 'product' ); ?>

      <?php endwhile; // end of the loop. ?>

    <?php woocommerce_product_loop_end(); ?>

  <?php endif;

  wp_reset_postdata();

  return '<div class="woocommerce">' . ob_get_clean() . '</div>';
}

add_shortcode( 'sold_out_products', 'sold_out_products_shortcode' );

// **********************************************************************// 
// ! Sort Products Order
// **********************************************************************// 
// Order product collections by stock status, in-stock products first.
// http://stackoverflow.com/questions/25113581/show-out-of-stock-products-at-the-end-in-woocommerce
// Pre-get posts does not seem to work for related.php, because the Query object has already been created.

class iWC_Orderby_Stock_Status {
  public function __construct(){
    // Check if WooCommerce is active
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      add_filter('posts_clauses', array($this, 'order_by_stock_status'), 2000);
    }
  }

  public function order_by_stock_status($posts_clauses) {
    global $wpdb, $post;
    // If the clause is querying for post_type = 'product', sort the solds to the back
    $where_clause = str_replace(' ', '', $posts_clauses['where']);
    if(preg_match("/_posts\.post_type='product'/", $where_clause)) {
      $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
      $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
      $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
    }
    return $posts_clauses;
  }
}
new iWC_Orderby_Stock_Status;


// **********************************************************************//
// ! Abstract Related Products Params
// **********************************************************************//

function adelman_get_slider_args_for_provider($provider_id, $product_id = -1, $num_items = -1) {
  return apply_filters('woocommerce_related_products_args', array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1,
    'no_found_rows' => 1,
    'posts_per_page' => $num_items,
    'post__not_in' => array($product_id),
    'meta_query' => array(
      'provider_clause' => array(
        'key' => 'provider',
        'value' => '"' . $provider_id . '"',
        'compare' => 'LIKE',  
      )
    )
  ));
}


// **********************************************************************// 
// ! Change 'Out of stock' strings
// **********************************************************************// 

function adelman_change_text_strings( $translated_text, $text, $domain ) {
  switch ( $translated_text ) {
    case 'Out of stock' :
      $translated_text = __( 'Sold', 'ETHEME_DOMAIN' );
      break;
  }
  return $translated_text;
}
add_filter( 'gettext', 'adelman_change_text_strings', 20, 3 );


// **********************************************************************// 
// ! Product Labels
// **********************************************************************// 

function etheme_wc_product_labels( $product_id = '' ) { 
    echo etheme_wc_get_product_labels($product_id);
}

function etheme_wc_get_product_labels( $product_id = '' ) {
  global $post, $wpdb,$product;
    $count_labels = 0; 
    $output = '';

    if ( etheme_get_option('sale_icon') ) : 
      if ($product->is_on_sale()) {$count_labels++; 
        $output .= '<span class="label-icon sale-label">'.__( 'Sale!', ETHEME_DOMAIN ).'</span>';
      }
    endif; 

    if ( etheme_get_option('new_icon') ) : $count_labels++;
      if(etheme_product_is_new($product_id)) :
        $second_label = ($count_labels > 1) ? 'second_label' : '';
        $output .= '<span class="label-icon new-label '.$second_label.'">'.__( 'New!', ETHEME_DOMAIN ).'</span>';
      endif;
    endif;

    if ( !$product->is_in_stock() && etheme_get_option('out_of_label')):
      // will not show other labels if is sold
      $output = '<span class="label-icon out-of-stock">'. __('Sold', ETHEME_DOMAIN) .'</span>';
    endif;

    return $output;
}

// **********************************************************************// 
// ! Is product New
// **********************************************************************// 

function etheme_product_is_new() {
  // Overrides Legenda function
  // Check if $var has been within the last month. (sec * min * hours * days)
  // Days is set in Theme Options
  $days = etheme_get_option('new_icon_timeout');
  if((time()-(60*60*24*$days)) < strtotime(get_the_date())) {
    return true;
  }
  return false;
}

// Adds the option to change this value in Theme Functions
function adelman_add_new_icon_timeout() {
  if ( is_admin() && function_exists( 'ot_register_settings' ) ) {
    $saved_settings = get_option('option_tree_settings');
    $custom_settings = $saved_settings;
    $settings = $saved_settings['settings'];

    $new_icon_timeout = array(
      'id'          => 'new_icon_timeout',
      'label'       => __('"NEW" label number of days', ETHEME_DOMAIN),
      'desc'        => __('<b>Example: </b> 60', ETHEME_DOMAIN),
      'type'        => 'text',
      'section'     => 'shop',
      'default'     => 60
    );

    // Insert the New timeout to appear after the enable switch
    for ($i = 0; $i < count($settings); $i++){
      if ($settings[$i]['id'] == 'new_icon') {
        array_splice($settings, $i + 1, 0, array($new_icon_timeout));
      }
    }

    $custom_settings['settings'] = $settings;

    if(is_array($settings)){
      foreach($settings as $key => $value){
        $defaults[$value['id']] = $value['default'];
      }
    }

    add_option( 'option_tree', $defaults ); // update_option  add_option

    /* settings are not the same update the DB */
    if ( $saved_settings !== $custom_settings ) {
      update_option( 'option_tree_settings', $custom_settings );
    }
  }
}
add_action( 'init', 'adelman_add_new_icon_timeout' );


// **********************************************************************// 
// ! WooCommerce Hooks
// **********************************************************************// 

function adelman_single_product_summary_hook() {
  global $product;
  ?>
  <span class="artist_name_wrapper"><?php adelman_product_artist(); ?></span>
  
  <?php $version_short = $product->get_attribute( 'pa_version-short' ); ?>
  <?php if($version_short): ?>
    <span class="short_description_meta">Version: <?php echo $version_short; ?></span>
  <?php endif; ?>

  <?php $dimensions = $product->get_dimensions(false); ?>
  <?php if ($dimensions): ?>
    <span class="short_description_meta">Dimensions: <?php echo wc_format_dimensions($dimensions); ?></span>
  <?php endif; ?>

  <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="short_description_meta">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' );
}

add_action( 'woocommerce_single_product_summary', 'adelman_single_product_summary_hook', 5);


// **********************************************************************// 
// ! Auction Stuff (Cron jobs)
// **********************************************************************//

// Every day
function adelman_auction_cron_mail(){
  adelman_ping_url( get_site_url(null, '/?auction-cron=mails', 'https') );
}
add_action( 'adelman_auction_cron_mail', 'adelman_auction_cron_mail' );

// Every minute
function adelman_auction_cron_check(){
  adelman_ping_url( get_site_url(null, '/?auction-cron=check', 'https') );
}
add_action( 'adelman_auction_cron_check', 'adelman_auction_cron_check' );

// Every hour
function adelman_auction_cron_relist(){
  adelman_ping_url( get_site_url(null, '/?auction-cron=relist', 'https') );
}
add_action( 'adelman_auction_cron_relist', 'adelman_auction_cron_relist' );


function adelman_ping_url($url = NULL) {
  if($url == NULL) {
    return false;
  }
  
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $data = curl_exec($ch);
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch); 
  
  if( $httpcode >= 200 && $httpcode < 300) {
    return true;
  } else {
    return false;
  }
}

if ( !function_exists('write_log') ) {
  function write_log ( $log )  {
    if ( is_array( $log ) || is_object( $log ) ) {
      error_log( print_r( $log, true ) );
    } else {
      error_log( $log );
    }
  }
}



// **********************************************************************// 
// ! ACF Fields
// **********************************************************************// 
if(function_exists("register_field_group"))
{
  register_field_group(array (
    'id' => 'acf_jeweler-categories',
    'title' => 'Jeweler Categories',
    'fields' => array (
      array (
        'key' => 'field_54ea44a4a23d4',
        'label' => 'Womens',
        'name' => '',
        'type' => 'tab',
      ),
      array (
        'key' => 'field_54ea4582a23dc',
        'label' => 'Anklets',
        'name' => 'anklets_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea4458a23d3',
        'label' => 'Bangles',
        'name' => 'bangles_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea400f445de',
        'label' => 'Bracelets',
        'name' => 'bracelets_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea4447a23d2',
        'label' => 'Cuffs',
        'name' => 'cuffs_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea43cfa23cf',
        'label' => 'Earrings',
        'name' => 'earrings_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea43eda23d0',
        'label' => 'Necklaces',
        'name' => 'necklaces_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea4435a23d1',
        'label' => 'Rings',
        'name' => 'rings_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea455ca23db',
        'label' => 'Watches',
        'name' => 'watches_womens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea44bfa23d5',
        'label' => 'Mens',
        'name' => '',
        'type' => 'tab',
      ),
      array (
        'key' => 'field_54ea453aa23da',
        'label' => 'Bracelets',
        'name' => 'bracelets_mens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea44cba23d6',
        'label' => 'Cufflinks',
        'name' => 'cufflinks_mens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea4520a23d9',
        'label' => 'Necklaces',
        'name' => 'necklaces_mens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea44d9a23d7',
        'label' => 'Rings',
        'name' => 'rings_mens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
      array (
        'key' => 'field_54ea44e4a23d8',
        'label' => 'Watches',
        'name' => 'watches_mens',
        'type' => 'image',
        'save_format' => 'object',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'jeweler',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
  register_field_group(array (
    'id' => 'acf_artist-linked-product',
    'title' => 'Artist Linked Product',
    'fields' => array (
      array (
        'key' => 'field_543f2a6d5c1c2',
        'label' => 'Linked Product',
        'name' => 'linked_product',
        'type' => 'relationship',
        'instructions' => 'Select a product to link to from the Artists page',
        'return_format' => 'object',
        'post_type' => array (
          0 => 'product',
        ),
        'taxonomy' => array (
          0 => 'product_cat:631',
          1 => 'product_cat:632',
          2 => 'product_cat:33',
          3 => 'product_cat:34',
          4 => 'product_cat:629',
          5 => 'product_cat:620',
          6 => 'product_cat:627',
          7 => 'product_cat:30',
          8 => 'product_cat:863',
          9 => 'product_cat:358',
          10 => 'product_cat:367',
          11 => 'product_cat:621',
          12 => 'product_cat:27',
          13 => 'product_cat:624',
          14 => 'product_cat:920',
          15 => 'product_cat:355',
          16 => 'product_cat:596',
          17 => 'product_cat:957',
          18 => 'product_cat:31',
        ),
        'filters' => array (
          0 => 'search',
        ),
        'result_elements' => array (
          0 => 'featured_image',
          1 => 'post_title',
        ),
        'max' => 1,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'artist',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
  register_field_group(array (
    'id' => 'acf_artists-jewelers',
    'title' => 'Artists / Jewelers',
    'fields' => array (
      array (
        'key' => 'field_54419fc095e12',
        'label' => 'Orientation',
        'name' => 'orientation',
        'type' => 'radio',
        'choices' => array (
          'landscape' => 'Landscape',
          'portrait' => 'Portrait',
        ),
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => 'portrait',
        'layout' => 'horizontal',
      ),
      array (
        'key' => 'field_556be0a1bb87a',
        'label' => 'Sold Date',
        'name' => 'sold_date',
        'type' => 'date_picker',
        'date_format' => 'yymmdd',
        'display_format' => 'mm/dd/yy',
        'first_day' => 1,
      ),
      array (
        'key' => 'field_56495fb52a2b9',
        'label' => 'Reserved',
        'name' => 'reserved',
        'type' => 'true_false',
        'message' => '',
        'default_value' => 0,
      ),
      array (
        'key' => 'field_5439a64f0333e',
        'label' => 'Provider',
        'name' => 'provider',
        'type' => 'relationship',
        'return_format' => 'object',
        'post_type' => array (
          0 => 'artist',
          1 => 'jeweler',
        ),
        'taxonomy' => array (
          0 => 'all',
        ),
        'filters' => array (
          0 => 'search',
          1 => 'post_type',
        ),
        'result_elements' => array (
          0 => 'post_type',
          1 => 'post_title',
        ),
        'max' => 1,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'product',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
        0 => 'the_content',
        1 => 'custom_fields',
        2 => 'comments',
        3 => 'revisions',
        4 => 'slug',
        5 => 'author',
        6 => 'format',
        7 => 'tags',
        8 => 'send-trackbacks',
      ),
    ),
    'menu_order' => 0,
  ));
}
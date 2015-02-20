<?php

// **********************************************************************// 
// ! Enqueue Files and Styles
// **********************************************************************// 

// Enqueue the parent styles
function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
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
  wp_register_script('adelman-script', get_stylesheet_directory_uri() . '/js/adelman.js', array('jquery'),'1.2', true);
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

// **********************************************************************// 
// ! Include etheme CSS and JS
// **********************************************************************// 

// Included only to override the etheme.js file for "mega search"
if(!function_exists('etheme_enqueue_styles')) {
  function etheme_enqueue_styles() {
    global $etheme_responsive, $etheme_theme_data;
    $etheme_theme_data = wp_get_theme( 'legenda' );
    $custom_css = etheme_get_option('custom_css');
    if ( !is_admin() ) {
      wp_enqueue_style("style",get_stylesheet_directory_uri().'/style.css', array(), $etheme_theme_data->Version);
      wp_enqueue_style("font-lato",et_http()."fonts.googleapis.com/css?family=Lato:300,400,700,300italic");
      wp_enqueue_style("open-sans",et_http()."fonts.googleapis.com/css?family=Open+Sans:300,400,700,300italic");

      if($etheme_responsive){
        wp_enqueue_style("responsive",get_template_directory_uri().'/css/responsive.css', array(), $etheme_theme_data->Version);
      }

      if($custom_css) {
        wp_enqueue_style("custom",get_template_directory_uri().'/custom.css', array(), $etheme_theme_data->Version);  
      }

      $etheme_color_version = etheme_get_option('main_color_scheme');
      
      if($etheme_color_version=='dark') {
        wp_enqueue_style("dark",get_template_directory_uri().'/css/dark.css', array(), $etheme_theme_data->Version);  
      }

      $script_depends = array();

      if(class_exists('WooCommerce')) {
        $script_depends = array('wc-add-to-cart-variation');
      }
          
      wp_enqueue_script('head', get_template_directory_uri().'/js/head.js'); // modernizr, owl carousel, Swiper, FullWidth helper
      if(etheme_get_option('product_img_hover') == 'tooltip')
        wp_enqueue_script('tooltip', get_template_directory_uri().'/js/tooltip.js');
      wp_enqueue_script('jquery');
      wp_enqueue_script('all_plugins', get_template_directory_uri().'/js/plugins.min.js',$script_depends,false,true);
      wp_enqueue_script('waypoints');
      wp_enqueue_script('etheme', get_stylesheet_directory_uri().'/js/etheme.js',$script_depends,false,true);
      wp_localize_script( 'etheme', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'noresults' => __('No results were found!', ETHEME_DOMAIN)));
    }
  }
}

add_action( 'wp_enqueue_scripts', 'etheme_enqueue_styles', 30);


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
        'supports' => array('title','editor','excerpt','thumbnail','comments'),
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
      'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
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
if(!function_exists('etheme_create_slider')) {
  function etheme_create_slider($args, $slider_args = array()){//, $title = false, $shop_link = true, $slider_type = false, $items = '[[0, 1], [479,2], [619,2], [768,4],  [1200, 4], [1600, 4]]', $style = 'default'
    global $wpdb, $woocommerce_loop;
    $product_per_row = etheme_get_option('prodcuts_per_row');
    extract(shortcode_atts(array( 
      'title' => false,
      'shop_link' => false,
      'slider_type' => false,
      'items' => '[[0, 1], [479,2], [619,2], [768,4],  [1200, 4], [1600, 4]]',
      'style' => 'default',
      'block_id' => false
    ), $slider_args));
    
      $box_id = rand(1000,10000);
      $multislides = new WP_Query( $args );
      $shop_url = get_permalink(woocommerce_get_page_id('shop'));
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
                                      woocommerce_get_template_part( 'content', 'product' );
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
                    items:4, 
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
}

//***********************************************************************//
//* Etheme Global Search */
//***********************************************************************//

add_action("wp_ajax_et_get_search_result", "et_get_search_result");
add_action("wp_ajax_nopriv_et_get_search_result", "et_get_search_result");
if(!function_exists('et_get_search_result')) {
  function et_get_search_result() {
    $word = esc_attr(stripslashes($_REQUEST['s']));
    if(isset($word) && $word != '') {
      $response = array(
        'results' => false,
        'html' => ''
      );
      
      if(isset($_GET['count'])) {
        $count = $_GET['count'];
      } else {
        $count = 3;
      }
      
      if($_GET['products'] && class_exists('WooCommerce')) {
        $products_args = array(
          'args' => array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $count,
              'meta_key' => 'pa_style',
              'meta_key' => 'pa_subject',
              'meta_key' => 'pa_provider',
            's' => $word
          ),
          'image' => $_GET['images'],
          'link' => true,
          'title' => __('View Products', ETHEME_DOMAIN),
          'class' => 'et-result-products'
        );
        $products = et_search_get_result($products_args);
        if($products) {
          $response['results'] = true;
          $response['html'] .= $products;
        }
      }

      if($_GET['artists']) {
          $artists_args = array(
            'args' => array(
              'post_type' => 'artist',
              'post_status' => 'publish',
              'posts_per_page' => $count,
              's' => $word
            ),
            'title' => __('View Artists', ETHEME_DOMAIN),
            'image' => false, //$_GET['images'],
            'link' => true,
            'class' => 'et-result-post'
          );
          $artists = et_search_get_result($artists_args);
          if($artists) {
              $response['results'] = true;
              $response['html'] .= $artists;
          }
      }

      if($_GET['jewelers']) {
          $jewelers_args = array(
            'args' => array(
              'post_type' => 'jeweler',
              'post_status' => 'publish',
              'posts_per_page' => $count,
              's' => $word
            ),
            'title' => __('View jewelers', ETHEME_DOMAIN),
            'image' => false, //$_GET['images'],
            'link' => true,
            'class' => 'et-result-post'
          );
          $jewelers = et_search_get_result($jewelers_args);
          if($jewelers) {
              $response['results'] = true;
              $response['html'] .= $jewelers;
          }
      }
      
      if($_GET['posts']) {
        $posts_args = array(
          'args' => array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            's' => $word
          ),
          'title' => __('From the blog', ETHEME_DOMAIN),
          'image' => false,
          'link' => true,
          'class' => 'et-result-post'
        );
        $posts = et_search_get_result($posts_args);
        if($posts) {
          $response['results'] = true;
          $response['html'] .= $posts;
        }
      }
      
      if($_GET['portfolio']) {
        $portfolio_args = array(
          'args' => array(
            'post_type' => 'etheme_portfolio',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            's' => $word
          ),
          'image' => false,
          'link' => false,
          'title' => __('Portfolio', ETHEME_DOMAIN),
          'class' => 'et-result-portfolio'
        );
        $portfolio = et_search_get_result($portfolio_args);
        if($portfolio) {
          $response['results'] = true;
          $response['html'] .= $portfolio;
        }
      }
      
      if($_GET['pages']) {
        $pages_args = array(
          'args' => array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            's' => $word
          ),
          'image' => false,
          'link' => false,
          'title' => __('Pages', ETHEME_DOMAIN),
          'class' => 'et-result-pages'
        );
        $pages = et_search_get_result($pages_args);
        if($pages) {
          $response['results'] = true;
          $response['html'] .= $pages;
        }
      }
      
      echo json_encode($response);
      
      die();
    } else {
      die();
    }
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
// ! Add PrettyPhoto 'rel' attribute for lightbox
// **********************************************************************// 
 
function rc_add_rel_attribute($link) {
  global $post;
  return str_replace('<a href', '<a rel="prettyPhoto[pp_gal]" href', $link);
}
add_filter('wp_get_attachment_link', 'rc_add_rel_attribute');

 
function frontend_scripts_include_lightbox() {
  global $woocommerce;
 
  $suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
  $lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;
 
  if ( $lightbox_en ) {
    wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.5', true );
    wp_enqueue_script( 'prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
    wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css' );
  }
}
add_action( 'wp_enqueue_scripts', 'frontend_scripts_include_lightbox' );


<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'ancora_theme_setup' ) ) {
	add_action( 'ancora_action_before_init_theme', 'ancora_theme_setup', 1 );
	function ancora_theme_setup() {

		// Register theme menus
		add_filter( 'ancora_filter_add_theme_menus',		'ancora_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'ancora_filter_add_theme_sidebars',	'ancora_add_theme_sidebars' );

		// Set theme name and folder (for the update notifier)
		add_filter('ancora_filter_update_notifier', 		'ancora_set_theme_names_for_updater');
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'ancora_add_theme_menus' ) ) {
	//add_filter( 'ancora_action_add_theme_menus', 'ancora_add_theme_menus' );
	function ancora_add_theme_menus($menus) {
		
		//For example:
		//$menus['menu_footer'] = __('Footer Menu', 'ancora');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		
		if (isset($menus['menu_side'])) unset($menus['menu_side']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'ancora_add_theme_sidebars' ) ) {
	//add_filter( 'ancora_filter_add_theme_sidebars',	'ancora_add_theme_sidebars' );
	function ancora_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> __( 'Main Sidebar', 'ancora' ),
				'sidebar_footer'	=> __( 'Footer Sidebar', 'ancora' )
			);
			if (ancora_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = __( 'WooCommerce Cart Sidebar', 'ancora' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}

// Set theme name and folder (for the update notifier)
if ( !function_exists( 'ancora_set_theme_names_for_updater' ) ) {
	//add_filter('ancora_filter_update_notifier', 'ancora_set_theme_names_for_updater');
	function ancora_set_theme_names_for_updater($opt) {
		$opt['theme_name']   = 'Blessing';
		$opt['theme_folder'] = 'blessing';
		return $opt;
	}
}



/* Include framework core files
------------------------------------------------------------------- */

require_once( get_template_directory().'/fw/loader.php' );

function cimitero_user_logged_in()
{
    if ( is_user_logged_in() == true ) {
       return true;
    } else {
       return false;
    }
}
add_action('init', 'cimitero_user_logged_in');

/*-------------------------------------------------------------------------------------*/
/* Login Hooks and Filters
/*-------------------------------------------------------------------------------------*/
if( ! function_exists( 'custom_login_fail' ) ) {
    function custom_login_fail( $username ) {
        $referrer = $_SERVER['HTTP_REFERER']; // where did the post submission come from?
        // if there's a valid referrer, and it's not the default log-in screen
        if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
            if ( !strstr($referrer,'?login=failed') ) { // make sure we don’t append twice
                wp_redirect( $referrer . '?login=failed' ); // append some information (login=failed) to the URL for the theme to use
            } else {
                wp_redirect( $referrer );
            }
            exit;
        }
    }
}
add_action( 'wp_login_failed', 'custom_login_fail' ); // hook failed login

if( ! function_exists( 'custom_login_empty' ) ) {
    function custom_login_empty(){
        $referrer = $_SERVER['HTTP_REFERER'];
        if ( strstr($referrer,get_home_url()) && $user==null ) { // mylogin is the name of the loginpage.
            if ( !strstr($referrer,'?login=failed') ) { // prevent appending twice
                wp_redirect( $referrer . '?login=failed' );
            } else {
                wp_redirect( $referrer );
            }
        }
    }
}
add_action( 'authenticate', 'custom_login_empty');

/**
 * Redirect users after add to cart.
 */
function my_custom_add_to_cart_redirect( $url ) {
	$url = WC()->cart->get_checkout_url();
	// $url = wc_get_checkout_url(); // since WC 2.5.0
	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );

function render_defunto_on_cart_item( $title = null, $cart_item = null, $cart_item_key = null ) {
    if( $cart_item_key && is_cart() ) {
    	global $wpdb;
    	$defunto_id = WC()->session->get( 'defunto_id');
    	$defunto = $wpdb->get_row( "SELECT * FROM wp_defunti WHERE id = $defunto_id" );
    	
      echo $title. '<dl class="">
                 <dt class="">Al Defunto : '.$defunto->name.' [ #'.$defunto_id .' ]</dt>           
              </dl>';
    }else {
        echo $title;
    }
}
add_filter( 'woocommerce_cart_item_name', 'render_defunto_on_cart_item', 1, 3 );

function defunto_order_meta_handler( $item_id, $values, $cart_item_key ) {
    wc_add_order_item_meta( $item_id, "name_on_tshirt", WC()->session->get( 'defunto_id') );    
}
add_action( 'woocommerce_add_order_item_meta', 'defunto_order_meta_handler', 1, 3 );

<?php
/**
 * Plugin Name: Woocommerce allow guest checkout
 * Plugin URI: http://neofix.ch/
 * Description: Plugin to allow guest checkout for individuel products .
 * Version: 1.0.0
 * Author: Neofix
 * Author URI: http://neofix.ch
 */

class NeofixWooGuestCheckout{
  function __construct(){
    add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_custom_general_fields' ) );
    add_action( 'woocommerce_process_product_meta', array( $this, 'custom_general_fields_save' ) );
    add_filter( 'pre_option_woocommerce_enable_guest_checkout', array( $this, 'enable_guest_checkout_based_on_product' ) );
  }

  function add_custom_general_fields() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    // Checkbox
    woocommerce_wp_checkbox( 
    array( 
    'id'            => '_allow_guest_checkout', 
    'label'         => __('Checkout', 'woocommerce' ), 
    'description'   => __('Allow Guest Checkout', 'woocommerce' ) 
    )
     );
    
    echo '</div>';
  }

  function custom_general_fields_save( $post_id ){
    $woocommerce_checkbox = isset( $_POST['_allow_guest_checkout'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_allow_guest_checkout', $woocommerce_checkbox );
  }
  
  function enable_guest_checkout_based_on_product( $value ) {

    if ( WC()->cart ) {
      $cart = WC()->cart->get_cart();
      foreach ( $cart as $item ) {
        if ( get_post_meta( $item['product_id'], '_allow_guest_checkout', true ) == 'yes' ) {
          $value = "yes";
        } else {
          $value = "no";
          break;
        }
      }
    }
    
    return $value;
  }
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  new NeofixWooGuestCheckout();   
}

?>
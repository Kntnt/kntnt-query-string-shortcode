<?php

/**
 * Plugin main file.
 *
 * @wordpress-plugin
 * Plugin Name:       Kntnt Query String Shortcode
 * Plugin URI:        https://www.kntnt.com/
 * GitHub Plugin URI: https://github.com/Kntnt/kntnt-query-string-shortcode
 * Description:       Provides the shortcode [get field="…" default="…"] that returns the value of the query string field with the name given by field="…" or the value of default="…" if no such field is found.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


namespace Kntnt\Query_String_Shortcode;

defined( 'WPINC' ) && new Plugin();

class Plugin {

  private static $defaults = [
    'field' => '',
    'default' => '',
  ];
      
  public function __construct() {
    add_shortcode( 'get', [ $this, 'get_shortcode' ] );
  }
  
  public function get_shortcode( $atts ) {
    $atts = $this->shortcode_atts( self::$defaults, $atts );
    return isset( $_GET[$atts['field']] ) ? htmlspecialchars( $_GET[$atts['field']] ) : $defaults;
  }
  
  // A more forgiving version of WP's shortcode_atts().
  private function shortcode_atts( $pairs, $atts, $shortcode = '' ) {

    $atts = (array) $atts;
    $out = [];
    $pos = 0;
    while( $name = key($pairs) ) {
      $default = array_shift( $pairs );
      if ( array_key_exists($name, $atts ) ) {
        $out[$name] = $atts[$name];
      }
      elseif ( array_key_exists( $pos, $atts ) ) {
        $out[$name] = $atts[$pos];
        ++$pos;
      }
      else {
        $out[$name] = $default;
      }
    }

    if ( $shortcode ) {
      $out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
    }
    
    return $out;

  }

}

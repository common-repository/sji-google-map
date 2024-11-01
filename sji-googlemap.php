<?php
/*
  Plugin Name: SJInnovation Google Map
  Plugin URI: http://sjinnovation.com/products/
  Description: SJInnovation Google Map
  Version: 1.0.0
  Author: sjinnovation
  Author URI: http://sjinnovation.com/about-us/
  License: GPL v3
  License URI: https://www.gnu.org/licenses/gpl.html
  Text Domain: 
*/

if ( !class_exists( 'sji_pro_google_map' ) ) {
  class sji_pro_google_map {

    // Constructor
    function __construct() {

        add_action( 'admin_menu', array( $this, 'sji_pro_add_menu' ));
        register_activation_hook( __FILE__, array( $this, 'sji_pro_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'sji_pro_uninstall' ) );
    }

    /*
     * Actions perform at loading of admin menu
     */
    function sji_pro_add_menu() {

      add_submenu_page('Settings', 'Settings', 'Settings', 'manage_options','settings','sji_pro_settings');
    }

    /*
     * Actions perform on loading of menu pages
     */
    function sji_pro_page_file_path() {

    }

    /*
     * Actions perform on activation of plugin
     */
    function sji_pro_install() {

    }

    /*
     * Actions perform on de-activation of plugin
     */
    function sji_pro_uninstall() {

    }
  }
}

new sji_pro_google_map();

/**
 * top level menu
 */
if ( !function_exists( 'sji_pro_options_page' ) ) {
  function sji_pro_options_page() {
   // add top level menu page
   add_menu_page(
   'Google Map Settings',
   'Google Map Settings',
   'manage_options',
   'googlemapsettings',
   'sji_pro_google_map_settings'
   );
  }
}
 
/**
 * register our wporg_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'sji_pro_options_page' );

//adding testimonial page settings values  and registering this as pageoption
add_action( 'admin_init', 'sji_pro_map_settings_init' );

if ( !function_exists( 'sji_pro_map_settings_init' ) ) {
  function sji_pro_map_settings_init() {
      register_setting( 'google-map-api-settings', 'sji_pro_google_map_settings' );
      add_settings_section(
          'google_map_pluginPage_section', 
          __( 'Google Map Settings Page', 'googlemap' ), 
          'google_map_section_callback', 
          'pluginPage'
      );
  }
}

/**
 * top level menu:
 * callback functions
 */
if ( !function_exists( 'sji_pro_google_map_settings' ) ) {
  function sji_pro_google_map_settings() {
   // check user capabilities
   if ( ! current_user_can( 'manage_options' ) ) {
   return;
   }
   ?>
   <p></p>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#general-settings" role="tab">General</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#credits" role="tab">Credits</a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="general-settings" role="tabpanel">
        <h1>Google map settings</h1>
        <form method="post" action="<?php echo get_admin_url(); ?>options.php">
        <?php 
          settings_fields( 'google-map-api-settings' );
          do_settings_sections( 'google-map-api-settings' );
          $options = get_option( 'sji_pro_google_map_settings' );
          if(!isset($options['lattitude']) && !isset($options['longitude'])){
            $options['lattitude'] = -25.363882;
            $options['longitude'] = 131.044922; 
          }
        ?>
        <table class="form-table">
          <tr valign="top">
            <th scope="row">Shortcode:</th>
            <td>[sji_google_map]</td>
          </tr>
          <tr valign="top">
          <th scope="row">Google Map API:</th>
          <td>
            <div style="margin-bottom:10px;">
              <input type="checkbox" id="default_api_key" name="sji_pro_google_map_settings[default_api_key]" value="<?php echo DEFAULT_API_KEY; ?>" <?php if(isset($options['default_api_key'])) echo 'checked="checked"'; ?> />
              <label for="default_api_key">Use Default (Uncheck to add your API Key below)</label>
            </div>
            <div>
              <input type="text" id="api" name="sji_pro_google_map_settings[api]" value="<?php echo $options['api']; ?>" style="width: 350px;<?php if(isset($options['default_api_key'])) echo 'display: none;'; ?>" />
            </div>
            </td>
          </tr>
          <tr valign="top">
          <th scope="row">Add Location:</th>
            <td>
              <div>
                <label for="lattitude">Lattitude: </label>
                <input type="number" name="sji_pro_google_map_settings[lattitude]" value="<?php echo $options['lattitude']; ?>" style="width: 350px;" readonly />
                <label for="longitude">Longitude: </label>
                <input type="number" name="sji_pro_google_map_settings[longitude]" value="<?php echo $options['longitude']; ?>" style="width: 350px;" readonly />
              </div>
              <div id="map" style="width: 100%;height: 300px;"></div>
            </td>
          </tr>
        </table>
        <?php submit_button(); ?>
      </form>
      </div>
      <div class="tab-pane" id="credits" role="tabpanel">
        <div class = "post-box">
          <div class = "inside">
             <div style="margin:50px;">
               <img style= "display: block;margin-left: auto; margin-right: auto;" src= <?php echo  plugins_url(plugin_basename(dirname(__FILE__)).'/images/logo.png', dirname(__FILE__)) ?> />
               <div class="company-links" style="text-align: center; margin-top:20px;margin-bottom:20px">
                 <a  style="margin:20px; font-size: 30px;" target = "_blank" href ="http://sjinnovation.com"><i class="fa fa-external-link"></i></a>
                 <a style="margin:20px; font-size: 30px;"  target = "_blank" href ="https://www.facebook.com/sjinnovation?fref=ts"><i class="fa fa-facebook" aria-hidden="true"></i></i></a>
                 <a  style="margin:20px; font-size: 30px;" target = "_blank"  href ="https://in.linkedin.com/company/sj-innovation"><i class="fa fa-linkedin" aria-hidden="true"></i></i></a>
                 <a  style="margin:20px; font-size: 30px;"  target = "_blank" href ="https://twitter.com/sjinnovation"><i class="fa fa-twitter" aria-hidden="true"></i></i></a>
                 <a  style="margin:20px; font-size: 30px;" target = "_blank" href ="https://www.instagram.com/sj_innovation/"><i class="fa fa-instagram" aria-hidden="true"></i></i></a>
                 <a  style="margin:20px; font-size: 30px;"  target = "_blank" href ="https://plus.google.com/u/0/+Sjinnovation/posts"><i style="font-size: 40px;" class="fa fa-google-plus" aria-hidden="true"></i></i></a>
                </div>
                <div><h3>Developed by:</h3></div> 
                <p> SJ INNOVATION LLC</p>
             </div>
          </div>
      </div>
      </div>
    </div>
    
  <?php
   }
 }

/**
 * Function to add assests
 */
add_action( 'wp_enqueue_scripts', 'sji_pro_my_enqueued_assets' );

if ( !function_exists( 'sji_pro_my_enqueued_assets' ) ) {
  function sji_pro_my_enqueued_assets() {
    // wp_enqueue_style( 'my-font', '//fonts.googleapis.com/css?family=Roboto' );
  }
}

add_action('wp_head', 'sji_pro_my_enqueued_header_scripts');
add_action('admin_head', 'sji_pro_my_enqueued_header_scripts');
add_action('wp_footer', 'sji_pro_my_enqueued_footer_scripts');
add_action('wp_footer', 'sji_pro_my_enqueued_front_end_scripts');
add_action( 'in_admin_footer', 'sji_pro_my_enqueued_footer_scripts' );
add_action( 'in_admin_footer', 'sji_pro_my_enqueued_back_end_scripts' );

/**
 * Function to add footer scripts
 */
if ( !function_exists( 'sji_pro_my_enqueued_header_scripts' ) ) {
  function sji_pro_my_enqueued_header_scripts() {
    wp_enqueue_style('bootstrap-style',  plugins_url(plugin_basename(dirname(__FILE__)).'/css/bootstrap.min.css', dirname(__FILE__)));
    wp_enqueue_style( 'font-awesome', 'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css');
  }
}

/**
 * Function to add footer scripts
 */
if ( !function_exists( 'sji_pro_my_enqueued_footer_scripts' ) ) {
  function sji_pro_my_enqueued_footer_scripts() {
    $options = get_option( 'sji_pro_google_map_settings' );
    if(isset($options['default_api_key']))
      wp_register_script('sji-googlemap', 'http://maps.googleapis.com/maps/api/js?key='.$options['default_api_key'], array("jquery"));
    else
      wp_register_script('sji-googlemap', 'http://maps.googleapis.com/maps/api/js?key='.$options['api'], array("jquery"));
    wp_enqueue_script('sji-googlemap');
    wp_register_script('tether-script', plugins_url(plugin_basename(dirname(__FILE__)).'/js/tether.min.js', dirname(__FILE__)) , array("jquery"));
    wp_enqueue_script('tether-script');
    wp_register_script('bootstrap-script', plugins_url(plugin_basename(dirname(__FILE__)).'/js/bootstrap.min.js', dirname(__FILE__)) , array("jquery"));
    wp_enqueue_script('bootstrap-script');
  }
}

/**
 * Function to add frontend scripts
 */
if ( !function_exists( 'sji_pro_my_enqueued_front_end_scripts' ) ) {
  function sji_pro_my_enqueued_front_end_scripts() {
    wp_register_script('sji-map-front-end', plugins_url(plugin_basename(dirname(__FILE__)).'/js/map.js', dirname(__FILE__)), array("jquery"));
    wp_enqueue_script('sji-map-front-end');
    wp_localize_script( 'sji-map-front-end', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin_ajax.php' ) ) );
  }
}

/**
 * Function to add frontend scripts
 */
if ( !function_exists( 'sji_pro_my_enqueued_back_end_scripts' ) ) {
  function sji_pro_my_enqueued_back_end_scripts() {
    wp_register_script('sji-map-back-end', plugins_url(plugin_basename(dirname(__FILE__)).'/js/admin-map.js', dirname(__FILE__)), array("jquery"));
    wp_enqueue_script('sji-map-back-end');
  }
}

/**
 * Function for google mao shortcode
 */
add_shortcode('sji_google_map', 'sji_pro_google_map_function');

if ( !function_exists( 'sji_pro_google_map_function' ) ) {
  function sji_pro_google_map_function() {
    return '<div id="map-wrap" style="width: 100%;height: 300px;"></div>';
  }
}

/**
 * Function for ajax call in script
 */
add_action( 'wp_ajax_get_coordinates', 'sji_pro_get_map_coordinates' );
add_action( 'wp_ajax_nopriv_get_coordinates', 'sji_pro_get_map_coordinates' );

if ( !function_exists( 'sji_pro_get_map_coordinates' ) ) {
  function sji_pro_get_map_coordinates() {
    $options = get_option( 'sji_pro_google_map_settings' );
    echo json_encode($options);
    wp_die();
  }
}
?>
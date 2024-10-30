<?php
/*
 * Plugin Name: Mobile browser color select
 * Description: UPDATE YOUR BROWSER COLOR WITH JUST ONE CLICK.<br>With this plugin you can select your browser tab color and save.<br><strong>no professional experince required</strong><br>dont forget to rate and share ♥
 * Version: 1.0.1
 * Author: www.script.co.il
 * Author URI: https://www.script.co.il/
 * License: GPLv2 or later
 * Text Domain: mbcs
*/

if (!defined( 'ABSPATH' )) die('whhhhattt??!');


class mobile_browser_color_select{
    
    private $color, $option_id;

    public function __construct (){
        $this->option_id = 'mbcs_browser_color';
        $this->color = get_option( $this->option_id );
        if ( is_admin() ){
            //INIT WORDPRESS COLOR PICKER INTO ADMIN PANEL
            $this->admin_create_section();
            
            //INIT LANGUAGES
            add_action( 'plugins_loaded', array($this, 'init_languages' ));
        }else{
            //INIT COLOR INTO WORDPRESS MARKUP LET THE MAGIC BEGIN
            if (! $this->color) return;
            $this->front_output_markup();
        }
        
    }
        
    public function admin_create_section(){
        // INIT MENU
        add_action( 'admin_menu', array($this, 'add_my_menu' ) );
        
        // INIT COLOR PICKER
        add_action('admin_enqueue_scripts', function(){
            wp_enqueue_style( 'wp-color-picker' );
	        wp_enqueue_script( 'mb_chrome_script', plugins_url('init.js', __FILE__ ), array( 'wp-color-picker' ), true, true );
        });
    }
    
    public function add_my_menu(){
    	add_options_page( 'Mobile browser color', __('Mobile browser color select', 'mbcs'), 'manage_options', 'mobile-browser-color-select', array($this, 'admin_page_content') );
    }
    
    public function init_languages(){
        load_plugin_textdomain( 'mbcs', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
    }
    
    public function admin_page_content(){
        
        $this->admin_update_data();
        $this->back_output_markup();
        
    }
    
    public function admin_update_data(){
        if (! empty( $_POST['color'] ) ){
            $this->color = $_POST['color'];
            update_option($this->option_id, $this->color);
        }
    }
    
    public function front_output_markup(){
        add_action('wp_head', function(){
			echo '<!-- Chrome, Firefox OS and Opera -->
			<meta name="theme-color" content="' . $this->color . '">
			<!-- Windows Phone -->
			<meta name="msapplication-navbutton-color" content="' . $this->color . '">
			<!-- iOS Safari -->
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-status-bar-style" content="' . $this->color . '">'; 
        });
    }
    
    public function back_output_markup(){
        ?>
        <form method="post">
            <h1><?= _e('Tab color', 'mbcs'); ?></h1>
            <label for="color"><?= _e('Choose color', 'mbcs'); ?>:</label>
            <input type="text" id="color" name="color" value="<?= $this->color; ?>">
            <button type="submit"><?= _e('Save', 'mbcs'); ?></button>
        </form>
        <?php
    }

}
    

$mbcs = new mobile_browser_color_select();
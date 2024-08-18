<?php
/*
Plugin Name: My First Plugin
Description: This is my first Plugin
Version:1.0.0
Author: Suprakash
Author URI: https://www.viacon.in/
License: GPLv2 or later
Text Domain: my-first-plugin
*/
defined('ABSPATH') or die('What are you doing here?');
require_once plugin_dir_path(__FILE__) . 'includes/mfp-functions.php';

class MyFirstPlugin{
    public $pluginLink;
    function __construct(){
        add_action('init', array($this,'customPostType'));
        
        $this->pluginLink = plugin_basename(__FILE__);
        
    }
    function activate(){
        $this->customPostType();
        flush_rewrite_rules();

        global $wpdb, $table_prefix;
        $wp_emp = $table_prefix.'emp';
        $q="CREATE TABLE IF NOT EXISTS `$wp_emp` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `mail` VARCHAR(255) NOT NULL , `status` BOOLEAN NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $wpdb->query($q);

        $q="INSERT INTO `$wp_emp` (`name`, `mail`, `status`) VALUES ('Jhon', 'test@gmail.com', 1);";
        $wpdb->query($q);
    }
    function deactivate(){
        flush_rewrite_rules();
        global $wpdb, $table_prefix;
        $wp_emp = $table_prefix.'emp';
        $q="DROP TABLE `worldhealthlife`.`$wp_emp`";
        $wpdb->query($q);
    }

   
    function customPostType(){
        register_post_type("book", ['public'=>true, 'label'=>'Books']);
    }
   

    function register(){
        //load script in admin
        add_action('admin_enqueue_scripts', array($this,'enque'));
        //load script in frontend
        add_action('wp_enqueue_scripts', array($this,'enque'));
        //add_action('wp_enqueue_style', array($this,'enque'));
        // Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
        add_action( 'admin_menu', array($this, 'mfp_Add_My_Admin_Link'));
        add_filter("plugin_action_links_$this->pluginLink", array($this,'setting_link'));
    }

    
// Add a new top level menu link to the ACP
public function mfp_Add_My_Admin_Link()
{
      add_menu_page(
        'My First Page', // Title of the page
        'My First Plugin', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'mfp-first-acp-page', // The 'slug' - file to display when clicking the link
        array($this, 'EmpTable'),// Call the function on page load
        'dashicons-image-filter',// Icon
        10 //Position of the menu
    );
    add_submenu_page('mfp-first-acp-page',
    'Employee List',
    'Employee List',
    'manage_options',
    'mfp-first-acp-page',
    array($this, 'EmpTable'),
    );
    add_submenu_page('mfp-first-acp-page',
    'Menu Subpage',
    'Menu Subpage',
    'manage_options',
    'mfp-sub_first-acp-page',
    array($this, 'admin_index')
    );
}

public function admin_index(){
require_once plugin_dir_path(__FILE__).'includes/mfp-first-acp-page.php';
}
public function EmpTable(){
    require_once plugin_dir_path(__FILE__).'includes/emp-table.php';
    }

//Plugin setting Link    
public function setting_link($link){
    $myp_link='<a href="admin.php?page=mfp-first-acp-page">Settings</a>';
    array_push($link,$myp_link);
    return $link;
}
    function enque(){
        wp_enqueue_style("mypluginstyle", plugins_url('assets/mystyle.css',__FILE__));
        wp_enqueue_script("mypluginscript", plugins_url('assets/myscript.js',__FILE__),'var ajaxUrl="'.admin_url('admin-ajax.php').'"');
    }

}
if(class_exists('MyFirstPlugin')){
    $mfPlugin= new MyFirstPlugin();
    $mfPlugin->register();
}


//Activate
register_activation_hook(__FILE__, array($mfPlugin,'activate'));

//Deactivate
register_deactivation_hook(__FILE__, array($mfPlugin,'deactivate'));



//Employee ajax search
add_action('wp_ajax_myAjaxSearh', 'myAjaxSearh');
add_action('wp_ajax_nopriv_myAjaxSearh', 'myAjaxSearh');
    function myAjaxSearh(){
        global $wpdb, $table_prefix;
        $wp_emp= $table_prefix.'emp';
        $search_term=$_POST['searchTerm'];
        if(!empty($search_term)){
            $listEmp="SELECT * FROM `$wp_emp` WHERE `name` LIKE '%".$search_term."%'
            OR `mail` LIKE '%".$search_term."%'
            OR `status` LIKE '%".$search_term."%'
            ;"; 
        } else {
            $listEmp="SELECT * FROM `$wp_emp`";
        }
        $getList=$wpdb->get_results($listEmp);
        ob_start(); ?>
             <?php foreach($getList as $list): ?>
        <tr>
            <td><?php echo $list->name; ?></td>
            <td><?php echo $list->mail;  ?></td>
            <td><?php echo $list->status; ?></td>
        </tr>
        <?php endforeach; ?>

        <?php echo ob_get_clean();
        die();
    }

// Search funciton in the frontend

add_shortcode('emptable', 'tableFront');
function tableFront(){
    ob_start();
    require_once plugin_dir_path(__FILE__).'includes/emp-table.php';
    return ob_get_clean(); 
}

// Register Function
function userCreate(){
    ob_start();
    require_once plugin_dir_path(__FILE__).'includes/userregis.php';
    return ob_get_clean();
}
add_shortcode('usercreate', 'userCreate');
// User Login
function mylogin(){
    if(isset($_POST['user_login'])){
        $user_name=esc_sql($_POST['user_name']);
        $user_password=esc_sql($_POST['user_password']);
    $credentials=array(
        'user_login'=>$user_name,
        'user_password'=>$user_password
    );

    $userL=wp_signon($credentials);
    if(!is_wp_error($userL)){
        wp_redirect((site_url('/profile')));
        die();
    } else {
        echo $userL;
    }
}
}
add_action('template_redirect', 'mylogin');

//Page Redirect 
function my_check_redirect(){
    $isuserLogin=is_user_logged_in();
    if($isuserLogin && is_page('register-2')){
        wp_redirect(site_url('/profile'));
    } elseif(!$isuserLogin && is_page('profile')){
        wp_redirect(site_url('/register-2'));
    }
}

add_action('template_redirect','my_check_redirect');

//Profile Page
function userProfile(){
    ob_start();
    require_once plugin_dir_path(__FILE__).'includes/profile.php';
    return ob_get_clean();
}
add_shortcode('userprofile', 'userProfile');

//Social media in footer
function option_menu_page(){
    require_once plugin_dir_path(__FILE__).'includes/social.php';
}
function option_menu(){
    add_submenu_page('options-general.php','Social','Social','manage_options', 'social-menu-page','option_menu_page');
}
add_action('admin_menu', 'option_menu');
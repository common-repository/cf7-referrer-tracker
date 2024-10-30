<?php
/**
 * @package Referer tracker for Contact Form 7
 */
/*
    Plugin Name: Referer tracker for Contact Form 7
    Plugin URI:  https://wordpress.org/plugins/cf7-referrer-tracker/
    Author: Bitss Techniques
    Author URI: http://bitss.tech/
    Description: Automatically include visitor's tracking information (from http headers) in Contact Form 7 Emails. 
    Version:  1.0.1
    License: GPLv2 or later
    Text Domain: bitss
*/

add_action('init', 'cf7rt_set_referer_cookie');
function cf7rt_set_referer_cookie() {
   
    $is_contact_form_7_active=check_contact_form_isActive();
    if($is_contact_form_7_active){    
        $user_agent="Unknown";       
        if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT']) ) {
            $user_agent="Internet Explorer";
        } else if(preg_match('/Chrome/i',$_SERVER['HTTP_USER_AGENT'])) {
            $user_agent="Google Chrome";
        } else if(preg_match('/Firefox/i',$_SERVER['HTTP_USER_AGENT'])) {
            $user_agent="Mozilla Firefox";
        }         
        $cf7rt_selected_http_headers = get_option("cf7rt_selected_http_headers",array('HTTP-Referer'));           
        $i=0;        
        while(sizeof($cf7rt_selected_http_headers) > $i){
            $cf7rt_selected_http_headers_value=strtoupper( $cf7rt_selected_http_headers[$i]);
            $cf7rt_selected_http_headers_value=str_replace("-","_",$cf7rt_selected_http_headers_value);            
            if(isset($_SERVER[$cf7rt_selected_http_headers_value])&&!isset($_COOKIE["cf7rt_".$cf7rt_selected_http_headers_value])) {
                setcookie("cf7rt_".$cf7rt_selected_http_headers_value, $_SERVER[$cf7rt_selected_http_headers_value],0,"/");	
            }
            $i=$i+1;
        }       
    } else {       
        //cf7rt_inValid_data_notice();
    }
}

function check_contact_form_isActive()
 {
     return is_plugin_active('contact-form-7/wp-contact-form-7.php')? true: false;
 }

add_action('admin_menu', 'cf7rt_my_menu_pages');
function cf7rt_my_menu_pages() {      
    add_submenu_page(
        'options-general.php',
        'Referer Tracker For Contact Form 7',
        'Referer Tracker For Contact Form 7',
        'manage_options',
        'cf7rt-optionSetting',
        'cf7rt_optionSetting'
    );
}

function cf7rt_optionSetting() {    
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'setting_options';    
    $is_contact_form_7_active=check_contact_form_isActive();    
    $bitss_track_http_headers = array("HTTP-Referer" , 
                                        "Remote-Addr", 
                                        "Http-X-Forwarded-For",
                                        "Http-User-Agent");
            
    if(isset($_POST["submit"]))
    {
        $selectOption=$_POST['include_http_header_in_admin_email'];
        $selected_referers=$_POST["track_http_headers"];
        $isValid =false;

        //validate "Include HTTP header's in email" dropdown
        if($selectOption!="Automatic"&&$selectOption!="Manual"){
            $isValid =false;                          
        }else{
            $isValid =true;          
        }

        //validate selected http referers
        if($isValid==true){
            foreach ($selected_referers as $selected_referer) {
                if(!in_array($selected_referer,$bitss_track_http_headers)){
                    $isValid =false;   
                } else {
                    $isValid =true;      
                }
            }
        }

        //if isValid is still true, then validation was successful, proceed to save data in database.
        if($isValid==true){
            update_option("include_http_header_in_admin_email", $selectOption);
            update_option("cf7rt_selected_http_headers",$selected_referers);            
            cf7rt_data_save_notice();           
        }else{
            cf7rt_inValid_data_notice(); 
        }            
    }
    //$selectOption=get_option("")
    $cf7rt_selected_http_headers = get_option("cf7rt_selected_http_headers",array('HTTP-Referer'));
    $selectOption  = get_option("include_http_header_in_admin_email",'Automatic');
    $template_path = plugin_dir_path( __FILE__ )."_inc/template/plugin_option.php"; 
    include_once($template_path);   
    
}



function cf7rt_inValid_data_notice() {
    ?>
    <div class="error notice">
      <p><strong>There are validation errors, please check and try again.</strong> </p>
    </div>
    <?php
}
function cf7rt_data_save_notice() {   
    ?>
    <div class="updated notice">
        <p>Settings Saved.</p>
    </div>
    <?php
}

add_action('wpcf7_before_send_mail', 'cf7rt_add_text_to_mail_body' ); 
function cf7rt_add_text_to_mail_body($contact_form){
    $is_contact_form_7_active=check_contact_form_isActive();
    if($is_contact_form_7_active){
        $mail = $contact_form->prop( 'mail' ); 
        $optionValue=get_option("include_http_header_in_admin_email",'Automatic');
        if($optionValue=="Automatic"){
            $mail['body'].= "\r\n";
            $cf7rt_selected_http_headers = get_option("cf7rt_selected_http_headers",array('HTTP-Referer'));
            $i=0;
            $mail['body'].="<br><br>";
            $mail['body'].="****Referer Tracking****<br>";
            while(sizeof($cf7rt_selected_http_headers) > $i){
                $cf7rt_selected_http_headers_value=strtoupper( $cf7rt_selected_http_headers[$i]);
                $cf7rt_selected_http_headers_value=str_replace("-","_",$cf7rt_selected_http_headers_value);
                $mail['body'].= $cf7rt_selected_http_headers_value.":\t".$_COOKIE["cf7rt_".$cf7rt_selected_http_headers_value];                
                $mail['body'].="<br>";
                $i=$i+1;
            }
        }
        $contact_form->set_properties( array( 'mail' => $mail ) );  
    }   
}

// function my_special_mail_tag( $output, $name, $html ) {
// 	if ( 'cf7rt_http_referer' == $name )
// 		$output = $_COOKIE["cf7rt_"];
 
// 	return $output;
// }
// add_filter( 'wpcf7_special_mail_tags', 'my_special_mail_tag', 10, 3 );

?>
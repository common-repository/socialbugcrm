<?php

namespace SocialbugCRM\Includes;

use SocialbugCRM\Includes\PluginOptions;
use SocialbugCRM\Includes\DB;

register_activation_hook(SOCIALBUGCRM_PAGE, array($db, 'createTable'));
register_deactivation_hook(SOCIALBUGCRM_PAGE, array($db, 'dropTable'));

class OptionsBuilder
{
    static public function getPluginOptions() {
        $defaults = array(
            'ApiKey' => '',
            'AppendHtml' => '',
        );
        
        $option = wp_parse_args(get_option(self::SOCIALBUGCRM_OPTIONS), $defaults);
        return $option;
    }

    public function Init()
    {
        $launchAppHook = add_menu_page('Launch SocialBugCRM', 'Launch SocialBugCRM', 'edit_pages', 'socialbugcrm_options', array( $this, 'LaunchApp' ), 'dashicons-admin-home', '7.62');
        add_action('load-'.$launchAppHook, array($this, 'ListScreenOptions'));

        add_filter('plugin_action_links_'.plugin_basename(SOCIALBUGCRM_PAGE), array($this, 'MakeLink')); 
        
        wp_add_inline_script('common', 'jQuery(document).ready(function($) {

        $("a.toplevel_page_socialbugcrm_options").attr("target","_blank");

        });');
    }

    function ListScreenOptions() 
    {
        return;
    }

    public function MakeLink($links) {
        $links[] = '<a href="'.esc_url(get_admin_url(null, 'options-general.php?page='.plugin_basename('socialbugcrm_options'))).'" target="_blank">Launch SocialBugCRM</a>';
        return $links;
    }
   
    private function GenerateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function LaunchApp()
    {
        global $wpdb, $db;

        $options = get_option('socialbugcrm');
        $apiKey = $options['ApiKey'];

        $db = new DB();

        $integration = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$db->getTableName().' WHERE ApiKey=%s', $apiKey), ARRAY_A);
        $salt = $integration['Salt'];
        $userId = $integration['UserId'];

        if ($salt == null)
        {
            global $wpdb;

            $userId = get_current_user_id();
            $salt = $this->GenerateRandomString();
            $apiKey = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
            
            $db->addNewRecord($apiKey, current_time('mysql', 1), $salt, $userId);
        }
        
        $options['Salt'] = $salt;
        $options['UserId'] = $userId;
        $options['ApiKey'] = $apiKey;

        update_option('socialbugcrm', $options);

        $url = esc_url(home_url());
        wp_redirect('https://socialbugcrm.sb-affiliate.com/api/gateway/stores/public/installation/woocommerce/Install/1?authorization_code='.$apiKey.'&site='.$url);
    }
}

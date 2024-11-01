<?php

use SocialbugCRM\Includes\DB;

global $db;
$db = new DB();

function Autoload( $class_name ) {
	$parts = explode( '\\', $class_name );
	if ( count( $parts ) > 2 && 'SocialBugCRM' == $parts[0] && 'Classes' == $parts[1] ) {
		array_shift( $parts );
		array_shift( $parts );
		$class_path = '';
		if ( substr( end( $parts ), -9 ) == 'Interface' ) {
			$class_path .= 'interfaces' . DIRECTORY_SEPARATOR;
		}

		$class_path .= implode( DIRECTORY_SEPARATOR, $parts );
		$filename = SOCIALBUGCRM_DIR . 'classes/' . $class_path . '.php';
		if ( file_exists( $filename ) ) {
			include_once( $filename );
		}
	}
}

spl_autoload_register( 'Autoload' );

add_action( 'wp_footer', 'LoadHtmlOnPage', 10000000);
add_action( 'rest_api_init', function () {
	$namespace = 'socialbugcrm';

	register_rest_route( $namespace, '/HelloWorld', array(
		'methods' => 'GET',
		'callback' => 'HelloWorld',
		'permission_callback' => 'CheckAuth',
	));
	register_rest_route( $namespace, '/PostStoreInfo', array(
		'methods' => 'POST',
		'callback' => 'PostStoreInfo',
		'permission_callback' => 'CheckAuth',
	));
	register_rest_route( $namespace, '/GetStoreInfo', array(
		'methods' => 'GET',
		'callback' => 'GetStoreInfo',
		'permission_callback' => 'CheckAuth',
    ));
    register_rest_route( $namespace, '/PostSalt', array(
		'methods' => 'POST',
		'callback' => 'PostSalt',
		'permission_callback' => 'CheckAuth',
    ));
    register_rest_route( $namespace, '/AddCustomer', array(
		'methods' => 'POST',
		'callback' => 'AddCustomer',
		'permission_callback' => 'CheckAuth',
    ));
    register_rest_route( $namespace, '/GetCustomerByEmail/(?P<email>.*)', array(
		'methods' => 'GET',
		'callback' => 'GetCustomerByEmail',
		'args' => array(
			'email' => array(
				'validate_callback' => 'IsEmail'
				)
			),
		'permission_callback' => 'CheckAuth',
		));
});

function CheckAuth() {
	if(array_key_exists('HTTP_X_GUID', $_SERVER) == false) {
		return false;
	}
	$key = $_SERVER['HTTP_X_GUID'];

    global $wpdb, $db;
    
    $ApiKey = $wpdb->get_var($wpdb->prepare('SELECT ApiKey FROM '.$db->getTableName().' WHERE ApiKey=%s', $key));
	if($ApiKey) {
		return true;
	}
    
    return false;
}

function HelloWorld() {
	return 1;
}

function GetStoreInfo(WP_REST_Request $request) {
    global $wpdb, $db;
    
    $key = $_SERVER['HTTP_X_GUID'];

    $integration = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$db->getTableName().' WHERE ApiKey=%s', $key), ARRAY_A);
    $userId = $integration['UserId'];
    $user = get_userdata($userId);

    $integration['Email'] = $user->user_email;
    $integration['FirstName'] = $user->first_name;
    $integration['LastName'] = $user->last_name;

    $options = get_option('socialbugcrm');
    $options['AppendHtml'] = $integration['AppendHtml'];
    $options['Salt'] = $integration['Salt'];
    $options['UserId'] = $userId;

	return $integration;
}

function PostStoreInfo(WP_REST_Request $request) {
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		$postresource = fopen("php://input", "r");
		while ($postData = fread($postresource, 1024)) {
			$input_data .= $postData;
		}
		fclose($postresource);
    }
    
    $key = $_SERVER['HTTP_X_GUID'];

    global $wpdb, $db;

	if (isset($input_data)) {
		$result = $wpdb->update(
			$db->getTableName(),              // table
			array(                            // columns to fill
                'AppendHtml' => $input_data
			),
			array('ApiKey' => $key),          // where
			array(                            // columns type
				'%s'
			),
			'%s'                              // where type
        );
        
        $options = get_option('socialbugcrm');
        $options['ApiKey'] = $key;
        $options['AppendHtml'] = $input_data;
        update_option('socialbugcrm', $options);
	}

    return $result;
}

function PostSalt(WP_REST_Request $request) {
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		$postresource = fopen("php://input", "r");
		while ($postData = fread($postresource, 1024)) {
			$input_data .= $postData;
		}
		fclose($postresource);
    }
    
    $key = $_SERVER['HTTP_X_GUID'];

    global $wpdb, $db;

	if (isset($input_data)) {
		$result = $wpdb->update(
			$db->getTableName(),              // table
			array(                            // columns to fill
                'Salt' => $input_data
			),
			array('ApiKey' => $key),          // where
			array(                            // columns type
				'%s'
			),
			'%s'                              // where type
        );
        
        $options = get_option('socialbugcrm');
        $options['ApiKey'] = $key;
        $options['Salt'] = $input_data;
        update_option('socialbugcrm', $options);
	}

    return $result;
}

function AddCustomer(WP_REST_Request $request) {
	$json = $request->get_body();
	$data = json_decode($json, 1);
    
    if(false == array_key_exists('Customer', $data)) {
		return null;
	}
    
    $customer = new \SocialBugCRM\Classes\Customer();
	$customer->extractDataFromJson($data['Customer']);
    
    $username_exists = username_exists($customer->getUserName());
	$user = get_user_by_email($customer->getEmail());
    
    if($user->ID) {
		$email_exists = true;
	} else {
		$email_exists = false;
	}
    
    if( false == $username_exists && false == $email_exists ) {
		$user_id = wp_create_user ( $customer->getUserName(), $customer->getPassword(), $customer->getEmail() );
		$user = new WP_User($user_id);
		$billing_address = $customer->getBillingAddress();
		if($billing_address) {
			add_user_meta( $user_id, 'billing_first_name', $billing_address->getFirstName() );
			add_user_meta( $user_id, 'billing_last_name',  $billing_address->getLastName() );
			add_user_meta( $user_id, 'billing_company',    $billing_address->getCompany() );
			add_user_meta( $user_id, 'billing_address_1',  $billing_address->getAddress1() );
			add_user_meta( $user_id, 'billing_address_2',  $billing_address->getAddress2() );
			add_user_meta( $user_id, 'billing_city',       $billing_address->getCity() );
			add_user_meta( $user_id, 'billing_state',      $billing_address->getStateProvinceId() );
			add_user_meta( $user_id, 'billing_country',    $billing_address->getCountryId() );
			add_user_meta( $user_id, 'billing_postcode',   $billing_address->getZipPostalCode() );
			add_user_meta( $user_id, 'billing_email',      $billing_address->getEmail() );
			add_user_meta( $user_id, 'billing_phone',      $billing_address->getPhoneNumber() );
		}

		$shipping_address = $customer->getShippingAddress();
		if($shipping_address) {
			add_user_meta( $user_id, 'shipping_first_name', $shipping_address->getFirstName() );
			add_user_meta( $user_id, 'shipping_last_name',  $shipping_address->getLastName() );
			add_user_meta( $user_id, 'shipping_company',    $shipping_address->getCompany() );
			add_user_meta( $user_id, 'shipping_address_1',  $shipping_address->getAddress1() );
			add_user_meta( $user_id, 'shipping_address_2',  $shipping_address->getAddress2() );
			add_user_meta( $user_id, 'shipping_city',       $shipping_address->getCity() );
			add_user_meta( $user_id, 'shipping_state',      $shipping_address->getStateProvinceId() );
			add_user_meta( $user_id, 'shipping_country',    $shipping_address->getCountryId() );
			add_user_meta( $user_id, 'shipping_postcode',   $shipping_address->getZipPostalCode() );
			add_user_meta( $user_id, 'shipping_email',      $shipping_address->getEmail() );
			add_user_meta( $user_id, 'shipping_phone',      $shipping_address->getPhoneNumber() );
		}
        
        add_user_meta( $user_id, 'affiliate_id', $customer->getAffiliateId() );
        //add_user_meta( $user_id, 'first_name', $customer->getFirstName() );
        //add_user_meta( $user_id, 'last_name', $customer->getLastName() );

		$user->set_role('customer');
		update_user_meta( $user_id, 'show_admin_bar_front', false );
		$customer->setCustomerId($user_id);
        
        return $customer->toArray();
	} else {
		$wp_customer = new \SocialBugCRM\Classes\Customer();
        
        if($username_exists) {
			$user = get_user_by('login', $customer->getUserName());
		}
        
        $wp_customer->extractDataFromWPUser($user);
        
        return $wp_customer->toArray();
	}
    
    return null;
}

function GetCustomerByEmail(WP_REST_Request $request) {
	$email = $request->get_param('email');
	$user = get_user_by_email($email);
    
    if($user->ID == 0) {return null;}
    
    $customer = new \SocialBugCRM\Classes\Customer();
	$customer->extractDataFromWPUser($user);
    
    return $customer->toArray();
}

function IsEmail($param, $request, $key) {
	return is_email($param);
}

function LoadHtmlOnPage() {
    $options = get_option('socialbugcrm');

    If ( $options == false )
        return;
    
    $uniqueId = '';
    $appendHtml = $options['AppendHtml'];

    $current_user = wp_get_current_user();
    if ($current_user->exists()){
        $salt = $options['Salt'];
        $userId = $current_user->ID;
        $str = $userId.$salt;
        $uniqueId = $userId.'~'.md5($str);
    }

    $appendHtml = str_replace("%customerId%", $uniqueId, $appendHtml);

    echo stripslashes($appendHtml)."\r\n";
}

?>

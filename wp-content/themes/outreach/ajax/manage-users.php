<?php

function signup_auth0_user()
{
	global $wpdb;
	$user = wp_insert_user(array(
		'user_login' => $_POST['email'],
		'user_email' => $_POST['email'],
		'user_pass' => 'limone@123',
		'display_name' => $_POST['user']['name']
	));
	
	if( is_wp_error( $user ) ) {
		$error = $user->get_error_message();
	} 
	else
	{
		// Insert into accounts table
		$date = date('Y-m-d H:i:s');
		$password = md5('limone@123');
		$name = $_POST['user']['name'];
		$query = "insert into account(id, type_id, email, fname, password, confirmed,date_created) values ('$user', '5', '$_POST[email]', '$name', '$password', '1', '$date')";
		$wpdb->query($query);
		
		// Save complete auth data
		add_user_meta($user, 'auth0_data', json_encode($_POST['user']));
	}
	
	// Login user
	$user = wp_signon(array(
		'user_login' => $_POST['email'],
		'user_password' => 'limone@123'
	));
	
	echo $_POST['user']['name'];
	wp_die();
}
add_action('wp_ajax_signup_auth0_user', 'signup_auth0_user');
add_action('wp_ajax_nopriv_signup_auth0_user', 'signup_auth0_user');

function login_auth0_user()
{
	global $wpdb;
	if( !email_exists($_POST['email']) )
	{
		// Create new user
		$user = wp_insert_user(array(
			'user_login' => $_POST['email'],
			'user_email' => $_POST['email'],
			'user_pass' => 'limone@123',
			'display_name' => $_POST['user']['name']
		));

		// Insert into accounts table
		$date = date('Y-m-d H:i:s');
		$password = md5('limone@123');
		$name = $_POST['user']['name'];
		$query = "insert into account(id, type_id, email, fname, password, confirmed,date_created) values ('$user', '5', '$_POST[email]', '$name', '$password', '1', '$date')";
		$wpdb->query($query);
		
		// Save complete auth data
		add_user_meta($user, 'auth0_data', json_encode($_POST['user']));
	}

	$user = wp_signon(array(
		'user_login' => $_POST['email'],
		'user_password' => 'limone@123'
	));
	
	if( is_wp_error( $user ) ) {
		echo 0;
		$error = $user->get_error_message();
	} else {
		$query = "select * from `account` where email='".$_POST['email']."'";
		echo json_encode( (array) $wpdb->get_row($query) );
	}
	
	wp_die();
}
add_action('wp_ajax_login_auth0_user', 'login_auth0_user');
add_action('wp_ajax_nopriv_login_auth0_user', 'login_auth0_user');
?>
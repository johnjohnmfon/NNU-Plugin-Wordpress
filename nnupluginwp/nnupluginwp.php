<?php

/**

 * Plugin Name: NNU Plugin wordpress
 * Plugin URI: https://example.com
 * Description: This plugin gives you NNU on wordpress
 * Version: 1.0.0
 * Author: John Mfon John
 * Author URI: https://example.com
 * License: Private

 */


//Add bonus on registration hook
add_action( 'user_register', 'user_signup_bonus', 10, 1 );
function user_signup_bonus( $user_id ) {

      add_user_earning($user_id, 500);
    
}

//Add user earning on login hook
add_action('wp_login', 'user_login_bonus', 10, 2);
function user_login_bonus($user_login, $user){
  
  $user_id = $user->ID;
  $user_login_bonus_last_added_ts = get_user_meta($user_id, 'user_login_bonus_last_added_ts', true);

    if(time() > $user_login_bonus_last_added_ts + 300){

      add_user_earning($user_id, 50);
      add_user_meta($user_id,'user_login_bonus_date_time_paid_ts',time() );
      update_user_meta($user_id,'user_login_bonus_last_added_ts',time() );

    }


}

add_action('wp', 'user_visits_post_bonus');
function user_visits_post_bonus($post) {
     global $post, $current_user;
     $current_user = wp_get_current_user();
     $user_id = $current_user->ID;

     if (empty($post_id) ) {
        
        $post_id = $post->ID;    
     }
     
     
     $post_visited_by_user = get_user_meta($user_id, 'post_visited', $post_id);


     if (empty($post_visited_by_user) && is_user_logged_in()) {
      
      add_user_earning($user_id, 3);
      add_user_meta($user_id,'post_visited', $post_id);
      add_post_meta($post_id,'post_visitors', $user_id);
       
     }

      $count = get_post_meta($post_id, 'post_visits_count', true);
      if( empty($count) ) {
        $count = 0;
      }
      $count++;
      // update count
      update_post_meta($post_id, 'post_visits_count', $count);


}


//Add user earning based on actions
function add_user_earning($user_id,$amount_to_add){

   $user_wallet_balance = get_user_meta($user_id, 'user_wallet_balance',true);

    if (!is_numeric($user_wallet_balance) and !is_int($user_wallet_balance)){
      $user_wallet_balance = 0;
    }

    $new_balance  = $user_wallet_balance + $amount_to_add; 
    $new_balance  = number_format($new_balance,0);
   
    update_user_meta($user_id, 'user_wallet_balance', $new_balance);

}




add_filter('user_contactmethods','my_new_contactmethods',10,1);
function my_new_contactmethods( $contactmethods ) {

      //User login bonus
      $contactmethods['user_login_bonus'] = 'User Login Bonus'; 

      //User wallet balance
      $contactmethods['user_wallet_balance'] = 'User Wallet Balance';   

      //add Default Image url
      if  ((current_user_can('manage_options')))
        {
   

       }

     return $contactmethods;
}





?>
<?php

include 'facebook.php';

/**
 * FB static wrapper
 *
 * @package    
 * @author     Ariful Islam <gplus.to/ariful>
 * @version    1.0
 * @license    http://mit-license.org/ MIT License
 * @link
 * This is a Facebook wrapper class to access the Facbook functions statically
 *
 * Contains some common functions
 * 
 */

class FB{	
	public static $appId='';
	public static $appSecret='';
	public static $access_token='';
	public static $inst=NULL;
	
	function __construct()
	{
		echo 'No need to create instance of this class, static functions only class.';
	}
	
	public static function inst(){
		if(self::$inst==NULL)
			self::$inst=new Facebook(array(
							'appId' => self::$appId,
							'secret' => self::$appSecret,
							'cookie' => true,
						));
		return self::$inst;
	}
	
	public static function logged(){
		return self::inst()->getUser();
	}	
	
	
	public static function login_url(){
		$param['req_perms']=$param['scope']= "publish_actions";
		return self::inst()->getLoginUrl($param);		
	}	
	
	public static function validate_login() {
		$data = self::inst()->api('/me');
		if(isset($data['email'])) $email=$data['email']; else return false;
		$merchant=Merchant::where('fb_email', '=', $email)->first();
		if($merchant){
			Auth::login($merchant->id);
			if($merchant->fb_uid==''){
				$merchant->fb_uid=$data['id'];
				$merchant->fb_access_token=self::inst()->getAccessToken();
				$merchant->fb_first_name=$data['first_name'];
				$merchant->fb_last_name=$data['last_name'];
				$merchant->fb_gender=$data['gender'];
				$merchant->fb_timezone=$data['timezone'];
				$merchant->save();
			}	
			return true;		
		}
		return false;
	}
	
	public static function list_pages() {
		$data = self::inst()->api('/me/accounts');
		//echo '<pre>';
		//print_r($data);
		return $data['data'];
	}
	
	
	public static function post($hero) {	
		
		$access_token=self::inst()->getAccessToken();
		
		$attachment = array(
				'access_token' => $access_token, 
				'message' => 'You are '.$hero['name'],
				//'name' => 'name',
				'link' => $hero['wikipedia'],
				'caption' => 'Which muslim hero you are?',
				'description' => 'Islamic conquests began with the prophet Muhammad(pbuh). He established a new unified polity in the Arabian Peninsula which under the subsequent Rashidun (The Rightly Guided Caliphs) and Umayyad Caliphates saw a century of rapid expansion of Muslim power. The history of muslim conquests is so vast that hundreds of books even can\'t cover it. So are the histories of muslim conquerors, heros.',
				'picture' => 'http://www.codelixir.com/muslim-hero/'.$hero['image']
		);
		try {
			$response=self::inst()->api("/me/feed", 'POST', $attachment);
			$post_id=$response['id'];
			return $response['id'];
		} catch (Exception $e) {
			return 'Error: '.$e->getMessage();
		}		
	}	
	
	public static function extend_token() {
		return file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.self::$appId.'&client_secret='.self::$appSecret.'&grant_type=fb_exchange_token&fb_exchange_token='.self::$access_token);
	}	
	
	public static function dump(){
		$access_token=self::inst()->getAccessToken();
		$data = self::inst()->api('/me');
		echo '<pre>';
		echo 'access_token: '.$access_token;
		print_r($data);
	}
	
	public function __toString()
	{
		return 'Facebook wrapper class for static access. Dont create instance, use static access.';
	}
	
	public function __call($method, $parameters)
	{
		return call_user_func_array(array(self::inst(), $method), $parameters);
	}
	
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(self::inst(), $method), $parameters);
	}	
}
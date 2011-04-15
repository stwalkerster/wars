<?php
/**************************************************************************
*                     Wikipedia Account Request System                    *
***************************************************************************
*                                                                         *
* Conceptualised by Incubez (author: X!) and ACC (author: SQL and others) *
*                                                                         *
* Please refer to /LICENCE for more info.                                 *
*                                                                         *
**************************************************************************/

// check that this code is being called from a valid entry point. 
if(!defined("WARS"))
	die("Invalid code entry point!");
	
class HttpClient
{	
	private $curl;
	private $cookiejar;
	
	private static $singleton;
	protected function __construct()
	{
		global $toolUserAgent;
		
		$this->curl = curl_init();		
		
		$this->cookiejar = '/tmp/http.cookies.'.dechex(rand(0,99999999)).'.dat';
		touch($this->cookiejar);
		chmod($this->cookiejar,0600);
		curl_setopt($this->curl,CURLOPT_COOKIEJAR,$this->cookiejar);
		curl_setopt($this->curl,CURLOPT_COOKIEFILE,$this->cookiejar);
		curl_setopt($this->curl,CURLOPT_USERAGENT,$toolUserAgent);
	}
	
	public static function getInstance()
	{
		if(!is_object(self::$singleton))
			self::$singleton = new HttpClient();
		return self::$singleton;
	}
	
	/*
	 * Sends a GET request for $url.
	 */
	public function get ($url) {
		curl_setopt($this->curl,CURLOPT_URL,$url);
		curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($this->curl,CURLOPT_MAXREDIRS,10);
		curl_setopt($this->curl,CURLOPT_HEADER,false);
		curl_setopt($this->curl,CURLOPT_HTTPGET,true);
		curl_setopt($this->curl,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($this->curl,CURLOPT_CONNECTTIMEOUT,15);
		curl_setopt($this->curl,CURLOPT_TIMEOUT,40);
		return curl_exec($this->curl);
	}
	
	/*
	 * Sends a POST request to $url.
	 * $data is the post data.
	 */
	public function post ($url,$data) {
		curl_setopt($this->curl,CURLOPT_URL,$url);
		curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($this->curl,CURLOPT_MAXREDIRS,10);
		curl_setopt($this->curl,CURLOPT_HEADER,false);
		curl_setopt($this->curl,CURLOPT_POST,true);
		curl_setopt($this->ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($this->curl,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($this->curl,CURLOPT_CONNECTTIMEOUT,15);
		curl_setopt($this->curl,CURLOPT_TIMEOUT,40);
		curl_setopt($this->curl,CURLOPT_HTTPHEADER,array('Expect:'));
		return curl_exec($this->curl);
	}
	
	public function __destroy () {
		curl_close($this->curl);
		@unlink($this->cookiejar);
	}
}
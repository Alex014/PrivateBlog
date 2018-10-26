<?php
namespace darkblog\lib;
/*
					COPYRIGHT

Copyright 2007 Sergio Vaccaro <sergio@inservibile.org>
Modified by Neo

This file is part of JSON-RPC PHP.

JSON-RPC PHP is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

JSON-RPC PHP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JSON-RPC PHP; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The object of this class are generic jsonRPC 1.0 clients
 * http://json-rpc.org/wiki/specification
 *
 * @author sergio <jsonrpcphp@inservibile.org>
 * modified by Neo
 */
class jsonRPCClient {
  
  public static $error;
  public static $output;
	
	/**
	 * Debug state
	 *
	 * @var boolean
	 */
	private $debug;
	
	/**
	 * The server URL
	 *
	 * @var string
	 */
	private $url;
	/**
	 * The request id
	 *
	 * @var integer
	 */
	private $id;
	/**
	 * If true, notifications are performed instead of requests
	 *
	 * @var boolean
	 */
	private $notification = false;
	
	/**
	 * Takes the connection parameters
	 *
	 * @param string $url
	 * @param boolean $debug
	 */
	public function __construct($url,$debug = false) {
		// server URL
		$this->url = $url;
		// proxy
		empty($proxy) ? $this->proxy = '' : $this->proxy = $proxy;
		// debug state
		empty($debug) ? $this->debug = false : $this->debug = true;
		// message id
		$this->id = rand(1, 99999);
	}
	
	/**
	 * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
	 *
	 * @param boolean $notification
	 */
	public function setRPCNotification($notification) {
		empty($notification) ?
							$this->notification = false
							:
							$this->notification = true;
	}
	
	/**
	 * Performs a jsonRCP request and gets the results as an array
	 *
	 * @param string $method
	 * @param array $params
	 * @return array
	 */
	public function __call($method,$params) {
		
		// check
		if (!is_scalar($method)) {
			throw new \Exception('Method name has no scalar value');
		}
		
		// check
		if (is_array($params)) {
			// no keys
			$params = array_values($params);
		} else {
			throw new \Exception('Params must be given as array');
		}
		
		// sets notification or request task
		if ($this->notification) {
			$currentId = NULL;
		} else {
			$currentId = $this->id;
		}
		
		// prepares the request
		$request = array(
                    'jsonrpc' => '1.0',
                    'method' => $method,
                    'params' => $params,
                    'id' => $currentId
                );
                
		// debug output
                if($this->debug) {
                    self::$output[] = '';
                    self::$output[] = '***** Request *****';
                    self::$output[] = print_r($request, true);
                    self::$output[] = '***** End Of request *****';
                }
                
		$request = json_encode($request, JSON_UNESCAPED_UNICODE );
                
		// performs the HTTP POST
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json;charset=\"utf-8\"'));
		curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    $responce = curl_exec($ch);

    if(!is_string($responce)) {
      throw new \Exception("Can't connect to: ".$this->url.' '.curl_error ( $ch ));
    }
    else {
        self::$output[] = '';
        self::$output[] = '***** Responce *****';
        self::$output[] = $responce;
        self::$output[] = '***** End Of responce *****';
    }
    
		$responce = json_decode($responce,true);
		//var_dump($responce);
		curl_close($ch);
		
		// final checks and return
		if (!$this->notification) {
			// check
			if ($responce['id'] != $currentId) {
				throw new \Exception('Incorrect response id (request id: '.$currentId.', response id: '.$responce['id'].')');
			}
			if (!is_null($responce['error'])) {
        jsonRPCClient::$error = $responce['error'];
				throw new \Exception('Request error: '.$responce['error']['message']);
			}
			
			return $responce['result'];
			
		} else {
			return true;
		}
	}
        
        public static function printOutout() {
            echo implode('<br/>', self::$output);
        }
}
?>
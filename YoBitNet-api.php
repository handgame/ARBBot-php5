<?php
/**
 * API-call related functions
 *
 * @author Marinu, Smart Edition
 */
 

 
 
class YoBitNetAPI {

    const DIRECTION_BUY = 'buy';
    const DIRECTION_SELL = 'sell';
    protected $public_api = 'https://yobit.net/api/2/';
    
    protected $api_key;
    protected $api_secret;
    protected $noonce;
    protected $RETRY_FLAG = false;
    
    public function __construct($api_key, $api_secret, $base_noonce = false) {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        if($base_noonce === false) {
            // Try 1?
            $this->noonce = time();
        } else {
            $this->noonce = $base_noonce;
        }
    }
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    /**
     * Get the noonce
     * @global type $sql_conx
     * @return type 
     */
    protected function getnoonce() {
        //$this->noonce++;
        //return array(0.05, $this->noonce);
        $n = intval(trim(file_get_contents('./nonce.txt')))+1;
       
	  // if ($n < 4500000) {} else {
		 file_put_contents('./nonce.txt',$n);
	  // }
	   
	   /*
		//Дополнительная проверка
		$nn = intval(trim(file_get_contents('./nonce.txt')))+1;
		if ($nn < 4500000) {
			file_put_contents('./nonce.txt',$n);
			
			$nn = intval(trim(file_get_contents('./nonce.txt')))+1;
			if ($nn < 4500000) {
				file_put_contents('./nonce.txt',$n);
				
				$nn = intval(trim(file_get_contents('./nonce.txt')))+1;
				if ($nn < 4500000) {
					file_put_contents('./nonce.txt',$n);
				}				
			}
		}
		*/
        
			$fp = fopen('noncelog.txt', 'a');
			$text = "$n\r\n";
			fwrite($fp, $text); // Запись в файл
			fclose($fp); //Закрытие файл
			if ($n < 5 or $n == "") { 
			
			$fp = fopen('noncelog.txt', 'a');
			$text = "Обрыв: $n\r\n";
			fwrite($fp, $text); // Запись в файл
			fclose($fp); //Закрытие файл
			exit();
			}
        return array(0.05, $n);
    }
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    /**
     * Call the API
     * @staticvar null $ch
     * @param type $method
     * @param type $req
     * @return type
     * @throws Exception 
     */
    public function apiQuery($method, $req = array()) {
        $req['method'] = $method;
        $mt = $this->getnoonce();
        $req['nonce'] = $mt[1];
       
        // generate the POST data string
        $post_data = http_build_query($req, '', '&');
 
        // Generate the keyed hash value to post
        $sign = hash_hmac("sha512", $post_data, $this->api_secret);
 
        // Add to the headers
        $headers = array(
                'Sign: '.$sign,
                'Key: '.$this->api_key,
        );
 
        // Create a CURL Handler for use
        $ch = null;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
 

 
 
if ($GLOBALS['global_proxy'] != "") {
curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['global_proxy']); //ip
curl_setopt($ch, CURLOPT_PROXYPORT, $GLOBALS['global_port']); //порт
}





        curl_setopt($ch, CURLOPT_URL, 'https://yobit.net/tapi/');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_PROXYUSERPWD,"".$GLOBALS['global_login'].":".$GLOBALS['global_password']."");
        curl_setopt($ch, CURLOPT_COOKIE, $GLOBALS['global_cookie']);
		curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['global_ua']);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_ENCODING , gzip);

//--new
//        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
//        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
//curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
//curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
//--end new
 
        // Send API Request
        $res = curl_exec($ch); print "@ $res @";       
        //print $res;
        // Check for failure & Clean-up curl handler
        if($res === false) {
            $e = curl_error($ch);
            curl_close($ch);
            throw new YoBitNetAPIFailureException('Could not get reply: '.$e);
        } else {
            curl_close($ch);
        }
        
        // Decode the JSON
        $result = json_decode($res, true);
        // is it valid JSON?
        if(!$result) {
            throw new YoBitNetAPIInvalidJSONException('Invalid data received, please make sure connection is working and requested API exists');
			
        }
        
        // Recover from an incorrect noonce
        if(isset($result['error']) === true) {
            if(strpos($result['error'], 'nonce') > -1 && $this->RETRY_FLAG === false) {
                $matches = array();
                $k = preg_match('/:([0-9])+,/', $result['error'], $matches);
                $this->RETRY_FLAG = true;
                trigger_error("Nonce we sent ({$this->noonce}) is invalid, retrying request with server returned nonce: ({$matches[1]})!");
                $this->noonce = $matches[1];
                return $this->apiQuery($method, $req);
            } else {
                throw new YoBitNetAPIErrorException('API Error Message: '.$result['error'].". Response: ".print_r($result, true));
            }
        }
        // Cool -> Return
        $this->RETRY_FLAG = false;
        return $result;
    }
    
    /**
     * Retrieve some JSON
     * @param type $URL
     * @return type
     */
    protected function retrieveJSON($URL) {
        $opts = array('http' =>
            array(
                'method'  => 'GET',
                'timeout' => 10 
            )
        );


 

        //$context  = stream_context_create($opts);
        //$feed = file_get_contents($URL, false, $context);

        $ch = curl_init($URL); //страница данных
        //curl_setopt  ($ch, CURLOPT_HEADER, true); //включаем в вывод заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //вывод получаемых данных не в браузер, а в переменную для последующей обработки

    $headers = array( 
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
  
    );
	
	
	
	 
		
if ($GLOBALS['global_proxy'] != "") {
curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['global_proxy']); //ip
curl_setopt($ch, CURLOPT_PROXYPORT, $GLOBALS['global_port']); //порт
}







    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,100); 
curl_setopt($ch, CURLOPT_TIMEOUT, 100); //timeout in seconds
curl_setopt($ch, CURLOPT_COOKIE, $GLOBALS['global_cookie']);
curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['global_ua']);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_PROXYUSERPWD,"".$GLOBALS['global_login'].":".$GLOBALS['global_password']."");

print curl_error($ch);

    while ($i_debag < 5) { 
     
        $i_debag++;
        $info_curl = curl_exec($ch); // выполнение установленных запросов
        //print $info_curl;
        if ($info_curl == "" or strlen($info_curl) < 100) {
            sleep(5);
            //loge(date("H:i:s")." Главная парсера: данные не получены".$access[0]." $i_debag: $page:$page_download:$debugging:$name_artist:$page_artist");
        } else {
            $i_debag = 55; 
        }
    }




        $json = json_decode($info_curl, true);
        return $json;
    }

















    
    /**
     * Place an order
     * @param type $amount
     * @param type $pair
     * @param type $direction
     * @param type $price
     * @return type 
     */
    public function makeOrder($amount, $pair, $direction, $price) {
        if($direction == self::DIRECTION_BUY || $direction == self::DIRECTION_SELL) {
            $data = $this->apiQuery("Trade"
                    ,array(
                        'pair' => $pair,
                        'type' => $direction,
                        'rate' => $price,
                        'amount' => $amount
                    )
            );
            return $data; 
        } else {
            throw new YoBitNetAPIInvalidParameterException('Expected constant from '.__CLASS__.'::DIRECTION_BUY or '.__CLASS__.'::DIRECTION_SELL. Found: '.$direction);
        }
    }
    
    /**
     * Check an order that is complete (non-active)
     * @param type $orderID
     * @return type
     * @throws Exception 
     */
    public function checkPastOrder($orderID) {
        $data = $this->apiQuery("OrderList"
                ,array(
                    'from_id' => $orderID,
                    'to_id' => $orderID,
                    /*'count' => 15,*/
                    'active' => 0
                ));
        if($data['success'] == "0") {
            throw new YoBitNetAPIErrorException("Error: ".$data['error']);
        } else {
            return($data);
        }
    }
    
    /**
     * Public API: Retrieve the Fee for a currency pair
     * @param string $pair
     * @return array 
     */
    public function getPairFee($pair) {
        return $this->retrieveJSON($this->public_api.$pair."/fee");
    }
    
    /**
     * Public API: Retrieve the Ticker for a currency pair
     * @param string $pair
     * @return array 
     */
    public function getPairTicker($pair) {
        return $this->retrieveJSON($this->public_api.$pair."/ticker");
    }
    
    /**
     * Public API: Retrieve the Trades for a currency pair
     * @param string $pair
     * @return array 
     */
    public function getPairTrades($pair) {
        return $this->retrieveJSON($this->public_api.$pair."/trades");
    }
    
    /**
     * Public API: Retrieve the Depth for a currency pair
     * @param string $pair
     * @return array 
     */
    public function getPairDepth($pair) {
        return $this->retrieveJSON($this->public_api.$pair."/depth");
    }
}

/**
 * Exceptions
 */
class YoBitNetAPIException extends Exception {}
class YoBitNetAPIFailureException extends YoBitNetAPIException {}
class YoBitNetAPIInvalidJSONException extends YoBitNetAPIException {}
class YoBitNetAPIErrorException extends YoBitNetAPIException {}
class YoBitNetAPIInvalidParameterException extends YoBitNetAPIException {}



?>

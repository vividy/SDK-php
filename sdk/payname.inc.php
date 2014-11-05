<?php 

define('PAYNAME_DEBUG', true);

require_once __DIR__ . '/payname_model.inc.php';
require_once __DIR__ . '/payname_error.inc.php';

/**
 * Class Payname($ID, $secret)
 */

Class Payname {

	/**
     * Base URL
     */
	const URL = "https://api.payname.fr/";

	/**
     * Identification du compte
     */
	private $ID;
	private $secret;

	/**
     * Modèle de stockage des Tokens
     */
	private $model;

	/**
     * Constructor
     */
	public function __construct($ID, $secret) {
        $this->Models = new PaynameModel();
        
        $this->ID = $ID;
        $this->secret = $secret;
        
        return true;
    }

    private function token() {
    	if ($access = $this->Models->get()) {
        	return $access;
        } elseif ($refresh = $this->Models->get("refresh")) {
        	$option = array(
	            'http' => array(
	                'method' => 'POST',
	                'header' => 'Content-Type: application/x-www-form-urlencoded',
	                'content' => 'ID=' . $this->ID . '&token=' . $refresh,
	            ) ,
	        );
	        if( $html = file_get_contents(self::URL . "auth/refresh_token", null, stream_context_create($option))) {
		        if($res = json_decode($html)) {
		        	if ($res->success) {
		        		if($this->Models->set($res->access_token, $res->access_validity, "access")) {
		        			return $res->access_token;
		        		} else {
		        			PaynameError::error("1:4");
		        			return false;
		        		}
			        } else {
			        	// @TODO : si le success est à FALSE regarder l'erreur et agir en conséquence.
			        	PaynameError::error("1:3", $res);
			        	return false;
			        }
		        } else {
		        	PaynameError::error("1:2");
					return false;
		        }
		    } else {
		    	PaynameError::error("1:1");
				return false;
		    }
        } else {
        	$option = array(
	            'http' => array(
	                'method' => 'POST',
	                'header' => 'Content-Type: application/x-www-form-urlencoded',
	                'content' => 'ID=' . $this->ID . '&secret=' . $this->secret,
	            ) ,
	        );
	        if( $html = file_get_contents(self::URL . "auth/token", null, stream_context_create($option))) {
		        if($res = json_decode($html)) {
		        	if ($res->success) {
		        		if($access_token = $this->Models->get()) {
		        			return $access_token;
		        		} else {
		        			PaynameError::error("2:5");
		        			return false;
		        		}
			        } else {
			        	// @TODO : si le success est à FALSE regarder l'erreur et agir en conséquence.
			        	PaynameError::error("2:3", $res);
			        	return false;
			        }
		        } else {
		        	PaynameError::error("2:2");
					return false;
		        }
		    } else {
		    	PaynameError::error("2:1");
				return false;
		    }
        }
    }

    public function saveToken() {
    	$access_token = isset($_POST['access_token']) ? $_POST['access_token'] : false;
    	$access_validity = isset($_POST['access_validity']) ? $_POST['access_validity'] : false;
    	$refresh_token = isset($_POST['refresh_token']) ? $_POST['refresh_token'] : false;
    	$refresh_validity = isset($_POST['refresh_validity']) ? $_POST['refresh_validity'] : false;

		if(
    		$access_token && 
    		strlen($access_token) == 64 && 
    		$refresh_token &&  
    		strlen($refresh_token) == 64 && 
    		$access_validity && $refresh_validity
    	) {
    		if(!$this->Models->set($access_token, $access_validity, "access")) {
    			PaynameError::error("3:4");
				return false;
    		}
    		if(!$this->Models->set($refresh_token, $refresh_validity, "refresh")) {
    			PaynameError::error("3:4");
				return false;
    		}
    		return true;
    	} else {
    		PaynameError::error("3:6");
			return false;
    	}
    }

    public function createUser($data) {
    	// POST data arguments
        $fields = ["email","phone","first_name","last_name"];
    	$content = "";
    	foreach ($fields as $field) {
    		if(isset($data[$field])) {
    			$content .= "&".$field."=".$data[$field];
    		}
    	}
    	$content = substr($content, 1);

        // HEADERS
        $header = 'Authorization: Bearer ' . $this->token()."\r\n";
        $header .= 'Content-Type: application/x-www-form-urlencoded';

    	$option = array(
            'http' => array(
                'method' => 'POST',
                'header' => $header,
                'content' => $content,
            ) ,
        );
        $res = json_decode(file_get_contents(self::URL . "user", null, stream_context_create($option)));
        if ($res->success) {
        	return $res->data;
        } else {
        	if(PAYNAME_DEBUG) {
        		var_dump($res);
        	}
        	return false;
        }
    } 

    public function getUsers() {
    	$option = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $this->token(),
            ) ,
        );
        $res = json_decode(file_get_contents(self::URL . "user", null, stream_context_create($option)));
        if ($res->success) {
        	return $res->data;
        } else {
        	if(PAYNAME_DEBUG) {
        		var_dump($res);
        	}
        	return false;
        }
    } 
}
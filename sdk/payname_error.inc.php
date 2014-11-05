<?php 

/**
 * Class PaynameError()
 */

Class PaynameError {

	/**
     * Context
     */
	const C1 = "Récupération de l'access Token via auth/refresh_token";
	const C2 = "Récupération de l'access Token via auth/token";
	const C3 = "Enregistrement des tokens";

	/**
     * Erreur
     */
	const E1 = "Echec de l'appel HTTP";
	const E2 = "Impossible de parser le JSON";
	const E3 = "Mauvaise réponse serveur";
	const E4 = "Impossible d'enregistrer le TOKEN";
	const E5 = "Impossible de récupérer le TOKEN";
	const E6 = "Mauvais paramètres";

	public static function error($err, $info=false) {
		list($context, $error) = explode(":", $err);
		$msg = "";
		if($context && defined('self::C'.$context)) $msg .= constant('self::C'.$context).' - ';
		if($error && defined('self::E'.$error)) $msg .= constant('self::E'.$error);
		if($info) {
			if(is_object($info) || is_array($info)) {
				$msg .= ': '.json_encode($info);
			} else {
				$msg .= ': '.$info;
			}
		}
    	throw new Exception($msg, str_replace(':', '.', $err));
		return false;
    }
}
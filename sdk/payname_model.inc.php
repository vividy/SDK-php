<?php

/**
 * Class PaynameModel()
 *
 * C'est ici qu'on stock les tokens ( access & refresh nécessaire au bon fonctionnement de l'API)
 * Par défaut, on stock dans un fichier.
 * Si vous souhaitez stocker ailleurs ( MongoDB, MySQL, SqlLite, etc... ) vous pouvez modifier ce fichier en gardant les fonctions actuelles.
 */
Class PaynameModel {

	/**
	 * Constructor
	 * Test si le fichier existe et est accessible en lecture et en écriture.
	 * Si le fichier n'existe pas il le crée
	 */
	public function __construct() {
		if ($handle = fopen(PAYNAME_TEMP_FILE, "a+")) {
			fclose($handle);
			return true;
		} else {
			throw new Exception("PaynameModel - construct: Le fichier de sockage des token n'est pas disponible en lecture et en écriture !", 1);
			return false;
		}
	}

	/**
	 * Function set
	 * écriture du token dans le fichier
	 *
	 * @param string(64) Token
	 * @param Timestamp Validity
	 * @param enum("access","refresh") (Défaut: "access") Type du token
	 * @return boolean Si la création à réussi ou non.
	 */

	public function set($token, $validity, $type = "access") {
		if($current = file_get_contents(PAYNAME_TEMP_FILE)) {			
		} else {
			$current = "";
		}
		$current .= "$type,$token,$validity\n";
		if(file_put_contents(PAYNAME_TEMP_FILE, $current)) {
			return true;
		} else {
			throw new Exception("PaynameModel - set: Impossible d'écrire dans le fichier de sockage des tokens", 1);
			return false;
		}		
	}

	/**
	 * Function get
	 * récupération d'un token valide selon le type demandé
	 *
	 * @param enum("access","refresh") (Défaut: "access") Type du token
	 * @return boolean Si la création à réussi ou non.
	 */

	public function get($type = "access") {
		$row = 1;
		if (($handle = fopen(PAYNAME_TEMP_FILE, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				if($data[0] == $type && $data[2] > time()) {
					return $data[1];
				}
			}
			fclose($handle);
			return false;
		} else {
			throw new Exception("PaynameModel - get: Impossible de lire le fichier de sockage des tokens", 1);
			return false;
		}		
	}

	/**
	 * Function erase
	 * supprime le fichier
	 * Dans le cas ou on utilise un ID & secret pour s'authentifier c'est que les tokens son invlide 
	 * ou qu'on change d'environnement.
	 *
	 * @return boolean Si la suppression à réussi ou non.
	 */

	public function erase() {
		return unlink(PAYNAME_TEMP_FILE);
	}
}
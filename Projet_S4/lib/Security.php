<?php

// https://webinfo.iutmontp.univ-montp2.fr/my/
class Security
{
	private static $seed = 'hKGjzdNYxS';

	static public function getSeed() {
   		return self::$seed;
   	}

	static function chiffrer($mdp) {
	  return hash('sha256', self::getSeed() . $mdp);
	}
	static function generateRandomHex() {
	  // 32 hexa -> 16 octets
	  $numbytes = 16;
	  $bytes = openssl_random_pseudo_bytes($numbytes); 
	  return bin2hex($bytes);
	}


}
?>

<?php
class Conf {

  static private $databases = array(
    //hostname correspond au domaine du réseau local de l'entreprise
    'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
    //database correspond au nom de la base de donnée du site 
    //cette base de donnée étant importée elle s'appellera workflow
    'database' => 'bourdesj',
    //login et password sont les identifiants de votre base de données
    'login' => 'bourdesj',

    'password' => 'Unicorn'
  );

  static private $debug = True; 
 
  static public function get($var) {
    //en PHP l'indice d'un tableau n'est pas forcement un chiffre.
    return self::$databases[$var];
  }
    
  static public function getDebug() {
    return self::$debug;
  }
   
}
?>


<?php
require_once File::build_path(array("config", "Conf.php"));

class Model {

  static private $pdo;

  static public function getPDO(){return self::$pdo;}

  static public function Init() { 
    $hostname = Conf::get('hostname');
    $database_name = Conf::get('database');
    $login = Conf::get('login');
    $password = Conf::get('password');
    try{
      self::$pdo = new PDO("mysql:host=$hostname;dbname=$database_name", $login, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      if (Conf::getDebug()) {
        echo $e->getMessage(); // affiche un message d'erreur
      } else {
        echo 'Une erreur est survenue <a href=""> retour a la page d\'accueil </a>';
      }
      die();
    }
  }

  static public function selectAll(){
    $model = 'Model' . ucfirst(static::$object);

    try{
      $pdo = Model::getPDO();
      $rep = $pdo->query("SELECT * FROM " . ucfirst(static::$object));
      $rep->setFetchMode(PDO::FETCH_CLASS, $model);
      $tab_obj = $rep->fetchAll();

    } catch(PDOException $e) {
      echo $e->getMessage(); // affiche un message d'erreur
      die();
    }
    return $tab_obj;
  }

  static public function select($pk_val) {
    $model = 'Model' . ucfirst(static::$object);

    if(is_array($pk_val)){
      $values = array();
      $where = '';
      foreach ($pk_val as $key => $value) {
        $where = $where . $key . " = :" . $key . " AND ";
        $values = [$key => $value] + $values;
      }
      $where = rtrim($where, "AND ");
    }
    else {
      $values = array("pk_value" => $pk_val);
      $where = static::$pk ." = :pk_value";
    }

    $sql = "SELECT * from ". ucfirst(static::$object) ." WHERE ". $where;
    $req_prep = Model::getPDO()->prepare($sql);

    try {
      $req_prep->execute($values);
      $req_prep->setFetchMode(PDO::FETCH_CLASS, $model);
      $tab = $req_prep->fetchAll(); 
    } catch(PDOException $e){
      echo $e->getMessage();
      return null;
    }
    // si vide (pas de resultat), null renvoyé
    if (empty($tab))
        return null;
    return $tab[0];
  }
  
  static public function delete($pk_val){
    $model = 'Model' . ucfirst(static::$object);

    if(is_array(static::$pk)){
      if(!is_array($pk_val)){
        return false;
      }
      $where = '';
      $values = array();
      foreach (static::$pk as $unepk) {
        $where .= $unepk . ' = :' . $unepk . ' AND ';
        $values[$unepk] = $pk_val[$unepk];
      }
      $where = rtrim($where, 'AND ');
    } else {
      if(is_array($pk_val)){
        $lapk = $pk_val[static::$pk];
        $where = $lapk ." = :". $lapk;
        $values = array(static::$pk => $lapk);
      } else {
        $where = static::$pk ." = :". static::$pk;
        $values = array(static::$pk => $pk_val);
      }
    }

    $sql = "DELETE FROM ". ucfirst(static::$object) ." WHERE ". $where;
    $req = Model::getPDO()->prepare($sql);    

    try{
      $req->execute($values);
    
    }catch (PDOException $e){
      echo $e->getMessage();
      return false;
    }
    return true;
  }

  static public function update($data){
    $set = " SET ";
    $values = array();

    foreach ($data as $key => $value) {
      $set = $set . $key . " = :" . $key . ", ";
      $values = [$key => $value] + $values;
    }
    $set = rtrim($set, ", ") . ' ';

    if(is_array(static::$pk)){
      $where = '';
      foreach (static::$pk as $unepk) {
        $where .= $unepk . ' = :' . $unepk . ' AND ';
      }
      $where = rtrim($where, 'AND ');
    } else {
      $where = static::$pk ." = :". static::$pk;
    }

    $sql = "UPDATE ". ucfirst(static::$object) . $set . "WHERE ". $where . ";";
    $req = Model::getPDO()->prepare($sql);
    try{
      $req->execute($values);
    
    }catch (PDOException $e){
      echo $e->getMessage();
      return false;
    }
    return true;
  }


  static public function created($data){
    $attributs = " (";
    $values = ") VALUES (";
    $array = array();

    foreach ($data as $key => $value) {
      $values = $values . ":" . $key . ", ";
      $attributs = $attributs . $key . ", ";
      $array = [$key => $value] + $array;
    }    
    $values = rtrim($values, ", ");
    $attributs = rtrim($attributs, ", ");

    $sql = "INSERT INTO ". ucfirst(static::$object) . $attributs . $values . ");";
    $req = Model::getPDO()->prepare($sql);

    try{
      $req->execute($array);
    }catch (PDOException $e){
      echo "Problème sql : " . $e->getMessage();
      return false;
    }
    return true;
  }

  // exemple d'utilistation : 
  // ModelTravail::selectAllWithArgs($args)
  // où args = array('tache' => 1, 'mail' => 'admin')
  // renvoie un array de ModelTravail ayant pour tache 1 et comme mail admin
  // sens = 'ASC' ou 'DESC'
  // et ordonner peut être n"importe quel attributs
  // ex : sens = 'ASC' et ordonner = 'tache, mail' ou ordonner = 'date'
  // exemple complet : ModelTravail::selectAllWithArgs(array('tache' => 1), 'date', 'DESC')
  static public function selectAllWithArgs($args, $ordonner = NULL, $sens = 'ASC'){
    $model = 'Model' . ucfirst(static::$object);

    $ordre = '';
    if(!is_null($ordonner)) {
      $ordre = ' ORDER BY ' . $ordonner . ' ' . $sens;
    }

    $where = '';
    foreach ($args as $key => $value) {
      $where .= $key . ' = :'. $key .' AND ';
    }
    $where = rtrim($where, 'AND ');

    if(!empty($where)) $where = " WHERE ". $where;

    $sql = "SELECT * FROM " . ucfirst(static::$object) . $where . $ordre;

    try{
      $req = Model::getPDO()->prepare($sql);
      $req->execute($args);
      $req->setFetchMode(PDO::FETCH_CLASS, $model);
      return $req->fetchAll();

    } catch(PDOException $e) {
      echo $e->getMessage(); // affiche un message d'erreur
      die();
    }
  }

}

Model::Init();


?>
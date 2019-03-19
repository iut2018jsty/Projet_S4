<?php
require_once File::build_path(array("model", "Model.php"));

Class ModelUtilisateur extends Model
{
    static protected $object = 'utilisateur';
    static protected $pk = 'mail';

    private $mail;
    private $nom;
    private $prenom;
    private $mdp;
    private $role; 
        // 0 : Employe
        // 1 : Manager
        // 2 : Directeur
    private $remplacepar;
    private $remplace;
    private $nonce;


    public function __construct($m = NULL, $n = NULL, $p = NULL, $pw = NULL, $r = NULL, $rp = NULL){
        if (!is_null($m) && !is_null($n) && !is_null($p) && !is_null($pw) && !is_null($r) && !is_null($rp)) {
          $this->mail = $m;
          $this->nom = $n;
          $this->prenom = $p;
          $this->mdp = $pw;
          $this->remplace = $r;
          $this->remplacepar = $rp;
        }
    }


    //getters setters génériques
    // ex : get("nom")
    public function get($var){
        return $this->$var;
    }
    public function set($var, $val){
        $this->$var = $val;
    }
    //comme le get générique mais à partir d'une adresse mail 
   static public function getNameFromMail($mail){
        $sql = "SELECT nom FROM Utilisateur WHERE mail = :mail";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("mail" => $mail);
        try{
            $req_prep->execute($values);
            return $req_prep->fetch()[0];
                    
          } catch (PDOException $e){
            echo $e->getMessage();
            return false;
          }
    }

    //functions / interactions bd

    public function afficher(){
      return htmlspecialchars(ucfirst($this->prenom) . ' ' . ucfirst($this->nom));
    }

    static public function checkPassword($mail, $mdp){
        $sql = "SELECT COUNT(*) FROM Utilisateur WHERE mdp = :mdp AND mail = :mail";
        $req_prep = Model::getPDO()->prepare($sql);

        $values = array(
            "mail" => $mail,
            "mdp" => $mdp
            );
        try{
          $req_prep->execute($values);
          return ($req_prep->fetch()[0] > 0);
        
        } catch (PDOException $e){
          echo $e->getMessage();
          return false;
        }
    }

    static public function getRole($mail){
        $sql = "SELECT role FROM Utilisateur WHERE mail = :mail";
        $req_prep = Model::getPDO()->prepare($sql);

        $values = array(
            "mail" => $mail,
            );
        try{
          $req_prep->execute($values);
          return $req_prep->fetch()[0];
                  
        } catch (PDOException $e){
          echo $e->getMessage();
          return false;
        }
    }

    static public function aVerifMail($mail){
        $sql = "SELECT nonce FROM Utilisateur WHERE mail = :mail";
        $req_prep = Model::getPDO()->prepare($sql);

        $values = array(
            "mail" => $mail,
            );
        try{
          $req_prep->execute($values);
          return is_null($req_prep->fetch()[0]);
        
        } catch (PDOException $e){
          echo $e->getMessage();
          return false;
        }
    }

    static public function correspond($mail, $nonce){
        $sql = "SELECT COUNT(*) FROM Utilisateur WHERE mail = :mail AND nonce = :nonce";
        $req_prep = Model::getPDO()->prepare($sql);

        $values = array(
            "mail" => $mail,
            "nonce" => $nonce
            );
        try{
          $req_prep->execute($values);
          return ($req_prep->fetch()[0] > 0);
        
        } catch (PDOException $e){
          echo $e->getMessage();
          return false;
        }
    }

    static public function validate($mail){
        $sql = "UPDATE Utilisateur SET nonce = NULL WHERE mail = :mail";
        $req_prep = Model::getPDO()->prepare($sql);

        $values = array(
            "mail" => $mail
            );
        try{
          $req_prep->execute($values);
        
        } catch (PDOException $e){
          echo $e->getMessage();
          return false;
        }    
    }

    static public function changepasswd($data){
        $sql = "UPDATE Utilisateur SET mdp = :mdp WHERE mail = :mail";
        $req_prep = Model::getPDO()->prepare($sql);

        $values = array(
            "mail" => $data['mail'],
            "mdp" => $data['mdp']
            );
        try{
          $req_prep->execute($values);        
        } catch (PDOException $e){
          // echo $e->getMessage();
          return false;
        }    
        return true;
    }

//ex : getListe(1) donne tous les managers
    static public function getListe($role){
      return self::selectAllWithArgs(array("role" => $role), 'nom, prenom');
      /*
        $sql = "SELECT * FROM Utilisateur WHERE role = :role" ; 
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("role" => $role);
        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelUtilisateur");
            return $req_prep->fetchAll(); 
        } catch (PDOException $e){
          return -1; 
        }
      */
    }

    static public function getListeSelonManager($manager) {
      return self::selectAllWithArgs(array("responsable" => $manager), 'nom, prenom');
      /*
       $sql = "SELECT * FROM Utilisateur WHERE responsable = :manager" ; 
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("manager" => $manager);
        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelUtilisateur");
            return $req_prep->fetchAll(); 
        } catch (PDOException $e){
          return -1; 
        }
    */
    }

    // le $mail correspond a celui qui remplace (axel manager, bastien employé : on met le mail de bastien)
    static public function plusderemplacement($mail) {
        $sql = "UPDATE Utilisateur SET remplace = NULL WHERE mail = :mail;
                UPDATE Utilisateur SET remplacepar = NULL WHERE remplacepar = :mail";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("mail" => $mail);
        try{
           $req_prep->execute($values);
           return true;
        } catch (PDOException $e){
          echo $e->getMessage();
          return false;
        } 
    }

    static public function emp_sans_resp(){
        $sql = "SELECT * FROM Utilisateur WHERE role = 0 AND ISNULL(responsable) = 1 "; 
        try{ 
            $rep = Model::getPDO()->query($sql);
            $rep->setFetchMode(PDO::FETCH_CLASS, "ModelUtilisateur");
            return $rep->fetchAll(); 
        } catch (PDOException $e){
          echo $e->getMessage();
          return -1; 
        }
    }

    static public function unset_responsable($mail){
      $sql = "UPDATE Utilisateur SET responsable = NULL WHERE mail = :mail;";
      $req_prep = Model::getPDO()->prepare($sql);
      $values = array("mail" => $mail);
      try{
         $req_prep->execute($values);
         return true;
      } catch (PDOException $e){
        echo $e->getMessage();
        return false;
      }       
    }

}

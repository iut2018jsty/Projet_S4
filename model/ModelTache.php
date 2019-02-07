<?php
require_once File::build_path(array("model", "Model.php"));

class ModelTache extends Model
{
    static protected $object = 'tache';
    static protected $pk = 'id';

    private $id;
    private $intitule;
    private $heuresprevues;
    private $fini;
    private $creepar;
    private $nomprojet;


    public function __construct($id = NULL, $i = NULL, $h = NULL, $f = NULL, $c = NULL, $n = NULL){
        if (!is_null($id) && !is_null($i) && !is_null($h) && !is_null($f) && !is_null($c) && !is_null($n)) {
            $this->id = $id;
            $this->i = $intitule;
            $this->h = $heuresprevues;
            $this->f = $fini;
            $this->c = $creepar;
            $this->n = $nomprojet;
        }
    }

    //getters setters

    public function get($var){
        return $this->$var;
    }

    public function set($var, $val){
        $this->$var = $val;
    }




    static public function getProjet($id = NULL){
        if(is_null($id)) return NULL;

        $sql = "SELECT nomprojet FROM Tache WHERE id = :id";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("id" => $id);
        try{
            $req_prep->execute($values);

            return $req_prep->fetch()[0];

        } catch (PDOException $e){
            $e->getMessage();
            return false;
        }
    }


    static public function getNom($id = NULL){
        if(is_null($id)) return NULL;

        $sql = "SELECT intitule FROM Tache WHERE id = :id";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("id" => $id);
        try{
            $req_prep->execute($values);

            return $req_prep->fetch()[0];

        } catch (PDOException $e){
            $e->getMessage();
            return false;
        }
    }



    public static function selectTacheManager($mail) {
        return self::selectAllWithArgs(array('creepar' => $mail));
        /*
        $sql = "SELECT * FROM Tache WHERE creepar = :ma";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("ma" => $mail);

        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelTache");

            return $req_prep->fetchAll(); 
        } catch (PDOException $e){
          return -1; 
        }
        */
    }


    public static function selectTacheEmp($mail) {
        $sql = 'SELECT * FROM Tache T JOIN Voit V ON V.mail = :mail AND T.id = V.tache ORDER BY T.nomprojet, T.intitule';
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("mail" => $mail);

        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelTache");

            return $req_prep->fetchAll(); 

        } catch (PDOException $e){
            echo $e->getMessage();
            return -1; 
        }
    }

    public static function tachesParProjet($projet){
        return self::selectAllWithArgs(array('nomprojet' => $projet));
        /*
        $sql = "SELECT * FROM Tache WHERE nomprojet = :p";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("p" => $projet);

        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelTache");
            return $req_prep->fetchAll(); 
        } catch (PDOException $e){
            return -1; 
        }*/
    }

    public static function totalHeureTravailEmploye($mail, $id){
        $sql = "SELECT SUM(duree) FROM Tache T JOIN Travail Tr ON Tr.tache = T.id WHERE T.id = :id AND Tr.mail = :mail GROUP BY T.id";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("mail" => $mail, "id" => $id);

        try{
            $req_prep->execute($values);
            return $req_prep->fetch()[0]; 
        } catch (PDOException $e){
            echo $e->getMessage();
            return -1; 
        }        
    }

    public static function taches_dun_manager($mail){
        $sql = 'SELECT T.* FROM Tache T JOIN Projet P ON P.nom = T.nomprojet AND P.gerepar = :mail ORDER BY T.nomprojet, T.intitule';
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("mail" => $mail);
        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelTache");

            return $req_prep->fetchAll(); 

        } catch (PDOException $e){
            echo $e->getMessage();
            return -1; 
        }
    }

    static public function totalConsomme($id){
        $sql = "SELECT SUM(duree) FROM Travail WHERE tache = :id GROUP BY tache";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("id" => $id);
        try{
            $req_prep->execute($values);

            $tmp = $req_prep->fetch()[0];
            if(is_null($tmp)) $tmp = 0;
            return $tmp;
        
        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }
}

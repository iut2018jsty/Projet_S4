<?php
require_once File::build_path(array("model", "Model.php"));

class ModelProjet extends Model
{    

    private $nom;
    private $joursprevus;
    private $gerepar;
    private $creepar;
    private $fini;
    protected static $object = "projet";
    protected static $pk='nom';


    public function __construct($n = NULL, $j = NULL, $g = NULL, $c = NULL, $f = NULL){
        if (!is_null($n) && !is_null($j) && !is_null($g) && !is_null($c) && !is_null($f)) {
            $this->nom = $n;
            $this->joursprevus = $j;  
            $this->gerepar = $g;  
            $this->creepar = $c; 
            $this->fini = $f; 
        }
    }


    //getters setters

    public function get($var){
        return $this->$var;
    }

    public function set($var, $val){
        $this->$var = $val;
    }


    static public function totalConsomme($nom)
    {
        $sql = "SELECT SUM(duree) FROM Travail Tr, Tache Ta WHERE Tr.tache = Ta.id AND Ta.nomprojet = :nomprojet GROUP BY Ta.nomprojet";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("nomprojet" => $nom);
        try{
            $req_prep->execute($values);

            $tmp = $req_prep->fetch()[0];
            if(is_null($tmp)) $tmp = 0;
            return $tmp;
        
        } catch (PDOException $e){
            $e->getMessage();
            return false;
        }
    }
 
    public static function selectProjetPersonnelsEmploye($mail) {
        $sql = "SELECT DISTINCT P.* FROM Projet P JOIN Tache T ON T.nomprojet = P.nom JOIN Voit V ON V.tache = T.id WHERE V.mail = :ma";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("ma" => $mail);

        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelProjet");

            return $req_prep->fetchAll(); 
        } catch (PDOException $e){
          return -1; 
        }
    }

    public static function selectAllEmploye($nomprojet){
        $sql = "SELECT DISTINCT U.* FROM Voit V JOIN Utilisateur U ON V.mail = U.mail JOIN Tache T ON T.id = V.tache WHERE T.nomprojet = :nomprojet ORDER BY U.nom, U.prenom";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("nomprojet" => $nomprojet);

        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelUtilisateur");

            return $req_prep->fetchAll(); 
        } catch (PDOException $e){
            echo $e->getMessage();
            return -1; 
        }
    }



    public static function selectProjetPersonnelsManager($mail) {
        return self::selectAllWithArgs(array('gerepar' => $mail));
        /*
        $sql = "SELECT * FROM Projet WHERE gerepar = :ma";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("ma" => $mail);

        try{
            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, "ModelProjet");

            return $req_prep->fetchAll(); 
        } catch (PDOException $e){
            return -1; 
        }

        }
        */
    }

    public static function totalHeureTravailEmploye($mail, $nomprojet){
        $sql = "SELECT SUM(duree) FROM Projet P JOIN Tache T ON T.nomprojet = P.nom JOIN Travail Tr ON Tr.tache = T.id WHERE P.nom = :nomprojet AND Tr.mail = :mail GROUP BY P.nom";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("mail" => $mail, "nomprojet" => $nomprojet);

        try{
            $req_prep->execute($values);
            return $req_prep->fetch()[0]; 
        } catch (PDOException $e){
            echo $e->getMessage();
            return -1; 
        }        
    }   
}

<?php
require_once File::build_path(array("model", "Model.php"));

class ModelVoit extends Model
{
    static protected $object = 'voit';
    static protected $pk = array('mail', 'tache');

    private $mail;
    private $tache;


    public function __construct($t = NULL, $e = NULL){
        if (!is_null($t) && !is_null($e)) {
            $this->mail = $e; 
            $this->tache = $t;
        }
    }


    //getters setters

    public function get($var){
        return $this->$var;
    }
    public function set($var, $val){
        $this->$var = $val;
    }

//renvoie vrai si l'employé peut réaliser cette tâche
    public static function peutFaire($emp = NULL, $tache = NULL){
        if(is_null($mail) || is_null($day)) return NULL;
        
        $sql = "SELECT COUNT(*) FROM Voit WHERE tache = :tache AND mail = :emp";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array(
            "tache" => $tache,
            "emp" => $emp
        );
        try{
            $req_prep->execute($values);

            return $req_prep->fetch()[0];

        } catch (PDOException $e){
            $e->getMessage();
            return false;
        }
    }

    //renvoie tous les noms des projets dont l'employé a au moins une tâche
    public static function projetsdestachesFaisables($emp = NULL){
    	if(is_null($emp)) return NULL;
        $sql = "SELECT DISTINCT T.nomprojet FROM Voit V
        		JOIN Tache T ON T.id = V.tache
        		WHERE V.mail = :emp";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("emp" => $emp);
        try{
            $req_prep->execute($values);

            return $req_prep->fetchAll();

        } catch (PDOException $e){
            $e->getMessage();
            return false;
        }
    }

    //renvoie toutes les tâches du projet que l'employé peut réaliser
    public static function tachesFaisablesParProjet($emp = NULL, $projet = NULL){
    	if(is_null($emp) || is_null($projet)) return NULL;

        $sql = "SELECT V.tache FROM Voit V
        		JOIN Tache T ON T.id = V.tache
        		WHERE V.mail = :emp AND T.nomprojet = :projet";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("emp" => $emp, "projet" => $projet);
        try{
            $req_prep->execute($values);

            return $req_prep->fetchAll();

        } catch (PDOException $e){
            $e->getMessage();
            return false;
        }
    }

    public static function employesAffectes($tache){
        $sql = "SELECT mail FROM Voit WHERE tache = :tache";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("tache" => $tache->get("id"));
        try{
            $req_prep->execute($values);

            return $req_prep->fetchAll();

        } catch (PDOException $e){
            $e->getMessage();
            return false;
        }
    }

    public static function ajouterEmployeATache($data){
        $id = $data["id"];
        unset($data["id"]); 
        foreach($data as $key => $value){
            if(!self::created(array("mail" => $value, "tache" => $id))) return false;
        }
        return true;
    }




    //functions / interactions bd






}

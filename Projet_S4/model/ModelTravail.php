<?php
require_once File::build_path(array("model", "Model.php"));
require_once File::build_path(array("model", "ModelTache.php"));

class ModelTravail extends Model
{
    static protected $object = 'travail';
    static protected $pk = array('mail', 'date', 'tache');

    private $mail;
    private $date;
    private $tache;
    private $commentaire;
    private $duree;


    public function __construct($t = NULL, $e = NULL, $da = NULL, $c = NULL, $du = NULL){
        if (!is_null($t) && !is_null($e) && !is_null($d) && !is_null($c)) {
            $this->mail = $e;
            $this->date = $da;   
            $this->tache = $t;     
            $this->commentaire = $c;
            $this->duree = $du;
        }
    }


    //getters setters

    public function get($var){
        return $this->$var;
    }
    public function set($var, $val){
        $this->$var = $val;
    }



    //functions / interactions bd

    static public function totalDuJour($mail = NULL, $day = NULL){
        if(is_null($mail) || is_null($day)) return NULL;
        
        $sql = "SELECT SUM(duree) FROM Travail WHERE date = :day AND mail = :mail GROUP BY date, mail";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array(
            "mail" => $mail,
            "day" => $day
        );
        try{
            $req_prep->execute($values);
            return $req_prep->fetch()[0];

        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    static public function dureeTravail($mail = NULL, $day = NULL, $tache = NULL){
        if(is_null($mail) || is_null($day) || is_null($tache)) return NULL;

        $travail = self::select(array('mail' => $mail, 'date' => $day, 'tache' => $tache));
        if(is_null($travail)) return 0;
        return $travail->get('duree');
        /*
        $sql = "SELECT duree FROM Travail WHERE date = :day AND mail = :mail AND tache = :tache";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array(
            "mail" => $mail,
            "day" => $day,
            "tache" => $tache
        );
        try{
            $req_prep->execute($values);

            return $req_prep->fetch()[0];

        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }*/
    }


}

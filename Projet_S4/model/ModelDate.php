<?php
require_once File::build_path(array("model", "Model.php"));

class ModelDate extends Model
{
    private $date;

    public function __construct($d = NULL)
    {
        if(!is_null($d)){
            $this->date = $d;
        }
    }

    public function getDate(){
        return $this->date;
    }

    static public function addDate($day){
        $sql = "INSERT INTO Date (date) VALUES (:day)";
        $req_prep = Model::getPDO()->prepare($sql);
        $values = array("day" => $day);
        try{
            $req_prep->execute($values);
        
        } catch (PDOException $e){
            return false;
        }
        return true;
    }


    static public function checkDate($day){
        $sql = "SELECT COUNT(*) FROM Date WHERE date = :day";
        $req_prep = Model::getPDO()->prepare($sql);

        $values = array("day" => $day);
        try{
            $req_prep->execute($values);
            if ($req_prep->fetch()[0] == 0) return self::addDate($day);
        
        } catch (PDOException $e){
            return false;
        }
        return true;
    }


}

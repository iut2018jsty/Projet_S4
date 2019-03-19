<?php
require_once File::build_path(array('model', 'ModelTache.php'));

class ControllerTache {
	public static $controller = 'tache';

	public static function readAll(){
        if(Session::is_employe()){
            $view = 'list_pour_employe';
        } 
        if(Session::is_manager()){
            $tab_t = ModelTache::taches_dun_manager($_SESSION['mail']);
            $view = 'list';
        }
        if(Session::is_directeur()){
            $tab_t = ModelTache::selectAllWithArgs(array(), 'nomprojet, intitule');
            $view = 'list';
        }
		$pagetitle = 'Liste des tâches';
		require File::build_path(array("view", "view.php"));      
	}	

    public static function readParProjet($data){
        if(Session::is_employe() || !isset($data['nom'])) { 
            return self::error();
        }
        $taches = ModelTache::tachesParProjet($data["nom"]);
        $view = 'list_par_projet';
        $pagetitle = 'Liste des tâches';
        require File::build_path(array("view", "view.php"));
    }

	public static function read($data){
		$view = 'detail';
		$pagetitle = 'Détails tâche';
		$isAdmin = Session::is_directeur();
		$t = ModelTache::select($data["id"]);
        //$aff = ModelUtilisateur::selectAll();
        $coms = ModelTravail::selectAllWithArgs(array('tache' => $data['id']), 'date', 'DESC');
		require File::build_path(array("view", "view.php"));;
	}	

	public static function create(){
        $view = 'update';
        $pagetitle = 'Création tâche';  
        $controller = 'tache';       

        $id = '';
        $intitule = '';
        $heuresprevues = '';
        $creepar = $_SESSION['mail'];
        $nomprojet = '';
        $nomprojetnonmodifiable = 0;
        if(isset($_GET['projet'])) {
            $nomprojet = $_GET['projet'];
            $nomprojetnonmodifiable = 1;
        }
        $nonfinie = 'checked';
        $finie = '';

        $option = '';
        $admin = Session::is_directeur();
        $end = 'required';
        $action = 'created';
        require File::build_path(array("view", "view.php"));
    }

    public static function update($data){
        if(Session::is_employe()) {
            return self::error();
        }

        $view = 'update';
        $controller = 'tache';
        $id = rawurlencode($data["id"]);
        $t = ModelTache::select($id);
        $pagetitle = 'Enregistrez une tache';
        $action = 'updated';

        $intitule = $t->get('intitule');
        $end = 'readonly';
        $heuresprevues = $t->get('heuresprevues');
        $nonfinie = 'checked';
        $finie = '';
        $nomprojetnonmodifiable = 0;
        if($t->get('fini')){
            $nonfinie = '';
            $finie = 'checked';
        }
        $creepar = $t->get('creepar');
        $nomprojet = $t->get('nomprojet');
   
        require File::build_path(array("view", "view.php"));           
    }



    public static function updated($data){
        if(Session::is_employe()) {
            return self::error();
        }
        $view = 'updated';
        $pagetitle = 'tâche mise à jour';
       
        if(ModelTache::update($data)){
            return self::readAll();
        } else {
            return self::error();
        }
    }

    public static function created($data){
        if(Session::is_employe()){
            return self::error();
        }

        if(ModelTache::created($data)) {
            return self::readAll();
        }
        else {
            return self::error();
        }
    }

    public static function delete($data){ 
        if(Session::is_employe()){
            return ControllerUtilisateur::connect();
        }
        if(ModelTache::delete($data["id"])) {
            return self::readAll();
        } else {
            return self::error();
        }
    }

    public static function affecterEmploye($data){
        if(ModelVoit::ajouterEmployeATache($data)){
            self::read($data);
        } else {
            return self::error();
        }
    }

    public static function error($err_msg = NULL){
        ModelUtilisateur::error($err_msg);
    }

    public static function unselect($data){
        $data['tache'] = $data['id'];
        if(!ModelVoit::delete($data)){
            return self::error('Désaffectation impossible');
        }
        return self::read($data);
    }
}
?>

<?php
require_once File::build_path(array('model', 'ModelProjet.php'));

class ControllerProjet {
	public static $controller = 'projet';

	public static function readAll(){
		$pagetitle = 'Liste des projets en cours';
        if(Session::is_directeur()) {
            $view = 'list_directeur';
            $tab_p = ModelProjet::selectAllWithArgs(array('fini' => 0));
        } else if(Session::is_manager()){
            $tab_p = ModelProjet::selectAllWithArgs(array('gerepar' => $_SESSION['mail'], 'fini' => 0), 'nom');
            $view = 'list';
        } else {
            $view = 'list';
        }
		require File::build_path(array("view", "view.php"));
	}	

	public static function read($data){
		$view = 'detail';
		$pagetitle = 'Détails projet';
		$isAdmin = Session::is_directeur();
		$p = ModelProjet::select($data['nom']);
		require File::build_path(array("view", "view.php"));;
	}	

    public static function delete($data){ 
        if(!Session::is_directeur()) {
            return ControllerUtilisateur::connect();
        }
        if(ModelProjet::delete($data["nom"])) {
            return self::readAll();
        } else {
            return self::error();
        }
    }

    public static function create(){
        if(!Session::is_directeur()) {
            return self::error();
        }
        $view = 'update';
        $pagetitle = 'Enregistrez un projet';  
        $controller = 'projet';        
        $joursprevus = '';
        $nom = '';
        $creepar = $_SESSION['mail'];
        $gerepar = '';
        $empl = 'checked';
        $mana = '';
        $dir = '';
        $admin = true;
        $end = 'required';
        $false = 'checked';
        $nonfinie = 'checked';
        $finie = '';
        $true = '';
        $action = 'created';
        require File::build_path(array("view", "view.php"));
    }

    public static function update($data){
        if(!Session::is_directeur()) {
            return self::error();
        }
        $view = 'update';
        $pagetitle = 'Enregistrez un projet';

        $controller = 'projet';
        $empl = 'checked';
    

        $nom = rawurldecode($data["nom"]);
        $u = ModelProjet::select($nom);
        $joursprevus = $u->get('joursprevus');
        $gerepar = $u->get('gerepar');
        $creepar = $u->get('creepar');
        $end =  'readonly';
        $nonfinie = 'checked';
        $finie = '';
        if($u->get('fini')){
            $nonfinie = '';
            $finie = 'checked';
        }

        $admin = Session::is_directeur();
        $action = 'updated';
    
        
        require File::build_path(array("view", "view.php"));     
}

    public static function updated($data){
        if(!Session::is_directeur()){
            return self::error();
        }
        $view = 'updated';
        $pagetitle = 'projet mis à jour';
      
        if(ModelProjet::update($data)){
            return self::readAll();
        } else {
            return self::error();
        }
    }

    public static function created($data){
        if(!Session::is_directeur()) {
            return ControllerUtilisateur::connect();
        }

        $view = 'created';
        $pagetitle = 'projet enregistré';

        if(ModelProjet::created($data)) {
            return self::readAll();
        }
        else {
            return self::error();
        }
    }


    public static function error(){
        self::$controller = 'utilisateur';
        $view = 'error';
        $pagetitle = 'Il y a eu une erreur';
        require File::build_path(array("view", "view.php"));
    }

    public static function readAllDone() {
        $view = 'list_done';
        $pagetitle = 'Liste des projets terminés';
        if (Session::is_directeur()){
            $tab_p = ModelProjet::selectAllWithArgs(array('fini' => 1));
        } else if(Session::is_manager()){
            $tab_p = ModelProjet::selectAllWithArgs(array('gerepar' => $_SESSION['mail'], 'fini' => 1));
        } else {
            $tab_p = ModelProjet::selectProjetPersonnelsEmploye($_SESSION['mail']);
        }
        require File::build_path(array("view", "view.php"));
    }

}
?>

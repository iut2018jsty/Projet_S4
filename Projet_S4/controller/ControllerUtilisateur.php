<?php
require_once File::build_path(array('model', 'ModelUtilisateur.php'));

class ControllerUtilisateur{
	public static $controller = 'utilisateur';

	public static function readAll($data = array()){
		if(Session::is_employe()) return self::error();
		$tab_u = ModelUtilisateur::selectAll();
		if(Session::is_manager()) {
			$view = 'list_manager';
			$manager = ModelUtilisateur::select($_SESSION['mail']);
		}
		if(Session::is_directeur()) {
			if(!isset($data['style_affichage'])){
				$view = 'list';
			} else switch ($data['style_affichage']) {
				case 'manager':
					$view = 'list_directeur_par_manager';
					break;
				case 'nom':
					$view = 'list_directeur_par_nom';
					break;
				case 'role':
					$view = 'list';
					break;
				default:
					$view = 'list';
					break;
			}
		}	
		$pagetitle = 'Liste des utilisateurs';
		require File::build_path(array("view", "view.php"));
	}	

	public static function read($data){
		$view = 'detail';
		$pagetitle = 'Détails utilisateur';
		$u = ModelUtilisateur::select($data["mail"]);
		require File::build_path(array("view", "view.php"));
	}


	public static function create(){
		if(!Session::is_directeur()){
			return self::connect();
		}
		$view = 'update';
		$pagetitle = 'Enregistrez un utilisateur';	
		$controller = 'utilisateur';		
		$mail = '';
		$nom = '';
		$prenom = '';
		$mdp = '';
		$empl = 'checked';
		$mana = '';
		$dir = '';
		$admin = Session::is_directeur();
		$end = 'required';
		$action = 'created';
		require File::build_path(array("view", "view.php"));
	}

	public static function update($data){
		$mail = rawurldecode($data["mail"]);
		if(Session::is_directeur() || Session::is_user($mail)) {
		$view = 'update';
		$pagetitle = 'Enregistrez un utilisateur';

		$controller = 'utilisateur';
		$empl = 'checked';
		$mana = '';
		$dir = '';
		$u = ModelUtilisateur::select($mail);
		$nom = $u->get('nom');
		$prenom = $u->get('prenom');
		$mdp = $u->get('mdp');
		$end =  'readonly';
		$admin = Session::is_directeur();
		$action = 'updated';
		if($u->get('role') >= 1){
			$empl = '';
			$mana = 'checked';
			if($u->get('role') >= 2){
				$mana = '';
				$dir = 'checked';
			}
		}
		require File::build_path(array("view", "view.php"));		
	}
	else {
		return self::connect();
	}
}

	public static function updated($data){
		if(!Session::is_directeur() && !Session::is_user($data['mail'])) {
			return self::error();
		}
		// la partie qui gere remplace et remplacepar
		$role = ModelUtilisateur::getRole($data['mail']);
		if($role > 0 && isset($data['remplacepar'])) {
			if($data['remplacepar'] == 'rien'){
				$user = ModelUtilisateur::select($data['mail']);
				if(!ModelUtilisateur::plusderemplacement($user->get('remplacepar'))) return false;
			} else {
				if(!ModelUtilisateur::update(array('mail' => $data['remplacepar'], 'remplace' => $data['mail']))) return false;
				if(!ModelUtilisateur::update(array('mail' => $data['mail'], 'remplacepar' => $data['remplacepar']))) return false;
			}
		}
		if($role < 2 && isset($data['remplace'])) {
			if($data['remplace'] == 'rien'){
				if(!ModelUtilisateur::plusderemplacement($data['mail'])) return false;
			} else {
				if(!ModelUtilisateur::update(array('mail' => $data['remplace'], 'remplacepar' => $data['mail']))) return false;
				if(!ModelUtilisateur::update(array('mail' => $data['mail'], 'remplace' => $data['remplace']))) return false;
			}
		}
		unset($data['remplace']);
		unset($data['remplacepar']);
		if(isset($data['responsable']) && $data['responsable'] == 'rien') {
			ModelUtilisateur::unset_responsable($data['mail']);
			unset($data['responsable']);
		}
		// fin de la gestion du remplacement
		
		if(isset($data['mdp'])) $data['mdp'] = Security::chiffrer($data['mdp']);
		
		if(!ModelUtilisateur::update($data)) return false;
		
		$view = 'updated';
		$pagetitle = 'Utilisateur mis à jour';
		$tab_u = ModelUtilisateur::selectAll();
		require File::build_path(array("view", "view.php"));
	}

	public static function created($data){
		if(!Session::is_directeur())
			return self::connect();
		if(!filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
			return self::create();
		$view = 'created';
		$pagetitle = 'Utilisateur enregistrée';
		$data['mdp'] = Security::chiffrer($data['mdp']);
		$data = $data + ["nonce" => Security::generateRandomHex()];
		if(ModelUtilisateur::created($data)) 	{
			self::sendMail($data);
			$tab_u = ModelUtilisateur::selectAll();
		}
		else {
			$view = 'error';
			$pagetitle = 'Problème enregistrement';
		}
		require File::build_path(array("view", "view.php"));
	}

	public static function delete($data){ 
		if(!Session::is_directeur()) {
			return self::connect();
		}
		if($data['mail'] == 'admin'){
			return self::error();
		}
		if(ModelUtilisateur::delete($data["mail"])) {
			$tab_u = ModelUtilisateur::selectAll();
			$view = 'deleted';
			$pagetitle = 'Utilisateur supprimée';
		} else {
			$view = 'error';
			$pagetitle = 'Problème suppression';
		}
		require File::build_path(array("view", "view.php"));
	}

	public static function error($err_msg = NULL){
		$view = 'error';
		$pagetitle = 'Page non existante';
		require File::build_path(array("view", "view.php"));
	}

	public static function connect($err_msg = NULL){
		if(!is_null($err_msg) && is_array($err_msg)) $err_msg = NULL;
		if (!isset($_SESSION['preremp'])) $_SESSION['preremp'] = '';
  		$view = 'connect';
  		$pagetitle = 'Se connecter';
		require_once File::build_path(array("view", "view.php"));
	}

	public static function connected($data){
		if(!ModelUtilisateur::checkPassword($data['mail'], Security::chiffrer($data['mdp']))){
			$_SESSION['preremp'] = $data['mail'];
			return self::connect('Mail ou mot de passe incorrect.');
		}
		if(!ModelUtilisateur::aVerifMail($data['mail'])){
			$_SESSION['preremp'] = $data['mail'];
			return self::connect('Compte non validé, vérifiez vos mail.');
		}
		unset($_SESSION['preremp']);
		$user = ModelUtilisateur::select($data['mail']);
		if(!is_null($user->get('remplace'))){
			$user = ModelUtilisateur::select($user->get('remplace'));
		}
		$data['mail'] = $user->get('mail');
		$_SESSION['mail'] = $data['mail'];
		$_SESSION['role'] = ModelUtilisateur::getRole($data['mail']);
		if(Session::is_employe()) return ControllerTravail::detailsemaine($data);
		if(Session::is_manager()) return ControllerTache::readAll();
		if(Session::is_directeur()) return ControllerProjet::readAll();
		self::error('Role non compréhensible ou incorrect.');
	}

	public static function deconnect($data){
		if(isset($_SESSION['mail'])){
			session_unset();
			session_destroy();
			setcookie(session_name(),'',time()-1);
			self::connect();
		}
		
	}

	public static function validate($data){
  		$view = 'validated';
  		$pagetitle = 'Email validée';
		if(ModelUtilisateur::correspond($data['mail'], $data['nonce']))
		{
			ModelUtilisateur::validate($data['mail']);
			require_once File::build_path(array("view", "view.php"));
		}
		else self::readAll();
	}

	public static function sendMail($data){
		$mail = "Pour valider l'adresse mail cliquez sur http://webinfo.iutmontp.univ-montp2.fr/~lesueurb/Workflow/index.php?action=validate&controller=utilisateur&mail=". $data['mail']."&nonce=". $data['nonce'] . ".";
		mail($data['mail'], "Mail de confirmation", $mail);
	}

	public static function changemdp($data = array(), $err_msg = NULL){
		if(!Session::is_user($data['mail']) && !Session::is_directeur())
			return self::error('Vous ne pouvez changer que votre mot de passe<br>Demandez a un administrateur si le problème persiste');
  		$view = 'changemdp';
  		$pagetitle = 'Changer de mot de passe';
		require_once File::build_path(array("view", "view.php"));	
	}

	public static function changemdpdindb($data){
		if(!Session::is_user($data['mail']) && !Session::is_directeur())
			return self::error('Vous ne pouvez changer que votre mot de passe<br>Demandez a un administrateur si le problème persiste');
		if(!ModelUtilisateur::checkPassword($data['mail'], Security::chiffrer($data['mdpold']))){
 			return self::changemdp($data, 'Ancien mot de passe non valide');
 		}
		if($data['mdp'] != $data['mdp2']){
			return self::changemdp($data, 'Nouveau mot de passe différents ');
		}
		if($data['mdpold'] == $data['mdp']){
			return self::changemdp($data, 'Nouveau mot de passe identique à l\'ancien, veuilllez changer de mot de passe');
		}
		$data['mdp'] = Security::chiffrer($data['mdp']);
		if(ModelUtilisateur::changepasswd($data)) self::deconnect($data);
		else self::error();			
		
	}
}
?>

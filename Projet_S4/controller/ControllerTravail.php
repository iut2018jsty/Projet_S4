<?php
require_once File::build_path(array('model', 'ModelTravail.php'));
require_once File::build_path(array('model', 'ModelVoit.php'));

class ControllerTravail{
	public static $controller = 'travail';
        

	public static function detailsemaine($data){
		$mail = $_SESSION['mail'];
		if(isset($data['mail']) && (Session::is_directeur() ||  (Session::is_manager() && (0 < count(ModelUtilisateur::selectAllWithArgs(array('mail' => $data['mail'], 'responsable' => $_SESSION['mail']))))))) $mail = $data['mail'];

		if(0 == count(ModelUtilisateur::selectAllWithArgs(array('mail' => $mail, 'role' => 0)))) return self::error();

		$weekdif = 0; // 0 = cette semaine, -1 semaine precedete, 1 semaine suivante etc.
		if(isset($data['week'])) $weekdif = $data['week'];
		
		$week = ControllerDate::getWeek($weekdif);
		foreach ($week as $day) {
			ModelDate::checkDate($day);
		}
		$taches = array();
		$projets = ModelVoit::projetsdestachesFaisables($mail);
		foreach ($projets as $key => $value) {
			foreach (ModelVoit::tachesFaisablesParProjet($mail, $value[0]) as $key2 => $value2) {
				$taches = $taches + [$value2[0] => $value[0]];
			}
		}
		$view = 'detailsemaine';
		$pagetitle = 'Detail de la semaine';
	    $user = ModelUtilisateur::select($mail);
		$tmp = ControllerDate::getLundi($weekdif);
		require File::build_path(array("view", "view.php"));
	}

	public static function create($data){
		$view = 'update';
		$pagetitle = 'Enregistrez un travail';
		$action = 'created';

		$controller = 'travail';
		$mail = rawurldecode($data["mail"]);
		$date = rawurldecode($data["date"]);
		$tache = rawurldecode($data["tache"]);
		$duree = 0;
		$placeholder = "Votre commentaire sur votre travail ici.";
		$commentaire = '';
		$admin = Session::is_directeur(); // modifier tout + changer admin

		require File::build_path(array("view", "view.php"));
	}

	public static function update($data){
		$view = 'update';
		$pagetitle = 'Enregistrez une travail';
		$controller = 'travail';
		$action = 'updated';

		$mail = rawurldecode($data["mail"]);
		$date = rawurldecode($data["date"]);
		$tache = rawurldecode($data["tache"]);

		$array = array(
			"mail" => $mail,
			"date" => $date,
			"tache" => $tache
		);

		$t = ModelTravail::select($array);
		
		$duree = $t->get('duree');
		$commentaire = $t->get('commentaire');
		$placeholder = "";

		require File::build_path(array("view", "view.php"));	
	}

	public static function updated($data){
		if(!Session::is_user($data['mail']) || !ModelTravail::update($data)){
			return self::error();
		}
		self::detailsemaine($data);
	}

	public static function created($data){
		if(!Session::is_user($data['mail']) || !ModelTravail::created($data)){
			return self::error();
		}
		self::detailsemaine($data);
	}

	public static function delete($data){
		if(!Session::is_user($data['mail']) || !ModelTravail::delete($data)){
			return self::error();
		}
		self::detailsemaine($data);
	}

	public static function error(){
		ControllerUtilisateur::error();
	}
}
?>

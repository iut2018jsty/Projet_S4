<?php
require_once File::build_path(array("controller", "ControllerUtilisateur.php"));
require_once File::build_path(array("controller", "ControllerProjet.php"));
require_once File::build_path(array("controller", "ControllerTache.php"));
require_once File::build_path(array("controller", "ControllerTravail.php"));
require_once File::build_path(array("controller", "ControllerDate.php"));

// function myGet($var){ 
// 	if(isset($_GET($var))) return $_GET($var);
// 	if(isset($_POST($var))) return $_POST($var);
// 	return NULL;
// }

$data = array_merge($_GET, $_POST);

// echo'<br>$_GET : '; var_dump($_GET); echo '<br>';
// echo'<br>$_POST : '; var_dump($_POST); echo '<br>';
// echo'<br>$data : '; var_dump($data); echo '<br>';
// echo'<br>$_SESSION : '; var_dump($_SESSION); echo '<br>';


if(!Session::is_connected()) {
	$controller = 'utilisateur';
	$action = 'connect';
	if(isset($data['action']) && 
			($data['action'] == 'connected' || $data['action'] == 'validate')) {
		$action = $data['action'];
	}
}
else {
	if(!isset($data['controller']) || !isset($data['action'])) {
		if(Session::is_employe()) {
			$controller = 'travail';
			$action = 'detailsemaine';
		}
		if(Session::is_manager()) {
			$controller = 'tache';
			$action = 'readAll';
		}
		if(Session::is_directeur()) {
			$controller = 'projet';
			$action = 'readAll';
		}
	} else {
		$controller = $data['controller'];
		$action = $data['action'];
	}
}

$controller_class = "Controller" . ucfirst($controller);
if(!class_exists($controller_class) || !in_array($action, get_class_methods($controller_class))){
	$controller = 'utilisateur';
	$controller_class = "ControllerUtilisateur";
	$action = 'error';
}
unset($data['action']);
unset($data['controller']);

if($action != 'error'){
	$controller_class::$action($data);
} else{
	$controller_class::$action('Lien non valide, erreur 404');
}
//echo '<br>appel de la fonction : ' . $controller_class . '::' . $action . '()<br>';


?>

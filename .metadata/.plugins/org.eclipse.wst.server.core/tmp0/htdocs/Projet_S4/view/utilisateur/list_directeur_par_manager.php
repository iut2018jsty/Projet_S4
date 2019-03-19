<?php 
require_once File::build_path(array("view", "utilisateur", "choix_list_directeur.php"));
foreach (ModelUtilisateur::selectAllWithArgs(array('role' => 1), 'nom, prenom') as $manager) {
	require File::build_path(array("view", "utilisateur", "list_manager.php"));
}

// afficher les employe sans responsable
$emp_sans_resp = ModelUtilisateur::emp_sans_resp();
if(!empty($emp_sans_resp)){
	echo '<br><h3>Liste des employés sans responsable : </h3><ul>';
	foreach ($emp_sans_resp as $emp) {
		echo '<li><a '. $href .'read&mail='. rawurlencode($emp->get('mail')) . '>' . $emp->afficher() .'</a>';
		if(Session::is_directeur()) {
			echo '<a class = "no" href=?controller=travail&action=detailsemaine&mail=' . rawurlencode($emp->get('mail')) . '><img src="img/calendrier.png"></a>';
		}
		echo '</li>';
	}
	echo'</ul>';
}


?>
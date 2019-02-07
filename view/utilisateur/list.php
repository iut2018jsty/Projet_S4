<?php
$href = 'href=?controller=utilisateur&action=';
// ce que voit le directeur
if(Session::is_directeur()) {
require_once File::build_path(array("view", "utilisateur", "choix_list_directeur.php"));

	foreach (ModelUtilisateur::getListe(2) as $a) {
		echo '<p><a '. $href .'read&mail='. rawurlencode($a->get('mail')) . '>' . $a->afficher() . '*</a></p>';
	}

	echo '<div id="listeRole"><table><thead> <td>Liste des employés</td> <thead><tbody>';
	foreach (ModelUtilisateur::getListe(0) as $emp) {
		echo '<tr><td><a '. $href .'read&mail='. rawurlencode($emp->get('mail')) . '>' . $emp->afficher() .'</a>
			<a class = "no" href=?controller=travail&action=detailsemaine&mail=' . rawurlencode($emp->get('mail')) . '><img src="img/calendrier.png"></a></td></tr>';
	}
	echo '</tbody></table>';

	echo '<table>
			<thead><td>Liste des managers</td></thead>
			<tbody>';
	foreach (ModelUtilisateur::getListe(1) as $man) {
		echo '<tr><td><a '. $href .'read&mail='. rawurlencode($man->get('mail')) . '>' . $man->afficher() .'</a>';
		echo '</td></tr>';
	}
	echo '</tbody></table></div><br><p><a href="?controller=utilisateur&action=create"> Ajouter un utilisateur.</a> <br></p>';


}


?>


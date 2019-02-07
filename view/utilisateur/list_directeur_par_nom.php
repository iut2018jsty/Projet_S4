<?php 
$href = 'href="?controller=utilisateur&action=';
require_once File::build_path(array("view", "utilisateur", "choix_list_directeur.php"));
echo '<br><h3>Tous les utilisateurs : </h3><ul class="listeparnom">';
foreach (ModelUtilisateur::selectAllWithArgs(array(), 'nom, prenom') as $utilisateur) {
	echo '<li><a '. $href .'read&mail='. rawurlencode($utilisateur->get('mail')) .'">' . $utilisateur->afficher() . '</a>';
	if($utilisateur->get('role') == 0) echo ' <a class = "no" href=?controller=travail&action=detailsemaine&mail=' . rawurlencode($utilisateur->get('mail')) . '><img src="img/calendrier.png"></a>';
	echo'</li>';
}
echo '</ul>';

?>
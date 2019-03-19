<?php
$href = 'href=?controller=utilisateur&action=';

echo '<br><h3>Liste des employés pour <u><i>'. $manager->afficher() .'</i></u> : </h3><ul>';
$liste = ModelUtilisateur::getListeSelonManager($manager->get('mail'));
if(empty($liste)) echo '<li> Aucun </li>';
foreach ($liste as $emp) {
	echo '<li><a '. $href .'read&mail='. rawurlencode($emp->get('mail')) . '>' . $emp->afficher() .'</a>';
	echo ' <a class = "no" href=?controller=travail&action=detailsemaine&mail=' . rawurlencode($emp->get('mail')) . '><img src="img/calendrier.png"></a></li>';
}
echo'</ul>';
?>
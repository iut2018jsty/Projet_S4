<?php
require_once File::build_path(array("controller", "ControllerUtilisateur.php"));
$href = 'href=?controller=projet&action=';

echo'<h3>Liste projets en cours: </h3><ul>';

$sansprojet = true;

foreach(ModelProjet::selectProjetPersonnelsEmploye($_SESSION['mail']) as $u) {
	if (!$u->get('fini')) echo '<li><a '. $href .'read&nom='. rawurlencode($u->get('nom')) . '>' . htmlspecialchars($u->get('nom')) .'</a></li>';
	$sansprojet = false;
}

foreach(ModelProjet::selectAllWithArgs(array('gerepar' => $_SESSION['mail'], 'fini' => 0)) as $u) {
	$sansprojet = false;
	echo '<li><a '. $href .'read&nom='. rawurlencode($u->get('nom')) . '>' . htmlspecialchars($u->get('nom')) .'</a></li>';	
}
echo'</ul>';

if($sansprojet) echo "<p>Vous n'avez pas de projets en cours.</p>";

echo '<a href="?controller=projet&action=readAllDone">Voir les projets terminés</a>';

?>
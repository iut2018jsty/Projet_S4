<?php
require_once File::build_path(array("controller", "ControllerUtilisateur.php"));
$href = 'href=?controller=projet&action=';

echo'<h3>Liste projets terminés : </h3><ul>';

foreach ($tab_p as $p) {
	if ($p->get('fini') == 1) {
				echo '<li> <a '. $href .'read&nom='. rawurlencode($p->get('nom')) .'>' . $p->get('nom') . '<i> ('. ModelProjet::totalConsomme($p->get('nom')) .'/'. strval($p->get('joursprevus')*24) .'h)</i> : </a><ul>';
		$taches = ModelTache::selectAllWithArgs(array('nomprojet' => $p->get('nom')), 'intitule');

		if(!$taches) echo'<li>' . 'Aucun' . '</li>';

		foreach ($taches as $t) {
			$temps = ' ('. strval(ModelTache::totalConsomme($t->get("id"))) .'/'. $t->get('heuresprevues') .'h)';
			
			if($t->get("fini")) {
				$temps = ' (fini en '. strval(ModelTache::totalConsomme($t->get("id"))) . 'h)';
			}

			echo'<li><a href="?controller=tache&action=read&id='.$t->get("id").'">' . $t->get('intitule') . $temps .'</a></li>';
		}

		echo'</ul><br>';
	}
}

if (!isset($taches)) echo "Vous n'avez pas de projets terminés.";



?>
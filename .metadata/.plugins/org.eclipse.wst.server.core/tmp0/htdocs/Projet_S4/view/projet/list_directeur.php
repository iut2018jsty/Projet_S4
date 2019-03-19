<?php

$href = 'href=?controller=projet&action=';

echo'<h3>Liste projets en cours : </h3><ul>';

foreach ($tab_p as $p) {
	$resp = ModelUtilisateur::select($p->get('gerepar'));
	echo '<li> <a '. $href .'read&nom='. rawurlencode($p->get('nom')) .'>' . $p->get('nom') . '<i> ('. ModelProjet::totalConsomme($p->get('nom')) .'/'. strval($p->get('joursprevus')*24) .'h)</i> - '. $resp->afficher() .' : </a><ul>';
	$taches = ModelTache::selectAllWithArgs(array('nomprojet' => $p->get('nom'), 'fini' => 0), 'intitule');

	if(!$taches) echo'<li>' . 'Aucune tâche en cours' . '</li>';

	foreach ($taches as $t) {
		$temps = ' ('. strval(ModelTache::totalConsomme($t->get("id"))) .'/'. $t->get('heuresprevues') .'h)';
		
		if($t->get("fini")) {
			$temps = ' (fini en '. strval(ModelTache::totalConsomme($t->get("id"))) . 'h)';
		}

		echo'<li><a href="?controller=tache&action=read&id='.$t->get("id").'">' . $t->get('intitule') . $temps .'</a></li>';
	}

	echo'</ul><br>';	
}

echo '</ul><p>Ajouter un projet : <a class="no" href="?controller=projet&action=create"><img src="img/plus.png"></a> <br></p>';

echo '<a href="?controller=projet&action=readAllDone">Voir tous les projets terminés</a>';

?>
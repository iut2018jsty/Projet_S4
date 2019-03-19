<?php
$href = 'href=?controller=tache&action=';
echo '<h3>Liste des tâches du projet \''. $data['nom'] .'\' :</h3>';
if(empty($taches)) echo "Il n'y a encore aucune tâche pour ce projet";
echo '<lu>';
foreach($taches as $t){
    echo '<li><a '. $href .'read&id='. rawurlencode($t->get('id')) . '>' . ucfirst(htmlspecialchars($t->get('intitule')));
    if($t->get('fini')){
	    echo ' (fini en ' . ModelTache::totalConsomme($t->get('id')) .'h)</a></li>';
	} else {
	    echo ' (' . ModelTache::totalConsomme($t->get('id')) . '/'. $t->get('heuresprevues') .'h)</a></li>';
	}
}
echo'</lu><br><p>Ajouter une tâche : <a class="no" href="?controller=tache&action=create&projet='. rawurlencode($data['nom']) .'"><img src="img/plus.png"></a> <br></p>';

?>
<?php
echo'<h3>Tâche faisable pour ' . $_SESSION['mail'] . ' :';
echo'<ul>';
foreach(ModelTache::selectTacheEmp($_SESSION['mail']) as $t) {
 	echo '<li><a href="?controller=projet&action=read&nom='. urlencode($t->get('nomprojet')) .'">'. $t->get('nomprojet') .'</a> : <a href="?controller=tache&action=read&id='. rawurlencode($t->get('id')) . '">' . htmlspecialchars($t->get('intitule')) .'</a></li>';
}
echo'</ul>';

?>
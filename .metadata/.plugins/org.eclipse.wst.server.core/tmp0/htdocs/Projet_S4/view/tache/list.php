<?php
$href = 'href=?controller=tache&action=';

echo '<h3>Tâches : </h3><ul>';
foreach ($tab_t as $t){
    echo '
    <li> <a href="?controller=projet&action=read&nom='. rawurlencode($t->get('nomprojet')) .'">'. $t->get('nomprojet') .'</a> : <a '. $href .'read&id='. rawurlencode($t->get('id')) . '>' . htmlspecialchars($t->get('intitule')) .'</a></li>';
}
echo '</ul>';
?>
<?php
  $href = 'href=?controller=utilisateur&action=';
  echo "<p><b>Mail utilisateur : </b>" . htmlspecialchars($u->get('mail')) .
   "<br><b>Nom utilisateur : </b>" . htmlspecialchars($u->get('nom')) .
    "<br><b>Prénom utilisateur </b>" . htmlspecialchars($u->get('prenom'));
  if(!is_null($u->get('remplace'))) echo '<br><b> Remplace l\'utilisateur : ' . $u->get('remplace');
  if(!is_null($u->get('remplacepar'))) echo '<br><b> Est remplacé par l\'utilisateur : ' . $u->get('remplacepar');
  if (Session::is_directeur()) echo'<br><br>Editer l\'utilisateur : <a class = "no"'. $href .'update&mail=' . rawurlencode($u->get('mail')) . '><img src="img/edit.png"></a>';

?>
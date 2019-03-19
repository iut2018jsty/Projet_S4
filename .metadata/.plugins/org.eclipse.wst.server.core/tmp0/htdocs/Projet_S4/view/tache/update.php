<form method="post" action="index.php">
	<fieldset>
		<legend>Formulaire tâche :</legend>
		<p>
			<?php

			echo '
			<input type="hidden" name="controller" value="'. $controller .'"/>
			<input type="hidden" name="action" value="'. $action .'"/>
			<input type="hidden" name="creepar" value="' . $creepar . '" required/>	';

			if($action == 'updated') echo '<input type="hidden" name="id" value="' . $id . '"/>';

			echo'
			<label for="nom_id">Intitulé de la tâche</label> :
			<input type="text" placeholder="Ex : Diagramme de Gantt" name="intitule" value="' . $intitule . '" '. $end .'/> <br>

			<label for="nbJours_id">Heures prévues</label> :
			<input type="number" placeholder="Ex : 12" min = "1" name="heuresprevues" value="' . $heuresprevues . '" required/><br>';
				
			echo '<label for="nbJours_id">Nom du projet</label> :
			<input type="text" placeholder="Ex : Workflow" name="nomprojet" value="' . $nomprojet . '"';
			if($nomprojetnonmodifiable){
				echo ' readonly="true"';
			}
			echo ' required/>';
			echo'<br>
			<label for="nbJours_id">Tâche en cours </label> :
			<input type="radio" name="fini" value="0" '. $nonfinie .'> <br>

			<label for="nbJours_id">Tâche finie </label> :
			<input type="radio" name="fini" value="1" '. $finie .'><br>';
			if($action == "updated") echo '
	        <br> Supprimer la tâche : 
	              <button type="button" id="del"><img src="img/delete.png"></button>
	              <div id="main" class="popup">
	                <div class="popup-content">
	                  <div class="close">&times;</div>
	                  <p>Êtes-vous sûr de vouloir supprimer <b>définitivement</b> cette tâche ?</p>
	                  <button type="button" onclick="location.href = \'?controller=tache&action=delete&id=' . rawurlencode($id) . '\';">Oui</button>
	                  <button class = "annuler" type="button">Annuler</button>
	                </div>
	              </div>';
			?>
		</p>
		<p>
			<input type="submit" value="Valider" />
		</p>
	</fieldset> 
</form>
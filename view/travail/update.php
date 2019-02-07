<form method="post" action="index.php">
	<fieldset>
		<legend>Formulaire ajout travail :</legend>
		<p>
			<?php
			echo '
			<input type="hidden" name="controller" value="'. $controller .'"/>
			<input type="hidden" name="action" value="'. $action .'"/>
			<input type="hidden" name="mail" value="'. $mail .'"/>
			<input type="hidden" name="date" value="'. $date .'"/>
			<input type="hidden" name="tache" value="'. $tache .'"/>
			<label for="brand">Durée (en h)</label> :
			<input type="number" name="duree" value="' . $duree . '" min="1" max="12" required/><br>
			<label for="brand">Commentaire</label> : <br> 
			<textarea name="commentaire" placeholder="' . $placeholder . '" required>'. $commentaire .'</textarea>';
			if($action == "updated") echo '
				<br> Supprimer le travail : 
						<button type="button" id="del"><img src="img/delete.png"></button>
						<div id="main" class="popup">
							<div class="popup-content">
								<div class="close">&times;</div>
								<p>Êtes-vous sûr de vouloir supprimer <b>définitivement</b> ce travail ?</p>
								<button type="button" onclick="location.href = \'?controller=travail&action=delete&mail=' . rawurlencode($mail) . '&date='. rawurlencode($date) .'&tache='. rawurlencode($tache) . '\';">Oui</button>
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
<form method="post" action="index.php">
	<fieldset>
		<legend>Formulaire utilisateur :</legend>
		<p>
			<?php
			echo '
			<input type="hidden" name="controller" value="'. $controller .'"/>
			<input type="hidden" name="action" value="'. $action .'"/>
			<label for="mail_id">Mail</label> :
			<input type="email" placeholder="Ex : Kevindu34" name="mail" value="' . $mail . '" '. $end .'/> <br>';
			if($action == 'created') {
				echo'
				<label for="mdp">Mot de passe</label> :
				<input type="password" placeholder="Ex : *******" name="mdp" value="' . $nom . '" required/> <br>';
			}
			echo'
			<label for="color">Nom</label> :
			<input type="text" placeholder="Ex : Dupuit" name="nom" value="' . $nom . '" required/> <br>
			<label for="brand">Prenom</label> :
			<input type="text" placeholder="Ex : Kevin" name="prenom" value="' . $prenom . '" required/><br>';
			if($action == 'updated' && Session::is_user($mail)) {
				echo'<br><a href="?controller=utilisateur&action=changemdp&mail='. $mail .'">Changer de mot de passe</a><br>';
			}
			if($admin){
				echo '
				<div>
					<label for="role">Role ?</label> :
					<input type="radio" name="role" value="0" '. $empl .'> Employé
					<input type="radio" name="role" value="1" '. $mana .'> Manager
					<input type="radio" name="role" value="2" '. $dir  .'> Directeur <br>	
				</div>';
			}
			if($admin || Session::is_manager() && Session::is_user($u->get('mail'))){
				if($action == 'updated'){
					$role = $u->get('role');
					if($role == 0){
						$tab_man = ModelUtilisateur::getListe($role+1); 
						echo '<label for "responsable">Affecter cet employé à un manager : </label>
						<select name="responsable" id="responsable">';
						echo '<option value="rien">Sans responsable</option>';
						foreach ($tab_man as $man) {	
							echo '<option value="'.$man->get("mail") . '"';
							if($man->get('mail') == $u->get('responsable')) echo ' selected="selected" ';
							echo '>' . $man->afficher() . '</option>';
						}
						echo '</select><br>';
					}

					if($role < 2 && $admin){
						$tab_man = ModelUtilisateur::getListe($role+1); 
						echo '<label for "remplace">Cette utilisateur remplace : </label>
						<select name="remplace" id="remplace">';
						echo '<option value="rien">Ne remplace pas</option>';
						foreach ($tab_man as $man) {	
							echo '<option value="'.$man->get("mail") . '"';
							if($man->get('mail') == $u->get('remplace')) echo ' selected="selected" ';
							echo '>' . $man->afficher() . '</option>';
						}
						echo '</select>';
					}				
					if($role > 0) {
						$tab_man = ModelUtilisateur::getListe($role-1);
						echo '<br><label for "remplacepar">Choisir un remplacant : </label>
						<select name="remplacepar" id="remplacepar">';
						echo '<option value="rien">Pas remplacé</option>';
						foreach ($tab_man as $man) {
							echo '<option value="'.$man->get("mail") . '"';
							if($man->get('mail') == $u->get('remplacepar')) echo ' selected="selected" ';
							echo '>' . $man->afficher() . '</option>';
						}
						echo '</select>';
					}

					if($admin && $mail != 'admin') echo '<br> Supprimer l\'utilisateur : 
						<button type="button" id="del"><img src="img/delete.png"></button>
						<div id="main" class="popup">
							<div class="popup-content">
								<div class="close">&times;</div>
								<p>Êtes-vous sûr de vouloir supprimer <b>définitivement</b> cette utilisateur ?</p>
								<button type="button" onclick="location.href = \'?controller=utilisateur&action=delete&mail=' . rawurlencode($mail) . '\';">Oui</button>
								<button class = "annuler" type="button">Annuler</button>
							</div>
						</div>';	
				}
			}?>
			<br>
		</p>
		<p>
			<input type="submit" value="Envoyer" />
		</p>
	</fieldset> 
</form>

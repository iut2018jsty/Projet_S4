<form method="post" action="index.php">
	<fieldset>
		<legend>Mon compte :</legend>
		<p>
			<input type="hidden" name="controller" value="utilisateur"/>
			<input type="hidden" name="action" value="changemdpdindb"/>
			<input type="hidden" name="mail" value=<?php echo'"'. $data['mail'] .'"'?> />
			<label for="brand">Ancien mot de passe</label> : <input type="password" placeholder="******" name="mdpold" id="brand" required/><br>
			<label for="brand">Nouveau mot de passe</label> : <input type="password" placeholder="********" name="mdp" id="brand" required/><br>
			<label for="brand">Confirmez mot de passe</label> : <input type="password" placeholder="********" name="mdp2" id="brand" required/>
			<br>
		</p>
		<p>
			<input type="submit" value="Sauvegarder" />
		</p>
	</fieldset> 
</form>
<?php 
$mail = '';
if(isset($_SESSION['preremp'])) $mail = $_SESSION['preremp'];
echo '<form method="post" action="index.php">
	<fieldset>
		<legend>Connexion :</legend>
		<p>
			<input type="hidden" name="controller" value="utilisateur"/>
			<input type="hidden" name="action" value="connected"/>
			<label for="mail"> Mail </label> :
			<input type="text" placeholder="Ex : exemple@mail.fr" name="mail" value = "' . $mail . '" required/> <br>
			<label for="mdp">Mot de passe</label> :
			<input type="password" placeholder="Ex : *******" name="mdp" required/> <br>
		</p>
		<p>
			<input type="submit" value="Se connecter" />
		</p>
	</fieldset> 
</form>
';
?>
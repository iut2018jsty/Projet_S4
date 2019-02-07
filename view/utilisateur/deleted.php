<p>L'utilisateur <?php echo htmlspecialchars($data["mail"]) ?> a été correctement supprimé dans la base de donnée.</p> <br>

<br>
<?php 
require File::build_path(array("view", "utilisateur", "list.php"));
?>
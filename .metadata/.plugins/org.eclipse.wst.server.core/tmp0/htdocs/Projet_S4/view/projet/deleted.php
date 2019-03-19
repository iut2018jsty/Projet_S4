<p>Le projet <?php echo htmlspecialchars($data["nom"]) ?> a été correctement supprimé dans la base de données.</p> <br>

<?php 
require File::build_path(array("view", "projet", "list.php"));
?>
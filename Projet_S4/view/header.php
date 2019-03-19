<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/css.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $pagetitle; ?></title>
    </head>
    <body id = "page">
    	<header>
				<?php 
			if(Session::is_connected()) { 
	    	echo' <div class="menu">
	    		<img id = "extand" src="img/menu.png">
		    	<ul id = "nav">';
					if(Session::is_employe()){
						echo'
			    		<li><a href="?controller=travail&action=detailsemaine">Ma semaine</a><div/></li>
			    		<li><a href="?controller=projet&action=readAll">Mes projets</a><div/></li>
			    		<li><a href="?controller=tache&action=readAll">Mes tâches</a><div/></li>';
					} if(Session::is_manager()){
			    		echo'
			    		<li><a href="?controller=projet&action=readAll">Mes projets</a><div/></li>
			    		<li><a href="?controller=tache&action=readAll">Mes tâches</a><div/></li>
			    		<li><a href="?controller=utilisateur&action=readAll">Mes employés</a><div/></li>';
					} if(Session::is_directeur()){
						echo'
			    		<li><a href="?controller=projet&action=readAll">Projets</a><div/></li>
			    		<li><a href="?controller=tache&action=readAll">Tâches</a><div/></li>
			    		<li><a href="?controller=utilisateur&action=readAll&style_affichage=nom">Utilisateurs</a><div/></li>';
					}
					// ce que tout le monde aura a la fin
			    	echo'
			    	<li><a href="?controller=utilisateur&action=update&mail='.$_SESSION['mail'] .'">Mon compte</a><div/></li>
			    	<li><a href="?controller=utilisateur&action=deconnect">Se déconnecter</a><div/></li>';
			    
		   		    
				

		    	echo'</ul>
    		</div>';
    		} else {
			   	echo '<div class="menu_deconnecte"><ul><li><a href="?controller=utilisateur&action=connect">Se connecter</a></li></ul></div>'; 
			}?>
    	</header>
<body>
	<div id='content'>
<?php 
if(isset($err_msg) && !is_null($err_msg)) {
	echo '<div class="messageerreur"><div>' . $err_msg . '</div></div>';
}
?>
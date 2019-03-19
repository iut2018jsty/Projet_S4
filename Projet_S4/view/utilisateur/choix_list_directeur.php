<p> Comment afficher la liste des utilisateur ? 
<ul>
	<li><a href='?controller=utilisateur&action=readAll&style_affichage=nom'>Par nom</a></li>
	<li><a href='?controller=utilisateur&action=readAll&style_affichage=role'>Par rôle</a></li>
	<li><a href='?controller=utilisateur&action=readAll&style_affichage=manager'>Par manager responsable</a></li>
</ul></p>

<?php 
echo 'Ajouter un utilisateur : <a class = "no" href = "?controller=utilisateur&action=create"><img src="img/plus.png"></a>';
?>
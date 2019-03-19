/*********pop up*********/
if ((document.getElementById('main') && document.getElementById("del") && document.getElementsByClassName("close")[0] && document.getElementsByClassName("annuler")[0]) !== null) {
	var popup = document.getElementById('main');

	// bouton supprimer
	var del = document.getElementById("del");

	// boutons fermer (resp. croix et annuler)
	var div = document.getElementsByClassName("close")[0]; //1er element (index 0) de la classe close
	var button = document.getElementsByClassName("annuler")[0]; //1er element (index 0) de la classe annuler

	del.onclick = function() {
		popup.style.display = "block";
	}

	div.onclick = function() {
		popup.style.display = "none";
	}

	button.onclick = function() {
		popup.style.display = "none";
	}

	//pour fermer le popup en cliquant en dehors de celui-ci
	window.onclick = function(event) {
		if (event.target == popup) { //target = retourne l'élément qui a trigger l'event
			popup.style.display = "none";
		}
	}
}



/*********menu*********/
if ((document.getElementById('extand') && document.getElementsByClassName('menu')[0]) && document.getElementById('nav') && document.getElementById('page') !== null) {
	var extand = document.getElementById('extand'); // le burger
	var menu = document.getElementsByClassName('menu')[0];
	var nav = document.getElementById('nav');
	var page = document.getElementById('page');

	// console.log(page);

	$(window).resize(function() {
		if ($(window).width() > 850) {
			// si la width de la page est grande tu affiches le menu
			nav.style.visibility = "visible";
			nav.style.opacity = "1";
			nav.style.transition = "0s";
			menu.style.height = "70px";
			extand.src = "img/menu.png";
		} else if ($(window).width() <= 850 && menu.style.height == "240px") {
			// si la page change de taille mais qu'elle est toujours petite, le menu reste
			nav.style.visibility = "visible";
			nav.style.opacity = "1";
			nav.style.transition = "0s";
		} else {
			// si la width de la page est petite tu caches le menu
			nav.style.visibility = "hidden";
			nav.style.opacity = "0";
			nav.style.transition = "0s";
		}
	});

	extand.onclick = function() {
		if (menu.style.height == "240px") {
			// si je clique et que le menu est ouvert tu le fermes et tu caches tout 
			menu.style.height = "70px";
			nav.style.visibility = "hidden";
			nav.style.opacity = "0";
			nav.style.transition = ".2s";
			extand.src = "img/menu.png";
		} else {
			// si je clique et que le menu est fermé tu l'ouvres et tu affiches tout 
			menu.style.height = "240px";
			nav.style.visibility = "visible";
			nav.style.opacity = "1";
			nav.style.transition = ".2s";
			extand.src = "img/menu2.png";
		}
	}
}


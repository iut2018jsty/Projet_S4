<h3>
	<?php 
	// remise dans le bon sens de la date
	list($tmp[0],$tmp[1], $tmp[6], $tmp[7]) = array($tmp[6],$tmp[7], $tmp[0], $tmp[1]);
	echo " - Semaine du " . $tmp . " pour l'utilisateur : " . $user->afficher();
	?>
</h3>
<div id="tablewitharrows" >
	<div id="arrow_left">
		<?php
		$mailcible = '';
		if(isset($data['mail'])) $mailcible = '&mail=' . $data['mail'];
		$weekcible = '&week=-1';
		if(isset($data['week'])) $weekcible = '&week=' . strval(intval($data['week'] -1));
		$href = "?action=detailsemaine&controller=travail";
		echo "<a class = 'no' href='". $href . $mailcible . $weekcible . "'>";
		?>
			<img src="img/arrow_left.jpg" class="arrow">	
		</a>
	</div>
	<table>

		<thead class="projet">
			<td/>
			<td>Lundi</td>
			<td>Mardi</td>
			<td>Mercredi</td>
			<td>Jeudi</td>
			<td>Vendredi</td>
		</thead>

		<tfoot class="projet">
			<tr>
				<td>Total</td>
				<?php
				foreach ($week as $day) {
					$totaljour = ModelTravail::totalDuJour($mail, $day);
					if(!$totaljour) $totaljour = 0;
					echo'<td>' . strval($totaljour) . 'h</td>';
				}?>
			</tr>
			<?php
				echo '<tr id="valideounon"><td/>';
				if(!isset($data['week']) || $data['week'] == 0 || $data['week'] == -1){
					foreach ($week as $day) {
						$totaljour = ModelTravail::totalDuJour($mail, $day);
						if(!$totaljour) $totaljour = 0;
						if($totaljour > 12 || $totaljour < 4){
							echo '<td><img src="img/cross.png"/></td>';
						} else {
							echo '<td><img src="img/checked.png"/></td>';
						}
					}
				};
				echo '</tr>';
			?>
		</tfoot>

		<tbody>
			<?php 
			// taches : array avec idtache => nomprojet de la tache
			// projets : array avec key => nom projet
			$debutlien = '<a class = "no" style="margin-left : 10px; parding:1px" href="?controller=travail&action=';
			$finlien = '"><img src="img/edit.png" id="pencil"></a>';

			foreach ($projets as $nom) {
				echo '<tr class="projet"><td>';
					echo '<a href="?controller=projet&action=read&nom='.$nom[0].'">' . $nom[0] . '</a>';
					echo '</td><td/><td/><td/><td/><td/>';
				echo '</tr>';


				echo '<tr>';
				foreach ($taches as $idtache => $nomptache) {
					echo '<tr>';
					if($nom[0] == $nomptache){
						echo '<td><a class = "lien" href="?controller=tache&action=read&id=' . $idtache . '">' . ModelTache::getNom($idtache) . '</a></td>';
						foreach ($week as $day) {
							$duree = ModelTravail::dureeTravail($mail, $day, $idtache);
							if($duree == 0) {
								$heure = '';
								$action = 'create';
							}
							else {
								$heure = $duree . 'h';
								$action = 'update';
							}
							$link = $debutlien . $action .
									'&mail=' . rawurlencode($mail) .
									'&date=' . rawurlencode($day) .
									'&tache=' . rawurlencode($idtache) . $finlien; 
							if(!Session::is_employe()) $link = '';
							echo '<td>'. $heure . $link . '</td>';
						}
					}
					echo '</tr>';
				}
			}
			?>
		</tbody>

	</table>
	<div id="arrow_right">
		<?php
		// réutilisation de mailcible et href de arrow_left
		$weekcible = '&week=1';
		if(isset($data['week'])) $weekcible = '&week=' . strval(intval($data['week'] +1));
		echo "<a class = 'no' href='". $href . $mailcible . $weekcible . "'>";
		?>
			<img src="img/arrow_right.jpg" class="arrow">
		</a>
	</div>

</div>

<div id="help"><div>&#10132;</div> Déplacez-vous pour visionner &#10132;</div>

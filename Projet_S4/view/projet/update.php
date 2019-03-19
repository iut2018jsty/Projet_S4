<form method="post" action="index.php">
  <fieldset>
    <legend>Formulaire projet :</legend>
    <p>
      <?php
      echo '
      <input type="hidden" name="controller" value="'. $controller .'"/>
      <input type="hidden" name="action" value="'. $action .'"/>
      <input type="hidden" name="creepar" value="' . $creepar . '" required/>

      <label for="nom">Intitulé du projet</label> :
      <input type="text" placeholder="Ex : Flux de travail" name="nom" value="' . $nom . '" '. $end .'/> <br>

      <label for="joursprevus">Temps en jours prévu</label> :
      <input type="number" placeholder="Ex : 52" min = "1" name="joursprevus" value="' . $joursprevus . '" required/> <br>';

      $tab_man = ModelUtilisateur::getListe(1); 
      echo '<label for "gerepar">Gérant du projet : </label>
      <select name="gerepar" id="gerepar">';
      foreach ($tab_man as $man) {  
        echo '<option value="'.$man->get("mail") . '"';
        if($action == 'updated' && $man->get('mail') == $gerepar) echo ' selected="selected" ';
        echo '>' . $man->afficher() . '</option>';
      }
      echo '</select><br>';

      echo'<label for="nbJours_id">Projet en cours </label> :
      <input type="radio" name="fini" value="0" '. $nonfinie .'> <br>

      <label for="nbJours_id">Projet fini </label> :
      <input type="radio" name="fini" value="1" '. $finie .'><br>';
      
        if($action == "updated") echo '
        <br> Supprimer le projet : 
              <button type="button" id="del"><img src="img/delete.png"></button>
              <div id="main" class="popup">
                <div class="popup-content">
                  <div class="close">&times;</div>
                  <p>Êtes-vous sûr de vouloir supprimer <b>définitivement</b> ce projet ?</p>
                  <button type="button" onclick="location.href = \'?controller=projet&action=delete&nom=' . rawurlencode($nom) . '\';">Oui</button>
                  <button class = "annuler" type="button">Annuler</button>
                </div>
              </div>';
      
      ?>
      <br>
    </p>
    <p>
      <input type="submit" ualue="Valider" />
    </p>
  </fieldset> 
</form>
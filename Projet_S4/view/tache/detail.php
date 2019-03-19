<?php
    $et = $t->get('fini');
    if($et == 0) {
        $et = "non terminée";
    }
    else {
        $et = "terminée";
    }
    $href = 'href=?controller=tache&action=';
    $createur = ModelUtilisateur::select($t->get('creepar'));
    echo "<p><b>Nom tâche : </b>" . $t->get('intitule') . "<br>
    <b>Heures prévues : </b>" . $t->get('heuresprevues') . "<br>
    <b>Créé par : </b>" . $createur->afficher() . "<br>
    <b>Nom projet : </b>" . $t->get('nomprojet') ."<br>

    <b> Etat de la tâche : </b> ". $et ."<br>
    <b>Employés affectés : </b>"; 
    $tmp = "<ul>";
    $aff = array();
    foreach(ModelVoit::employesAffectes($t) as $mail){
        $aff[]=$mail[0];
    }
    if (empty($aff)) echo 'Aucun'; 
    else {
        foreach($aff as $value) {
            $user = ModelUtilisateur::select($value);
            $remove = '';
            if (Session::is_manager()) $remove = '<a class="no" href="?action=unselect&controller=tache&id='. rawurlencode($data['id']) .'&mail='. rawurlencode($value) .'"><img class="smallimg" src="img/remove.png"/></a>';
            $tmp .= '<li>' . $user->afficher() . $remove . '</li>';
        }
    }

    echo $tmp . "</ul></p>";
    if (Session::is_manager()){
        echo "<form method='post' action='index.php'>
        <input type='hidden' name='controller' value='tache'/>
        <input type='hidden' name='action' value='affecterEmploye'/>
        <input type='hidden' name='id' value='".$t->get('id')."'/>";
        $i = 0;
        $possibilites = array();
        echo '<div style="display : flex; flex-wrap : wrap;">';
        foreach(ModelUtilisateur::getListeSelonManager($_SESSION['mail']) as $emp){
            $mail= $emp->get('mail');
            if(!in_array($mail, $aff)){
                $possibilites[] = 1;
                echo "<div><input type='checkbox' name='".$i."' value='".$mail."'> ".$emp->afficher() . '</div>';
            } 
            $i +=1;
        }
        echo '</div>';
        if(!empty($possibilites)){
            echo "<p><input type='submit' value='Ajouter' /></p>
            </form>";
        }

    }
    if(Session::is_directeur() || (Session::is_manager() && Session::is_user($t->get('creepar')))) {
        echo 'Editer la tâche : <a class = "no" '. $href .'update&id=' . rawurlencode($t->get('id')) . '><img src="img/edit.png"></a>';
    }
    foreach ($coms as $key => $value) {
        $user = ModelUtilisateur::select($value->get('mail'));
        $date = $value->get('date');
        // remise dans le bon sense
        list($date[6], $date[7], $date[8], $date[9], $date[5], $date[3], $date[4], $date[2], $date[0], $date[1]) = str_split($date);
        echo '<div class="commentaireaff">';
        echo '<div><b>' . $user->afficher() . '</b> :  le '. $date .' ('. $value->get('duree') .'h)<br></div>';
        echo '<div>' . $value->get('commentaire') . '</div>';

        echo '</div><br>';

    }
    echo '<br><br>';

?>

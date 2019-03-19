<?php
	echo '<h3> - ' . htmlspecialchars($p->get('nom')) . ' ('. htmlspecialchars(ModelProjet::totalConsomme($p->get('nom'))) . '/'. htmlspecialchars($p->get('joursprevus') * 24) .'h) :</h3>';
    $gerant = ModelUtilisateur::select($p->get('gerepar'));
    $createur = ModelUtilisateur::select($p->get('creepar'));
    echo "
    <p><b>Projet géré par : </b>" . $gerant->afficher() . "<br>
    <b>Projet créé par : </b>" . $createur->afficher() . "</p>";

    if(Session::is_employe()){
        $taches = ModelTache::selectTacheEmp($_SESSION['mail']);
        $ps = array();
        for($i = 0; $i < count($taches); $i++) {$ps[] = $p;}
        // ptit trick pour changer une array d'objet en array d'un attributs de ces objets
        // le $ps c'est pour passer un argument a la fonction sinon il est relou
        $nomprojets = array_map(function($o, $p) { if($p->get('nom') == $o->get('nomprojet')) return array($o->get('intitule'), $o->get('id')); }, $taches, $ps);
        echo '<br><h3> Mes tâches sur ce projet : </h3><ul>';
        // array_unique équivalent a DISTINCT en sql, SORT_REGULAR permet de trier les objets en tant qu'objet et pas les convertir en string
        foreach(array_unique($nomprojets, SORT_REGULAR) as $tache) {
            if(is_array($tache)){
                echo '<li><a href="?controller=tache&action=read&id='. $tache[1] .'">' . $tache[0] . '</a></li>';

            }
        }
        echo '</ul>';
    } else {

    $users = ModelProjet::selectAllEmploye($p->get('nom'));
    ?>
    <div id="tablewitharrows"><table>
    
        <thead class="projet">
            <td><div> Tâches en cours </div></td>
            <?php foreach ($users as $u) echo '<td>'. $u->afficher() . '</td>';?>
            <td><div> Total </div></td>
        </thead>

        <tfoot class="projet">
            <td><div>Total</div></td>
            <?php 
            foreach ($users as $u) {
                $tmp = ModelProjet::totalHeureTravailEmploye($u->get('mail'), $p->get('nom'));
                if(!$tmp) $tmp = 0;
                echo'<td><div>' . strval($tmp) . 'h</div></td>'; 
            } 
            $sum = ModelProjet::totalConsomme($p->get('nom'));
            echo '<td><div>' . strval($sum) . '/'. ($p->get('joursprevus')*24) .'h</div></td>';
            ?>
        </tfoot>

        <tbody>
        <?php
            foreach (ModelTache::selectAllWithArgs(array('nomprojet' =>  $p->get('nom'), 'fini' => 0), 'intitule, heuresprevues') as $t) {
                echo '<tr class="projet"><td>' . $t->get('intitule') . '</td>';
                $sum = 0;
                foreach ($users as $u) {
                    $tmp = ModelTache::totalHeureTravailEmploye($u->get('mail'), $t->get('id'));
                    $sum += $tmp;
                    if($tmp == 0) echo '<td/>'; 
                    else echo'<td><div>' . strval($tmp) . 'h</div></td>';
                }
                echo '<td><div>' . strval($sum) . '/'. $t->get('heuresprevues') . 'h</div></td>';
                echo '</tr>';
            }
        ?>
        </tbody>
    </table></div>

    <p><div id="help"><div>&#10132;</div> Déplacez-vous pour visionner &#10132;</div></p>

    <?php
        $tachesnonfinies = ModelTache::selectAllWithArgs(array('nomprojet' =>  $p->get('nom'), 'fini' => 1), 'intitule, heuresprevues');
        if(!empty($tachesnonfinies)){
            echo'<h3> Tâches déja terminées : </h3><ul>';
            foreach ($tachesnonfinies as $t){
                echo '<li>'. $t->get('intitule') .'</li>';
            }
            echo'</ul><br>';
        }
    $href = 'href="?controller=projet&action=';
    if (Session::is_directeur()) echo 'Editer le projet : <a class = "no" '. $href .'update&nom=' . rawurlencode($p->get('nom')) . '"><img src="img/edit.png"></a><br><br>';
    echo"<a href='?controller=tache&action=readParProjet&nom=".rawurlencode($p->get("nom"))."'>Toutes les tâches de ce projet</a></p>";
    }
?>
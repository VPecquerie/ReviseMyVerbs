<?php 
$titre = "Apprendre des verbes";
$onglet = "apprendre";
require_once 'includes/haut.php';
is_connecte();
if(isset($_GET['action']))
$action = $_GET['action'];
else 
$action = null;

        $requete = 'SELECT v_id FROM apprendre WHERE u_id = '.$_SESSION['id'];
        $retour = $connexion->query($requete);
        $retour->setFetchMode(PDO::FETCH_OBJ);
        
        while($ligne = $retour->fetch())
            $verbesConnu[] = $ligne->v_id;

switch($action)
{
    case "verif":
        $array = $_SESSION['listeVerbe'];
        $score = $_POST['score'];
		$nbErreur = 0;
        

            
        
        for($i = 5; $i < 10; $i++)
        {
            $note = 0;
           
            if(isset($_POST['l'.$i.'c1']))
                if($_POST['l'.$i.'c1'] == $array[$i]['v_base_verbale'])  $note += 0.25;
                else $nbErreur++;
                
            if(isset($_POST['l'.$i.'c2']))
                if($_POST['l'.$i.'c2'] == $array[$i]['v_preterit'])$note += 0.25;
                else $nbErreur++;
                
            if(isset($_POST['l'.$i.'c3']))
                if($_POST['l'.$i.'c3'] == $array[$i]['v_participe_passe']) $note += 0.25; 
                else $nbErreur++;
                
            if(!empty($_POST['l'.$i.'c4']))
                if($_POST['l'.$i.'c4'] == $array[$i]['v_traduction'] OR strpos($array[$i]['v_traduction'],$_POST['l'.$i.'c4']) !== FALSE)  $note += 0.25; 
                else{  $nbErreur++; }
                      
            if($note == 0.75) 
            {   
                $note = 1;
                $id_verbe = $_POST['vid'.$i];
                if(!in_array($id_verbe,$verbesConnu))
                {
                    $verbesConnu[] = $id_verbe;
                    $requete = 'INSERT INTO apprendre VALUES('.$_SESSION['id'].','.intval($id_verbe).',1)';
                }
                else
                {
                    $requete = 'UPDATE apprendre SET nb_vu = nb_vu + 1 WHERE u_id = '.$_SESSION['id'].' AND v_id = '.intval($id_verbe);
                }
                $connexion->exec($requete);
            }
            else
            {
                $requete = 'DELETE FROM apprendre WHERE u_id = '.$_SESSION['id'].' AND v_id='.intval($_POST['vid'.$i]);
                $connexion->exec($requete);
                
            }
            
            $score += $note;    
        }
        
        $nbErreur += intval($_SESSION['nbErreur']);
        ?>
        <p>Nous avez fait : <?php echo $nbErreur; ?> erreur(s). <br /> Note : <?php echo $score; ?> / 10</p>
        <div class="row-fluid">
        <?php
        $requeteScore = 'INSERT INTO notes VALUES("",now(),'.$score.','.$_SESSION['id'].')';
        $connexion->exec($requeteScore);
        
        $array = array('', 'v_base_verbale','v_preterit','v_participe_passe', 'v_traduction');
        $comparant = $_SESSION['listeVerbe'];
        for($i = 0; $i < 5; $i++)
        {
            echo '<div class="row-fluid">';
            
            for($j = 1; $j <= 4; $j++)
            {
                if(isset($_SESSION['form']['l'.$i.'c'.$j]) and !empty($_SESSION['form']['l'.$i.'c'.$j]))
                {
                    if($comparant[$i][$array[$j]] != $_SESSION['form']['l'.$i.'c'.$j])
                    echo '<div class="span3" style="color: red;">'.$_SESSION['form']['l'.$i.'c'.$j].'<span style="color: black;"> au lieu de </span><span style="color: blue;">'.$comparant[$i][$array[$j]].'</span></div>';
                    else
                    echo '<div class="span3" style="color: blue;">'.$_SESSION['form']['l'.$i.'c'.$j].'</div>';    
                        
                }
                else if(isset($_SESSION['form']['s'.$i]) && $_SESSION['form']['s'.$i] == $comparant[$i][$array[$j]])
                {
                    echo '<div class="span3" style="color: black;">'.$comparant[$i][$array[$j]].'</div>';     
                }
                else
                {
                    echo '<div class="span3" style="color: grey;">'.$comparant[$i][$array[$j]].'</div>';                     
                }
                   
            }
            echo '</div>';
        }
        for($i = 5; $i < 10; $i++)
        {
            echo '<div class="row-fluid">';
            
            for($j = 1; $j <= 4; $j++)
            {
                if(isset($_POST['l'.$i.'c'.$j]) and !empty($_POST['l'.$i.'c'.$j]))
                {
                    if($comparant[$i][$array[$j]] != $_POST['l'.$i.'c'.$j])
                    echo '<div span class="span3" style="color: red;">'.$_POST['l'.$i.'c'.$j].'<span style="color: black;"> au lieu de </span><span style="color: blue;">'.$comparant[$i][$array[$j]].'</span></div>';
                    else
                    echo '<div span class="span3" style="color: blue;">'.$_POST['l'.$i.'c'.$j].'</div>';    
                        
                }
                else if(isset($_POST['s'.$i]) && $_POST['s'.$i] == $comparant[$i][$array[$j]])
                {
                    echo '<div span class="span3" style="color: black;">'.$comparant[$i][$array[$j]].'</div>';     
                }
                else
                {
                    echo '<div span class="span3" style="color: grey;">'.$comparant[$i][$array[$j]].'</div>';                     
                }
                   
            }
            echo '</div>';
        }
        ?>
        <a href="index.php"><button class="btn btn-success">Retour à l'accueil</button></a>
        <a href="apprendreVerbes.php"><button class="btn btn-success">Recommencer</button></a>
            <h2>Correction :</h2>
        <p>En <span style="color: blue;">bleu</span> les bonnes réponses.<br />
           En <span style="color: red;">rouge</span> les mauvaises réponses.<br />
           En <span style="color: grey;">gris</span> les réponses qui n'ont pas été renseigné.<br />
           En <span style="color: black;">noir</span> les réponses pré-remplis.</p>
        </div>
        <?php 
        break;
      
    case "test2":
        // First Step Récuperation du nombre de bonnes réponses :

        $array = $_SESSION['listeVerbe'];
        $_SESSION['form'] = $_POST;
       	$nbErreur = 0;
        $score = 0;
        for($i = 0; $i < 5; $i++)
        {
            $note = 0;
            
            if(isset($_POST['l'.$i.'c1']))
                if($_POST['l'.$i.'c1'] == $array[$i]['v_base_verbale'])  $note += 0.25;
                else $nbErreur++;
                        
            if(isset($_POST['l'.$i.'c2']))
                if($_POST['l'.$i.'c2'] == $array[$i]['v_preterit']) $note += 0.25;
                else $nbErreur++;
                
                
            if(isset($_POST['l'.$i.'c3']))
                if($_POST['l'.$i.'c3'] == $array[$i]['v_participe_passe']) $note += 0.25; 
                else $nbErreur++;
                
                
            if(!empty($_POST['l'.$i.'c4']))
            {
            	
				
            	if($_POST['l'.$i.'c4'] == $array[$i]['v_traduction'] OR strpos($array[$i]['v_traduction'],$_POST['l'.$i.'c4']) !== FALSE)  $note += 0.25; 
                else $nbErreur++;
            }   
                      
            if($note == 0.75)
            {            $note = 1;
            
                            $id_verbe = $_POST['vid'.$i];
                if(!in_array($id_verbe,$verbesConnu))
                {
                    $verbesConnu[] = $id_verbe;
                    $requete = 'INSERT INTO apprendre VALUES('.$_SESSION['id'].','.intval($id_verbe).',1)';
                }
                else
                {
                    $requete = 'UPDATE apprendre SET nb_vu = nb_vu + 1 WHERE u_id = '.$_SESSION['id'].' AND v_id = '.intval($id_verbe);
                }
                $connexion->exec($requete);
            
            $score += $note;    
            }
            else
            {
                $requete = 'DELETE FROM apprendre WHERE u_id = '.$_SESSION['id'].' AND v_id='.intval($_POST['vid'.$i]);
                $connexion->exec($requete);
                
            }
        }   
        $_SESSION['nbErreur'] = $nbErreur;
        ?>
        <form action="?action=verif" method="POST">
        <?php
            $i = 5;
            $retour = $_SESSION['listeVerbe'];
            while($i < 10 and $ligne = $retour[$i])
            {
                
                $choix = mt_rand(1,4);
                echo '<div class="row-fluid"><div class="span3">';
                echo '<input type="hidden" name="vid'.$i.'" value="'.$ligne['v_id'].'" />';
                if($choix == 1) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_base_verbale']."' />".$ligne['v_base_verbale'];
                else echo "<input type='text' name='l".$i."c1'   class='span11' />";
                 echo '</div>';
                
                echo '<div class="span3">';
                if($choix == 2) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_preterit']."' />".$ligne['v_preterit'];
                else echo "<input type='text' name='l".$i."c2'   class='span11' />";
                echo '</div>';
                
                echo '<div class="span3">';
                if($choix == 3) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_participe_passe']."' />".$ligne['v_participe_passe'];
                else echo "<input type='text' name='l".$i."c3'   class='span11' />";
                 echo '</div>';
                echo '<div class="span3">';
                if($choix == 4) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_traduction']."' />".$ligne['v_traduction'];
                else echo "<input type='text' name='l".$i."c4'  class='span11' />";
                
                echo '</div></div>';
                echo "<br />";
                $i++;
            }
        ?>
            <input type="hidden" name="score" value="<?php echo $score; ?>" />
            <button class="btn btn-success" onClick='history.back();'>Retour</button>
            <button class="btn btn-success" type="submit">Continuer</button>
        </form>
        <?php
        break;
    
    case "test1":
            $i = 0;
            $retour = $_SESSION['listeVerbe'];
            echo '<form action="?action=test2" method="POST"><div class="row-fluid">';
            while($ligne = $retour[$i] and $i < 5)
            {
                
                $choix = mt_rand(1,4);
                echo '<div class="row-fluid"><div class="span3">';
                 echo '<input type="hidden" name="vid'.$i.'" value="'.$ligne['v_id'].'" />';
                if($choix == 1) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_base_verbale']."' />".$ligne['v_base_verbale'];
                else echo "<input type='text' name='l".$i."c1'  class='span11' />";
                 echo '</div>';
                
                echo '<div class="span3">';
                if($choix == 2) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_preterit']."' />".$ligne['v_preterit'];
                else echo "<input type='text' name='l".$i."c2'  class='span11' />";
                echo '</div>';
                
                echo '<div class="span3">';
                if($choix == 3) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_participe_passe']."' />".$ligne['v_participe_passe'];
                else echo "<input type='text' name='l".$i."c3'  class='span11' />";
                 echo '</div>';
                echo '<div class="span3">';
                if($choix == 4) echo "<input type='hidden' name='s".$i."' value='".$ligne['v_traduction']."' />".$ligne['v_traduction'];
                else echo "<input type='text' name='l".$i."c4'  class='span11' />";
                 echo '</div></div>';
                echo "<br />";
                $i++;
            }
            ?>
                
        <button class="btn btn-success" onClick="history.back();">Retour</button>
            <button class="btn btn-success" type="submit">Continuer</button>
        <?php
        break;
    
    case "verbe2":
        ?>
        <table class="table table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th>Verbe infinitif</th>
                    <th>Preterit</th>
                    <th>Participe passe</th>
                    <th>Traduction</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $i = 0;
            $retour = $_SESSION['listeVerbe'];
            while($i < 10 and $ligne = $retour[$i])
            {
                if($i > 4)
                   echo "<tr><td>".$ligne['v_base_verbale']."</td><td>".$ligne['v_preterit']."</td><td>".$ligne['v_participe_passe']."</td><td>".$ligne['v_traduction']."</td></tr>";
                $i++;
            }
            ?>
            </tbody>
        </table>
           <a href="apprendreVerbes.php?action=verbe1" class="btn btn-success">Retour</a>
           <a href="apprendreVerbes.php?action=test1" class="btn btn-success">Continuer</a>
        <?php
        break;
    
    case "verbe1":
        $retour = $_SESSION['listeVerbe'];
        ?>
        <table class="table table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th>Verbe infinitif</th>
                    <th>Preterit</th>
                    <th>Participe passe</th>
                    <th>Traduction</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $i = 0;
            while($ligne = $retour[$i] and $i < 5)
            {
                echo "<tr><td>".$ligne['v_base_verbale']."</td><td>".$ligne['v_preterit']."</td><td>".$ligne['v_participe_passe']."</td><td>".$ligne['v_traduction']."</td></tr>";
                $i++;
            }
            ?>
            </tbody>
        </table>
           <a href="apprendreVerbes.php?action=verbe2" class="btn btn-success">Continuer</a>
           <?php
        break;
    default:        
        $requete = 'SELECT * FROM verbes WHERE RAND() > 0.9 AND v_id NOT IN	(SELECT v_id FROM apprendre WHERE u_id = '.$_SESSION['id'].' AND nb_vu > 5) LIMIT 0,10';
        $retour = $connexion->query($requete);
        $retour->setFetchMode(PDO::FETCH_ASSOC)or die("Action impossible !");
        $_SESSION['listeVerbe'] = $retour->fetchAll();
        
        ?>
        <h2>Apprendre des verbes</h2>
        <p>L'apprentissage se déroule en 4 étapes et vous fera apprendre 10 verbes. Dans un premiers temps, vous aurez à apprendre 5 verbes puis 5 autres. Vous aurez ensuite un test sur les 5 premiers verbes puis les 5 suivants. Quand vous vous sentez prêt, cliquez sur commencer.</p>
        <a class="btn btn-success" href="apprendreVerbes.php?action=verbe1">Commencer</a>
            <br />
        <?php 

        break;
}
require_once 'includes/bas.php';

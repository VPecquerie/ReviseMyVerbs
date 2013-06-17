<?php 
$titre = "Liste des verbes";
$onglet = "liste";
require_once("includes/haut.php");
$requete = "SELECT * FROM verbes WHERE v_niveau <= ".intval($_SESSION['niveau']);
$retour = $connexion->query($requete)or die("Action impossible !");
$retour->setFetchMode(PDO::FETCH_OBJ)or die("Action impossible !");
if($retour->rowCount() > 0)
{
?>
<br />
<h3>Liste des <?php echo $retour->rowCount(); ?> verbes irr&eacute;guliers correspondant &agrave; votre niveau : </h3>
        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>

                    <th>Base Verbale</th>
                    <th>Prétérit</th>
                    <th>Participe passé</th>
                    <th>Traduction</th>
            </tr>
            </thead>

            <tbody>
        <?php
            while($ligne = $retour->fetch())
            {
                echo "<tr><td>".$ligne->v_base_verbale."</td><td>".$ligne->v_preterit."</td><td>".$ligne->v_participe_passe."</td><td>".$ligne->v_traduction."</td></tr>";
            }
        ?>
            </tbody>
        </table>
        
        <?php
}
else { echo "Aucun verbe"; };
require_once("includes/bas.php");

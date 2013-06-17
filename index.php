<?php 
require_once("includes/haut.php");
$requete = $connexion->query("SELECT * FROM actualites WHERE n_datePublication < NOW() ORDER BY n_datePublication");
$requete->setFetchMode(PDO::FETCH_OBJ);
if(isset($_GET['code'])) $code = intval($_GET['code']);
if(isset($code) && $code != 0) 
{ 
?>
    <div id="MessageAffichage" class="alert alert-info">
        <h4 class="alert-heading">Message n°<?php echo $code; ?> !</h4>
            <p>
            <?php 
            switch($code)
            {
                case 1: 
                    echo  "Connexion effectuée avec succès !";
                break;
                
                case 2:
                    echo "Modifications efféctuées avec succès !";
                break;
                case 3:
                	echo  "Déconnexion effectuée avec succès !";
                break;
            }
            ?>
            </p>
    </div>
<?php 
} 
?>
<?php if(!isset($_SESSION['pseudo']) OR !isset($_SESSION['id'])) { ?>
<p class="bienvenue">
    Bienvenue sur ReviseMyVerb ! Ce site est une application dédiée à l’apprentissage des verbes irréguliers. Pour l'utiliser, vous devez vous inscrire sur <a href="inscription.php">la page d’inscription</a> ou vous pouvez aussi utiliser le compte de démo (nom d’utilisateur <b>login</b> et mot de passe <b>motdepasse</b>) pour tester l’application. Les comptes personnels permettent un suivi individuel.
</p>
<?php } ?>
<?php
while ($ligne = $requete->fetch())
{
?>
<div class="row-fluid">
    <h1><?php echo $ligne->n_titre; ?></h1>
    
    <p><?php echo nl2br($ligne->n_contenu); ?></p>
    
</div>
<?php
}
require_once("includes/bas.php");
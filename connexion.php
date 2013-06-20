<?php   
$titre = "Connexion";
$onglet = "connexion";
require_once("includes/haut.php");
if(isset($_GET['code'])) $code = intval($_GET['code']);
if(!isset($_POST['pseudo']) && !isset($_POST['pass']))
{
?>
    <br />
    <form action="connexion.php" method="POST" class="well form-horizontal">


        <fieldset>

        <?php if(isset($code) && $code != 0) 
              { 
        ?>
                <div class="alert alert-error">
                    <h4 class="alert-heading">Erreur n°<?php echo $code; ?> !</h4>
                    <p>
                        <?php 
                        switch($code)
                        {
                           case 1: 
                               echo "Tout les éléments du formulaire n'ont pas été remplis.";
                           break;
                           
                           case 2:
                               echo "Votre compte n'est pas activé. Veuillez regarder vos e-mail.";
                           break;
                       
                           case 3:
                               echo "Votre mot de passe est érronné.";
                           break;
                       
                           case 4:
                               echo "Le compte utilisateur est introuvable.";
                           break;
                           
                           case 5:
                           		echo "Vous devez être connecté pour accéder à cette page !";
                           	break;
                        }
                        ?>
                    </p>
                </div>
        <?php 
                } 
        ?>



            <legend>Connexion :</legend>

        <div class="control-group">
	<label class="control-label" for="pseudo">Nom d'utilisateur :</label>
	<div class="controls">
		<input type="text" name="pseudo" required/>
	</div>
        </div>
        
        <div class="control-group">

	<label class="control-label" for="pass">Mot de passe :</label>
	<div class="controls">
		<input type="password" name="pass" required />
	</div>

        </div>
        
        <div class="control-group">

	<label class="control-label" for="valider"></label>
	<div class="controls">		
		<button class="btn" type="submit"><i class="icon-off"></i> Se connecter</button>
		<button type="reset" value="Annuler"  class="btn">Annuler</button>
	</div>
        <br />

            <ul>
                <li>Pas encore inscrit ?</li>
                <li>Mot de passe oublié ?</li>
 
            <li>Compte non activé ?</li>
            <li>Renvoyer un e-mail de validation ?</li>
        </ul>

</div>	
        </fieldset>
    </form>
<?php
}
else
{
    if(isset($_POST['pseudo']) && isset($_POST['pass']))
    {
        $pseudo = $connexion->quote($_POST['pseudo']);
        $pass   = $_POST['pass'];
        
        $requete = "Select * from utilisateurs WHERE u_pseudo = ".$pseudo;
        $retour = $connexion->query($requete);
        $retour->setFetchMode(PDO::FETCH_OBJ);
        if($retour->rowCount() > 0)
        {
            $data = $retour->fetch();
            if($data->u_actif)
            {
                if(sha1($pass) == $data->u_mdp)
                {
                    $_SESSION['id'] = $data->u_id;
                    $_SESSION['pseudo'] = $data->u_pseudo;
                    $_SESSION['niveau'] = $data->u_niveau;
                    header('location: index.php?code=1');
                }
                else { header('location: connexion.php?code=3'); }
            }
            else { header('location: connexion.php?code=2'); }
        }
        else  { header('location: connexion.php?code=4'); }
    }
    else { header('location: connexion.php?code=1'); }
}
require_once("includes/bas.php");

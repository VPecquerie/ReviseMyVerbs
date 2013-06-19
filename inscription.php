<?php 
$titre = "Inscription";
$onglet = "inscription";
require_once("includes/haut.php");
if(!isset($_POST['pseudo']))
{       
        if(isset($_GET['code']))
            $code = intval($_GET['code']);
        else 
            $code = 0;
        
        if(isset($code) && $code != 0) 
        { 
        ?>
                <div class="alert alert-error">
                    <h4 class="alert-heading">Erreur n°<?php echo $code; ?> !</h4>
                    <p>
                        <?php 
                        switch($code)
                        {
                            case 1:
                                echo "Tous les éléments du formulaire n'ont pas été renseignés !";
                                break;
                            
                            case 2:
                                echo "Le mot de passe et sa confirmation ne concorde pas !";
                                break;
                            case 3:
                                echo "Le mot de passe est trop court (minimum 6 caractères) !";
                                break;
                            case 4:
                                echo "L'email n'a pas une forme correcte !";
                                break;
                            case 5: 
                                echo "Un compte existe déjà avec cette adresse e-mail !";
                                break;
                            case 6:
                                echo "Un compte existe déjà avec ce nom d'utilisateur !";
                                break;
                            case 7:
                                echo "Une erreur interne est survenu !";
                                break;
                        }
                        ?>
                    </p>
                </div>
        <?php 
        } 
        ?>

        <form method="POST" class="well form-horizontal">
            <fieldset>
                <legend>Inscription :</legend>
                
                    <div class="control-group">
                        <label class="control-label" for="pseudo">Nom d'utilisateur :</label>
                        <div class="controls">
                                <input type="text" name="pseudo" required/>
                        </div>
                    </div>
                    <br />
                    <div class="control-group">
                        <label class="control-label" for="password">Mot de passe :</label>
                        <div class="controls">
                                <input type="password" name="password" required/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="confirmation">Confirmation :</label>
                        <div class="controls">
                            <input type="password" name="confirmation" required/>
                        </div>
                    </div>
                    <br />
                    <div class="control-group">
                        <label class="control-label" for="email">Email :</label>
                        <div class="controls">
                            <input type="email" name="email" required/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="email-confirmation">Confirmation :</label>
                        <div class="controls">
                            <input type="email" name="email-confirmation" required/>
                        </div>
                    </div>
                    
                   <div class="control-group">
                        <label class="control-label" for="niveau">Niveau:</label>
                        <div class="controls">
                        <?php 
                            $requete = "SELECT * FROM niveau ORDER BY n_id";
                            $retour = $connexion->query($requete);
                            $retour->setFetchMode(PDO::FETCH_OBJ);
                        ?>
                       
                            <select name="niveau">
                                <?php while($data = $retour->fetch()) { ?>                
                                    <option value="<?php echo $data->n_id; ?>">
                                        <?php echo $data->n_titre; ?>
                                    </option>
                                <?php } ?>
                            </select>
               
                  
                        </div>
                    </div>
                    <div class="control-group">
                        
                        <div class="controls">
                            <button type="reset" class="btn btn-danger">Annuler</button> &nbsp;&nbsp;
                            <button type="submit" class="btn btn-success">Valider</button>
                        </div>
                    </div>
            </fieldset>
        </form>

    <?php
}
else
{
    if(!empty($_POST['pseudo']) && !empty($_POST['password']) && !empty($_POST['confirmation']) && !empty($_POST['email']) && !empty($_POST['email-confirmation']))
    {
        $login = $connexion->quote($_POST['pseudo']);
        $mdp = $connexion->quote(sha1($_POST['password']));
        $confirmation = $connexion->quote(sha1($_POST['confirmation']));
        $email = $connexion->quote($_POST['email']);
        $emailConfirmation = $connexion->quote($_POST['email-confirmation']);
        if($mdp == $confirmation)
        {
            if(strlen($_POST['password']) >= 6)
            {
                if(preg_match("#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#", $_POST['email']))
                {
                    $requete = "SELECT u_id FROM utilisateurs WHERE u_email = ".$email;
                    $requete2 = "SELECT u_id FROM utilisateurs WHERE u_pseudo = ".$login;
                    if($connexion->query($requete)->rowCount() == 0)
                    {
                        if($connexion->query($requete2)->rowCount() == 0)
                        {
                            $requete = "INSERT INTO utilisateurs VALUES('',".$login.",".$mdp.",".$email.",1,".intval($_POST['niveau']).")";
                            if($connexion->query($requete))
                            {
                                header("location: index.php");
                            }
                            else header("location: ?code=7");
                        }
                        else header ("location: ?code=6");
                    }
                    else header ("location: ?code=5");
                }
                else header("location: ?code=4");
            }
            else header("location: ?code=3");   
        }
        else header ("location: ?code=2");
    }
    else header("location: ?code=1"); 
   
}
require_once("includes/bas.php");

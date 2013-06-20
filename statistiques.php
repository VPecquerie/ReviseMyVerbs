<?php 
$titre = "Statistiques";
$onglet = "stat";
require_once("includes/haut.php");
is_connecte();

	// Partie modele
	$requete = 'SELECT n_id, n_date, n_valeur FROM notes WHERE u_id = '.$_SESSION['id'].' ORDER BY n_id DESC LIMIT 0,30';
	$retour = $connexion->query($requete);
	$retour->setFetchMode(PDO::FETCH_OBJ);
	
	
	// partie controlleur
	while($ligne = $retour->fetch())
		$data[$ligne->n_date] = $ligne->n_valeur;
	
	ksort($data);
	
	
	$requete = 'SELECT COUNT(*) as appris, (SELECT COUNT(*) FROM verbes WHERE v_niveau <= '.$_SESSION['niveau'].') as a_apprendre FROM apprendre WHERE u_id = '.$_SESSION['id'];
	$retour = $connexion->query($requete);
	$retour->setFetchMode(PDO::FETCH_OBJ);
	$ligne = $retour->fetch();
	$pourcent = round(($ligne->appris/$ligne->a_apprendre)*100, 1);
	
	// Partie vue
	?>
	
	    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
             ['Date', 'Note'],
             <?php 
             foreach($data as $key => $element) 
             {
				echo "['".$key."', ".$element."],";
             }	
             ?>
        ]);

        var options = {
          title: 'Vos Résultats :'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	<div id="chart_div" style="height: 300px;" class="span10"></div>
	<div class="offset1 span10"><h3>Progression : <?php echo $pourcent; ?> % soit <?php echo $ligne->appris; ?> verbes appris sur <?php echo $ligne->a_apprendre ?> verbes.</h3>
	

	
	</div>
	<br /><br />
	
	<table class="table">
		<tr>
			<th>Date</th>
			<th>Note obtenu</th>
		</tr>
		<?php foreach($data as $key => $element){?>
		<tr>
			<td><?php echo date('d/m/Y à H:i:s', strtotime($key)); ?></td>
			<td><?php echo $element; ?></td>
		</tr>
		<?php } ?>
	</table>
<?php 
require_once("includes/bas.php");
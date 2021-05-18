<?php
	include 'header.php';
	
	if(isset($_POST['send'])) // Si on veut enregistrer une rencontre (modifiée ou non)
	{		
		if (isset($_POST['jour']) && isset($_POST['mois']) && isset($_POST['guilde']) && isset($_POST['points_gagnes']))
		{
			$timestamp = mktime(0, 0, 0, intval($_POST['mois']), intval($_POST['jour']), intval($_POST['annee']));
			$guilde = ucwords(strtolower(addslashes($_POST['guilde'])));
			$points_gagnes = htmlspecialchars($_POST['points_gagnes']);
			$classement = intval($_POST['classement']);
			$points = intval($_POST['points']);
			$id_gvg = addslashes($_POST['id_gvg']);
	
			// Si ce n'est pas une modification			
			if ($_POST['id_gvg'] == 0)
			{
				if($points == 0) // Si le score n'a pas été rentré, on le calcul automatiquement à partir du résultat précédent
				{
					$sql = 'SELECT points, timestamp
							FROM ' . SQL_PREFIX . 'gvg
							ORDER BY g_id DESC LIMIT 0, 1';
					$result = Fsb::$db->query($sql);
					$donnees = Fsb::$db->rows($result);
					Fsb::$db->free($result);
					
					$arr = str_split($points_gagnes);
					if($arr[0] == '+')
					{
						if(array_key_exists(2, $arr))
						{
							$points = $donnees[0]['points'] + ($arr[1] . $arr[2]);
						}
						else
						{
							$points = $donnees[0]['points'] + $arr[1];
						}
					}
					elseif($arr[0] == '-')
					{
						if(array_key_exists(2, $arr))
						{
							$points = $donnees[0]['points'] - ($arr[1] . $arr[2]);
						}
						else
						{
							$points = $donnees[0]['points'] - $arr[1];
						}
					}
					else
					{
						// Dans le cas où l'utilisateur n'aurait pas rentré correctement les points
						echo '<div align="center">Les points rentr&eacute;s ne correspondent pas au points de Guild Wars.';
						exit();
					}
				}
				
				// On insert le tout dans la BDD
				Fsb::$db->insert('gvg', array(
					'timestamp' =>		$timestamp,
					'guilde' 	=>		$guilde,
					'points_gagnes'	=>	$points_gagnes,
					'points' =>			$points,
					'classement' =>		$classement,
				));
			}
			else
			{
				Fsb::$db->update('gvg', array(
					'timestamp' =>		$timestamp,
					'guilde' =>			$guilde,
					'points_gagnes' =>	$points_gagnes,
					'points'	=>		$points,
					'classement' =>		$classement,
				), 'WHERE g_id = ' . $id_gvg);
			}
		}
		echo '<meta http-equiv="refresh" content="0; url=index_gvg.php" />';
		exit();
	}
	else
	{
		if ($fsb->is_logged() && ($fsb->userdata('u_auth') >= 3 || $fsb->userdata['u_color'] == 'style="color: #CCAA00; font-weight: bold"' || $fsb->userdata['u_color'] == 'style="color: #00AA77; font-weight: bold"'))
		{	
			if (isset($_GET['modifier_gvg']))
			{
				// On protège la variable "modifier_news" pour éviter une faille SQL
				$_GET['modifier_gvg'] = mysql_real_escape_string(htmlspecialchars($_GET['modifier_gvg']));
		
				$sql = 'SELECT * 
						FROM ' . SQL_PREFIX . 'gvg 
						WHERE g_id=' . $_GET['modifier_gvg'];
				$result = Fsb::$db->query($sql);
				$donnees = Fsb::$db->row($result);
				
				$jour = date('j', $donnees['timestamp']);
				$mois = date('n', $donnees['timestamp']);
				$annee = date('y', $donnees['timestamp']);
				$guilde = stripslashes($donnees['guilde']);
				$points_gagnes = stripslashes($donnees['points_gagnes']);
				$classement = intval($donnees['classement']);
				$points = intval($donnees['points']);
				$id_gvg = $donnees['g_id']; // Cette variable va servir pour se souvenir que c'est une modification
			}
			else // C'est qu'on rédige une nouvelle news
			{
				// Les variables $titre et $contenu sont vides, puisque c'est une nouvelle news
				$jour = date('j');
				$mois = date('n');
				$annee = date('y');
				$guilde = '';
				$points_gagnes = '';
				$classement = 0;
				$points = 0;
				$id_gvg = 0; // La variable vaut 0, donc on se souviendra que ce n'est pas une modification
			}
		?>
<script type="text/javascript" src="js/ajax_gvg.js"></script>
<script type="text/javascript">
var divOnMouseDown=function(){
  _inputField.value=getSuggestion(this);
//  _documentForm.submit()
};

window.onload = function(){initAutoComplete(document.getElementById('gvg'),
document.getElementById('guilde'))};
</script>
		<form action="rediger_gvg.php" method="post" name="gvg" id="gvg">
		<p>Guilde : <input type="text" size="50" name="guilde" id="guilde" value="<?php echo $guilde; ?>" autocomplete="off" /></p>
		<p>Progression : <input type="text" size="3" name="points_gagnes" value="<?php echo $points_gagnes; ?>" /> Score : <input type="text" size="4" name="points" value="<?php echo $points; ?>" /><br/><i>La progression correspond au nombre de points gagn&eacute;s (+/- 1, 2 ... ou 0 en cas d'&eacute;galit&eacute;). <b>Il est possible de laisser le score &agrave; 0, il sera calcul&eacute; automatiquement en fonction du dernier score.</b></i></p>
		<p>Le classement : <input type="text" size="10" name="classement" value="<?php echo $classement; ?>" /><br/><i>Le classement correspond au classement de notre guilde apr&egrave;s le combat.</i></p>
		<p>Le : <select name="jour">
				<?php for($i = 1; $i <= 31; $i++){ ?>
						<option value="<?php echo ($i . '" '); if($i == $jour){echo 'selected="selected"';} echo (' >' . $i); ?></option>
				<?php } ?>
				</select>
				<select name="mois">
				<?php for($j = 1; $j <= 12; $j++){ ?>
						<option value="<?php echo ($j . '" '); if($j == $mois){echo 'selected="selected"';} echo (' >' . $j); ?></option>
				<?php } ?>
				</select>
				<select name="annee">
					<option value="08" <?php if($annee == '08'){echo 'selected="selected"';}?>>08</option>
					<option value="09" <?php if($annee == '09'){echo 'selected="selected"';}?>>09</option>
					<option value="10" <?php if($annee == '10'){echo 'selected="selected"';}?>>10</option>
					<option value="11" <?php if($annee == '11'){echo 'selected="selected"';}?>>11</option>
					<option value="12" <?php if($annee == '12'){echo 'selected="selected"';}?>>12</option>
				</select>
		</p>
			<input type="hidden" name="id_gvg" value="<?php echo $id_gvg; ?>" />
			<input type="submit" name="send" value="Envoyer" />
		</form>
		<?php 	
		// Si on a eu recours à la BDD, on libère la mémoire
		if(isset($_GET['modifier_gvg']))
					Fsb::$db->free($result); ?>
		
		<br/>
		<a href="gerer_gvg.php">Retourner &#xE0; la liste des gvg</a>
		
		<?php
		}
		else
		{
			echo 'Vous n\'&#xEA;tes pas connect&eacute; ou n\'avez pas l\'autorisation d\'acc&eacute;der &#xE0; cette page.';
		}
		include 'footer.php';
	}
?>
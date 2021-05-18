<?php
	include 'header.php';
	
if ($fsb->is_logged() && ($fsb->userdata('u_auth') >= 3 || $fsb->userdata('u_color') == 'style="color: #CCAA00; font-weight: bold"' || $fsb->userdata('u_color') == 'style="color: #00AA77; font-weight: bold"'))
{	
	if (isset($_POST['supprimer_gvg']) && isset($_POST['conf_yes'])) // Si on veut vraiment supprimer la rencontre
	{
	    // Alors on supprime la rencontre correspondante
	    $sql = 'DELETE 
				FROM ' . SQL_PREFIX . 'gvg 
				WHERE g_id=' . $_POST['supprimer_gvg'];
		Fsb::$db->query($sql);

		$continue = TRUE;
	}
	elseif(isset($_GET['supprimer_gvg'])) // On demande une confirmation avant de supprimer la rencontre
	{
		// On protège la variable "id_gvg" pour éviter une faille SQL
		$id_gvg = addslashes($_GET['supprimer_gvg']); ?>
		<center>
		<form action="gerer_gvg.php" method="post"><br />
			<label>Voulez-vous supprimer cette rencontre ?</label>
			<input type="hidden" name="supprimer_gvg" value="<?php echo $id_gvg ?>" /><br /><br />
			
			<input type="submit" name="conf_yes" value="Oui" />
			<input type="submit" name="conf_no" value="Non" />
		</form>
		</center>
		<?php
		$continue = FALSE;
	}
	else
	{
		$continue = TRUE;
	}
	
	if($continue == TRUE)
	{
?>
<a class="submit" href="rediger_gvg.php">Cr&eacute;er une rencontre</a><br/><br/>
<table align="center">
	<tr>
		<th class="news_boutons"></th>
		<th class="titre_news">Date</th>
		<th class="titre_news">Guilde</th>
		<th class="titre_news">Classement</th>
		<th class="titre_news">Progression</th>
		<th class="titre_news">Points</th>
	</tr>
<?php
	// Ajout d'une fonction de pagination
	include './tools/pagination.php';
	$messages_par_page = 25;
	$messages = pagination('gvg', $messages_par_page);

	$sql = 'SELECT *
			FROM ' . SQL_PREFIX . 'gvg
			ORDER BY timestamp DESC, g_id DESC LIMIT ' . $messages['premier'] . ', ' . $messages_par_page;
	$results = Fsb::$db->query($sql);
		
	while ($donnees = Fsb::$db->row($results))
	{
?>
	<tr>
		<td class="news_boutons"><?php echo '<a href="rediger_gvg.php?modifier_gvg=' . $donnees['g_id'] . '">'; ?><img src="images/edit.gif" alt="Modifier la rencontre" title="Modifier la rencontre" border="0"/></a>
		<?php echo '<a href="gerer_gvg.php?supprimer_gvg=' . $donnees['g_id'] . '">'; ?><img src="images/delete.gif" alt="Supprimer la rencontre" title="Supprimer la rencontre" border="0"/></a></td>
		<td class="titre_news"><?php echo date('d/m/Y', $donnees['timestamp']); ?></td>
		<td class="titre_news"><?php echo stripslashes($donnees['guilde']); ?></td>
		<td class="titre_news"><?php echo $donnees['classement']; ?></td>
		<td class="titre_news"><?php echo $donnees['points_gagnes']; ?></td>
		<td class="titre_news"><?php echo $donnees['points']; ?></td>
	</tr>
	
<?php
	}
	Fsb::$db->free($results);
?>

</table>
<?php
	// Le système de pagination
	echo pages('gerer_gvg');
	}
}
else
{
	echo 'Vous n\'&#xEA;tes pas connect&eacute; ou n\'avez pas l\'autorisation d\'acc&eacute;der &#xE0; cette page.';
}
	include 'footer.php';

/*// Fonction permettant de remettre les points des gvg suivant celui dont on a modifié les points gagnés
function correction_points($timestamp, $points_depart)
{
	$sql = 'SELECT g_id, points_gagnes
			FROM ' . SQL_PREFIX . 'gvg
			WHERE timestamp >= \'' . $timestamp . '\'
			ORDER BY timestamp ASC, g_id ASC';
	$results = FSB::$db->query($sql);
	$points = 0;
	
	while($data = Fsb::$db->row($results))
	{
		switch($data['points_gagnes'])
		{
			  case '+2' :
				  $points = $points_depart + 2;
				  break;
			  case '+3' :
				  $points = $points_depart + 3;
				  break;
			  case '-2' :
				  $points = $points_depart - 2;
				  break;
			  case '-3' :
				  $points = $points_depart - 3;
				  break;
		}
		Fsb::$db->update('gvg', array('points' =>	$points), 'WHERE g_id = ' . $data['g_id']);
		$points_depart = $points;
	}
	Fsb::$db->free($results);
}
*/?>
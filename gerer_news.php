<?php	
include 'header.php';

if ($fsb->is_logged() && ($fsb->userdata('u_auth') >= 3 || $fsb->userdata('u_color') == 'style="color: #CCAA00; font-weight: bold"' || $fsb->userdata('u_color') == 'style="color: #00AA77; font-weight: bold"'))
{	
	if (isset($_POST['supprimer_news']) && isset($_POST['conf_yes'])) // Si on veut vraiment supprimer la news
	{
	    // Alors on supprime la news correspondante
	    // On protège la variable "id_news" pour éviter une faille SQL
	//	$_GET['supprimer_news'] = addslashes($_GET['supprimer_news']);
	    $sql = 'DELETE 
				FROM ' . SQL_PREFIX . 'news 
				WHERE n_id=' . $_POST['supprimer_news'];
		Fsb::$db->query($sql);

		$continue = TRUE;
	}
	elseif(isset($_GET['supprimer_news'])) // On demande une confirmation avant de supprimer la news
	{
		$id_news = addslashes($_GET['supprimer_news']); ?>
		<center>
		<form action="gerer_news.php" method="post"><br />
			<label>Voulez-vous supprimer cette news ?</label>
			<input type="hidden" name="supprimer_news" value="<?php echo $id_news ?>" /><br /><br />
			
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
<a class="submit" href="rediger_news.php">Cr&eacute;er une news</a><br/><br/>
<table align="center">
	<tr>
		<th class="news_boutons"></th>
		<th>Pseudo</th>
		<th class="titre_news">Titre</th>
		<th>Date</th>
	</tr>
<?php
	include './tools/pagination.php';
	$messages_par_page = 25;
	$messages = pagination('news', $messages_par_page);
	
	$sql2 = 'SELECT *
			FROM ' . SQL_PREFIX . 'news
			ORDER BY n_id DESC LIMIT ' . $messages['premier'] . ', ' . $messages_par_page;
	$results = Fsb::$db->query($sql2);

	while ($donnees = Fsb::$db->row($results))
	{
?>

	<tr>
		<td class="news_boutons"><?php echo '<a href="rediger_news.php?modifier_news=' . $donnees['n_id'] . '">'; ?><img src="images/edit.gif" alt="Modifier la news" title="Modifier la news" border="0"/></a>
		<?php echo '<a href="gerer_news.php?supprimer_news=' . $donnees['n_id'] . '">'; ?><img src="images/delete.gif" alt="Supprimer la news" title="Supprimer la news" border="0"/></a></td>
		<td><?php echo $donnees['name']; ?></td>
		<td class="titre_news"><?php echo stripslashes($donnees['title']); ?></td>
		<td><?php echo date('d/m/Y', $donnees['timestamp']); ?></td>
	</tr>
	
<?php
	}
	Fsb::$db->free($results);
?>

</table>
<?php
	echo pages('gerer_news');
	}
}
else
{
	echo 'Vous n\'&#xEA;tes pas connect&eacute; ou n\'avez pas l\'autorisation d\'acc&eacute;der &#xE0; cette page.';
}
	include 'footer.php';
?>
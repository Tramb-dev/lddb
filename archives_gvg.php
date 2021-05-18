<?php 
	include 'header.php';
?>

<h1>Archives des GvG</h1>
<br/>
<a href="index_gvg.php">Voir les résultats actuels</a><br/><br/>

<?php
	include './tools/pagination.php';
	$messages_par_page = 30;
	$messages = pagination('gvg_archives', $messages_par_page);
	
	$sql = 'SELECT *
			FROM ' . SQL_PREFIX . 'gvg_archives
			ORDER BY timestamp DESC, g_id DESC LIMIT ' . $messages['premier'] . ', ' . $messages_par_page;
	$results = Fsb::$db->query($sql);
?>	
<!--<script type="text/javascript" src="js/ajax_gvg.js"></script>
<script type="text/javascript">
window.onload = function(){initAutoComplete(document.getElementById('search'),
document.getElementById('search_gvg'))};
</script>
<div align="center">
    <form action="search_gvg.php" name="search" id="search"><label for="search">Recherche : </label><input type="text" name="search_gvg" id="search_gvg" size="25" autocomplete="off" /></form>
</div>-->
<div id="bsn"></div><br/>

<table align="center" width="80%">
	<tr>
		<th class="titre_news">Date</th>
		<th class="titre_news">Guilde</th>
		<th class="titre_news">Progression</th>
		<th class="titre_news">Classement</th>
		<th class="titre_news">Points</th>
	</tr>

<?php
	while ($donnees = Fsb::$db->row($results))
	{
		if (($donnees['points_gagnes'] == '+2') || ($donnees['points_gagnes'] == '+3'))
		{
			?><tr class="gvg_positif"><?php
		}
		else
		{
			?><tr class="gvg_negatif"><?php
		}
		?>
			<td class="titre_news"><?php echo date('d/m/y', $donnees['timestamp']); ?></td>
			<td class="titre_news"><?php echo stripslashes($donnees['guilde']); ?></td>
			<td class="titre_news"><?php echo stripslashes($donnees['points_gagnes']); ?></td>
			<td class="titre_news"><?php echo stripslashes($donnees['classement']); ?></td>
			<td class="titre_news"><?php echo intval($donnees['points']); ?></td>
		</tr><?php
	}
	Fsb::$db->free($results);
?>
</table><br/>
<?php	
	echo pages('archives_gvg');
	include 'footer.php';
?>
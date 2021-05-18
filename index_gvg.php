<?php 
	include 'header.php';
?>

<h1>Nos r&eacute;sultats en GvG</h1><br/>

<?php
	if(Fsb::$session->is_logged())
	{
		if($fsb->userdata('u_auth') >= 3 || $fsb->userdata('u_color') == 'style="color: #CCAA00; font-weight: bold"' || $fsb->userdata('u_color') == 'style="color: #00AA77; font-weight: bold"')
		{
			echo '<a class="submit" href="rediger_gvg.php">Cr&eacute;er une rencontre GvG</a><a class="submit" href="gerer_gvg.php">Modifier les rencontres GvG</a><br/><br/>';
		}
	}
	include './tools/pagination.php';
	$messages_par_page = 30;
	$messages = pagination('gvg', $messages_par_page);
	
	$sql = 'SELECT *
			FROM ' . SQL_PREFIX . 'gvg
			ORDER BY timestamp DESC, g_id DESC LIMIT ' . $messages['premier'] . ', ' . $messages_par_page;
	$results = Fsb::$db->query($sql);
?>	

<div align="center"><a href="stats_gvg.php"><img src="images/progression_gvg.php" alt="Progression des GvG" title="Progression des GvG" /></a></div><br/>
<script type="text/javascript" src="js/ajax_gvg.js"></script>
<script type="text/javascript">
var divOnMouseDown=function(){
  _inputField.value=getSuggestion(this);
  _documentForm.submit()
};

window.onload = function(){initAutoComplete(document.getElementById('search'),
document.getElementById('search_gvg'))};
</script>
<div align="center">
    <form action="search_gvg.php" name="search" id="search"><label for="search_gvg">Recherche : </label><input type="text" name="search_gvg" id="search_gvg" size="25" autocomplete="off" /></form>
</div>
<br/>

<table align="center" width="90%">
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
		$arr = str_split($donnees['points_gagnes']);
		if (($arr[0] == '+') && ($arr[1] != '0'))
		{
			?><tr class="gvg_positif"><?php
		}
		elseif(($donnees['points_gagnes'] == '0') || ($donnees['points_gagnes'] == '+0') || ($donnees['points_gagnes'] == '-0'))
		{
			?><tr class="gvg_neutre"><?php
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
<a href="stats_gvg.php">Statistiques des GvG</a>
<?php	
	echo pages('index_gvg');
	include 'footer.php';
?>
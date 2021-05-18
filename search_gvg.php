<?php
	include 'header.php';
	
	if (isset($_GET['search_gvg']))
	{
		$search = str_replace('+', ' ', $_GET['search_gvg']);
		$search = addslashes($search);
		echo $search;
		
		$sql = 'SELECT *
				FROM ' . SQL_PREFIX . 'gvg
				WHERE guilde LIKE \'%' . $search . '%\'
				ORDER BY timestamp DESC, g_id DESC';
		$results = Fsb::$db->query($sql);
		
		if(Fsb::$db->count($results) > 0)
		{
			echo '<div align="center">Résultats de la recherche pour <b>' . $search . '</b></div><br/>';
			?>
            <table align="center" width="80%">
                <tr>
                    <th class="titre_news">Date</th>
                    <th class="titre_news">Guilde</th>
                    <th class="titre_news">Progression</th>
                    <th class="titre_news">Classement</th>
                    <th class="titre_news">Points</th>
                </tr>
			<?php
			
			while($donnees = Fsb::$db->row($results))
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
				</tr>
			<?php
			}
		}
		else
		{
			echo '<div align="center">Aucun résulat pour <b>' . $search . '</b></div><br/>';
		}
		Fsb::$db->free($results);
	}
?>
</table>
<br />
<span class="submit"><a href="index_gvg.php">Retour</a></span>

<?php
	include 'footer.php';
?>

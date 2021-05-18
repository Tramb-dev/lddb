<?php 
	include 'header.php';
?>

<h1>Liste des membres</h1>
<span id="liste">
	<span class="underline">Ma&#xEE;tre de guilde :</span><br/>
<?php
	// On récupère la liste des membres automatiquement à partir du forum. Regarder dans le SDK pour avoir plus d'option
	$users = $fsb->get_users('*', 'u_joined');
	foreach($users as $value)
	{
		// Pour les membres, on regarde à partir de la couleur car certains membres ont des avantages par rapport à d'autres, mais gardent la même couleur.
		if($value['u_color'] == 'style="color: #00AA77; font-weight: bold"')
		{
			echo $value['u_nickname'] . '<br/>';
		}
	}
?>
	<br/>
	<span class="underline">Officiers :</span><br/>
<?php
	// On récupère la liste des membres automatiquement à partir du forum. Regarder dans le SDK pour avoir plus d'option
	$users = $fsb->get_users('*', 'u_joined');
	foreach($users as $value)
	{
		// Pour les membres, on regarde à partir de la couleur car certains membres ont des avantages par rapport à d'autres, mais gardent la même couleur.
		if($value['u_color'] == 'style="color: #CCAA00; font-weight: bold"')
		{
			echo $value['u_nickname'] . '<br/>';
		}
	}
?>
	<br/>
	<span class="underline">Membres :</span><br/>
<?php
	// On récupère la liste des membres automatiquement à partir du forum. Regarder dans le SDK pour avoir plus d'option
	$users = $fsb->get_users('*', 'u_joined');
	foreach($users as $value)
	{
		// Pour les membres, on regarde à partir de la couleur car certains membres ont des avantages par rapport à d'autres, mais gardent la même couleur.
		if($value['u_color'] == 'style="font-weight: bold; color: #c000c0;"')
		{
			echo $value['u_nickname'] . '<br/>';
		}
	}
?>
</span>
<br />
<p>Membres actuellement en ligne :
<?php 
// On affiche les membres en ligne avec leur couleur et un lien vers leur profil
$total = $fsb->who_is_online();
for($i = 0; $i < $total['total_user']; $i++)
{
	echo $total['list'][$i]['html'];
	if ($i != ($total['total_user'] - 1))
	echo ', ';
}
?>
</p>
<?php
	include 'footer.php';
?>
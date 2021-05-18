<?php
	define('ROOT', '../forum/');
	include(ROOT . 'sdk.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="images/favicon.ico" />
<div id="stylesheet"><?php $cookie = Http::getcookie('style');
if($cookie == NULL)
{
	echo '<link rel="stylesheet" media="screen" type="text/css" title="Orange" href="../css/style_orange.css" />';
}
else
{
	switch($cookie)
	{
		case 'orange':
			echo '<link rel="stylesheet" media="screen" type="text/css" title="Orange" href="../css/style_orange.css" />';
			break;
			
		case 'blue':
			echo '<link rel="stylesheet" media="screen" type="text/css" title="Bleu" href="../css/style_blue.css" />';
			break;
		
		default:
			echo '<link rel="stylesheet" media="screen" type="text/css" title="Orange" href="../css/style_orange.css" />';
			break;
	}
} ?></div>
<title>Les Disciples de Baal [Baal]</title>

<script language="javascript" src="../js/bbcode.js" type="text/javascript"></script>
<script language="javascript" src="../js/skinswitcher.js" type="text/javascript"></script>
<!--<script language="javascript" src="js/redim.js" type="text/javascript"></script>
-->
</head>

<body>


<div id="bigwrap">
	<div id="site-header">
    	<div id="login-top">
			<?php if ($fsb->is_logged())
            {	
                echo 'Bienvenue ' . $fsb->nickname() . ' ';
			}?>
		</div>
    </div>
    <div id="sidebar">
        <div id="menu">
            <?php include '../menu.php'; ?>
        </div>
        <div id="presentation">
        	<p>L'origine de la guilde remonte au 11 mars 2006, date à laquelle celle-ci fut fondée par deux amis encore présents actuellement : <b>Dark Boudha</b> et <b>Shining Boudha</b>.<br/>
            L'objectif de la guilde est de s'amuser sans prise de tête. Nous faisons aussi bien du PvE que du PvP.</p>
        </div>
    </div>
    <div id="page-container">

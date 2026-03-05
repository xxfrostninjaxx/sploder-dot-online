<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="https://www.w3.org/1999/xhtml"> 
<head> 
	<?php include('../content/head.php'); ?>
	<link rel="stylesheet" type="text/css"  href="/css/sploder_v2p22.min.css" /> 
	<script type="text/javascript" src="/includes/thumb.js"></script> 
	<script type="text/javascript" src="/includes/peekaboo.js"></script> 
	<script type="text/javascript" src="/includes/swfobject_min.js"></script> 
	<script type="text/javascript">var _sf_startpt=(new Date()).getTime();</script>
	<?php include('../content/ruffle.php'); ?>
	<?php include('../content/onlinechecker.php'); ?>
	<script type="text/javascript">window.rpcinfo = "Got lost wandering around";</script>

</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="" >
		<?php include('../content/headernavigation.php'); ?>
		<div id="page">
			<?php include('../content/subnav.php'); ?>
			
			<div id="content" style="margin-left: 180px;">
			    
				<h3>Oops!</h3><p>The page you were looking for was not found. Try some of our most popular games instead!</p>
			

				<div class="gameobject" style="width: 570px; height: 426px;">
					<div id="flashcontent" style="color: #ffec00;">
					<br /><br/><br /><br /><br/><br /><center>Loading some awesome games...</center><br /><br/><br /><br /><br/><br />
					</div>
				</div>
				
				<script type="text/javascript">
					var so = new SWFObject("/swf/popgames.swf", "game", "570", "426", "8", "#333333");
					so.addParam("menu", "false");
					so.addParam("quality", "high");
					
					so.write("flashcontent");
				</script>
				
				<br style="clear: both;" />
				
				<div class="buttons" style="padding: 0;">
					<!-- TODO: <span class="button firstbutton"><a href="games/favorites/">Favorite Games &raquo;</a></span>
					<span class="button"><a href="/members/hall-of-fame">Hall of Fame &raquo;</a></span> -->
					<span class="button firstbutton"><a href="/games/tags.php">Tags &raquo;</a></span>
					<span class="button"><a href="games/members.php">Top Members &raquo;</a></span>
					<!-- <span class="button"><a href="games/groups/">Groups &raquo;</a></span> -->
				</div>
				
				<br style="clear: both;" />
				
				<p>Want to make your own online games for free? <strong>Sploder Revival</strong> makes it super easy for you to make your own free games online and share them with your friends. Make your own <a href="/make/plat.php">platformer games</a>, <a href="/make/shooter.php">spaceship shooters</a>, and more!</p>
				
				
				<br style="clear: both;" />
				



			</div>

			
			
					
<?php include('../content/footernavigation.php') ?>
</body>

</html>
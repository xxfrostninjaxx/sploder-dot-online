<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once(__DIR__ . '/../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
$status = "playing";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" href="/css/sploder_mobile_v03.min.css">
    <script type="text/javascript">
        var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Playing Sploderheads Multiplayer";</script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="creator" class="multiplayer" >

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>

			<div id="content"><h3>SploderHeads Multiplayer Smash-fest</h3>
        <a name="top"></a>
        
        <div class="title_block">
            <ul>
            <li><img src="/images/title_sploderheads.png" alt="SploderHeads Multiplayer Smash-Fest!" width="420" height="60" /></li>
            <li><a href="#help"><img src="/images/help_sploderheads.png" alt="help" width="60" height="60" /></a></li>
            <li class="shown_small"><a href="#game"><img src="/images/scrolltogame_sploderheads.gif" alt="help" width="60" height="60" /></a></li>
            </ul>
        </div>
        
        <script type="text/javascript">
	    
		var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
		var eventer = window[eventMethod];
		var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
		
		eventer(messageEvent, function (e) {
		    
		    //if (e.origin !== "http://multiplayer2.sploder.com:3000" && e.origin !== "http://192.168.2.2:3000") return;
		   
		    switch (e.data) {
			
                case "fullscreen":
                    console.log("fullscreen requested");
                    window.scrollTo(0, 0);
                    $("body").toggleClass("fullscreen_game");
                    /* falls through */
                    
                case "bounce":
                    var iframe = document.getElementById("html5_game");
                    iframe.src = iframe.src;
                    break;

                default:
                    var room = e.data;
                    if (room === "lobby" || room.endsWith("_room")) {
                        if (room) {
                            const link = document.createElement('a');
                            link.href = "/games/multiplayer.php?room=" + room;
                            link.style.display = 'none';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    }
                break;
				
		    }
			
		}, false);
	    </script>
        <?php
        $urlAdd = "";
        if (isset($_GET['room']) && $_GET['room'] != "lobby") {
            $urlAdd = htmlspecialchars($_GET['room']) . "/";
        }
        if (isset($_SESSION['loggedin'])) {
            $isolate = $userRepository->isIsolated($_SESSION['username']) ? "1" : "0";
        } else {
            $isolate = "1";
        }
        ?>
        <a name="game"></a><iframe id="html5_game" scrolling="no" width="880" height="540" src="/sploderheads/games/first/<?= $urlAdd ?>?username=<?= htmlspecialchars($_SESSION['username'] ?? 'guest') ?>&isolated=<?= $isolate ?>"></iframe><div class="info"><a name='help'></a>

<h2>SploderHeads Multiplayer Help</h3>



<img class="right" src="/images/sploderheads_create.gif?v=3" width="286" height="190" alt="powerups" />

<p>Welcome to Sploder's first multiplayer games portal! Through this portal, you'll be able to see a list of all the <em>SploderHeads</em> games currently being designed and played in real-time. 

You can either join an open game, or create your own. When you create your own game, you become the host and moderator of the game, and can control who can play and enter messages in your game room.</p>



<img class="left" src="/images/sploderheads_draw.gif?v=3" width="286" height="190" alt="powerups" />



<h3>Creating Games</h3>



<p>To create a game, press the "Create Game" button at the top right corner of the window. A game room will be created in the portal. Click on your game room to start designing your new game.

You can draw your world and move the four players using the buttons at the top. The world is wider than the screen, so you can drag using your mouse wheel or trackpad if it is supported. You can also click and 

drag your world if no drawing buttons are selected.</p>


<img class="right" src="/images/sploderheads_join.gif?v=3" width="286" height="190" alt="powerups" />

<img class="right" src="/images/sploderheads_players.gif?v=3" width="286" height="190" alt="powerups" />


<h3>Adding Players</h3>

<p>Once you've created your world, you can publish it. At that point, other players may join your game. You can also add bots to your game if you wish. There are three bots who can play in place of human players.

    You can see the current players in the game by clicking the <em>Players</em> button at the bottom 

    right. Online players are shown with white text.</p>



<h3>Joining Games</h3>



<p>To join a game, click on a game in the portal and then click <em>Join Game</em> at the top right corner. Once there are four players, the game will start. Keep in mind that if you create a game, you still 

    must join the game if you want to play.</p>



<!-- <h3>Moderating Games</h3>

<p>If you are the owner of the game room you can <code>/kick</code>, <code>/mute</code> and <code>/ban</code> users who visit your room. Type <code>/help</code> for a full list of room commands. Sploder's 

moderator staff can also see all messages in games as well as <code>/kick</code>, <code>/warn</code> and <code>/ban</code> users who are behaving inappropriately.</p> -->



<h3>Messaging in Games</h3>

<p>Please be sure to follow our <a href='/legal/termsofservice.php'>Terms of Service</a> when messaging in games. If your account is not in good standing, or you have opted out of social interaction, messaging may be 

disabled for you. You can still play games, however.</p>

<br /><br />

<a href="#top"><img align="right" src="/images/top_sploderheads.png" alt="back to top" width="60" height="60" /></a>

<div class="spacer"></div>



<h2>How to Play <em>SploderHeads</em></h2>



<p><em>SploderHeads</em> is a real-time turn-based game. Players play as their avatars and armed with a single cannon. On each turn, you can either fire your cannon at another player, or move your player 

along the landscape. Your player's health is shown with a meter right above the avatar.</p>



<p>There are challenges and hazards in the map as well. Wind will change every so often and change the trajectory of your cannonball. There is also water in the map, which can limit movement.

When cannonballs land on the map, part of the land is destroyed. If any players are in the vicinity, they will lose health, and fall lower on the map. If the map is destroyed below the water-line, water will 

be exposed. If a player falls into water, they instantly lose the game.</p>



<h3>Controls</h3>



<img class="right" src="/images/sploderheads_modes.gif" alt="player modes" width="440" height="230" />



<p>When it is your turn in a game, controls will appear on the screen. There is a top menu which allows you to choose whether to <em>fire</em> your cannon or <em>move</em> your player. By default, <em>fire</em> 

will be selected.</p>



<p>When firing, there are three controls on the bottom of the screen. You can aim your cannon with the circular control on the left. You can choose the power of your shot with the diamond-shaped control on the 

    right. Finally, you can launch your cannonball by pressing the button in the middle.</p>



<p>When moving, you can move left and right with the respective arrow buttons. Once you have chosen your new location, press the button in the middle.<p> 



<h3>Powerups and Hazards</h3>

<p>Powerups will appear randomly throughout the game. There are four different powerups that may appear, each with their own abilities:</p>



<img class="left" src="/images/sploderheads_powerups.gif" width="288" height="114" alt="powerups" />



<ul>

    <li><strong>Wind:</strong> Changes the direction of the wind.</li>

    <li><strong>Earth:</strong> Builds a mountain of land under your player and raises you safely above water.</li>

    <li><strong>Health:</strong> Restores your health.</li>

    <li><strong>Shield:</strong> Creates a defense against cannons for two rounds. You can still be harmed by falling into water.</li>

</ul>

<br />

<p>Powerups appear above all players in the sky above the landscape. To get a powerup, you must fire your cannon and hit it. Keep in mind that this uses a turn.</p>



<h3>Winning the Game</h3>



<a href="#top"><img align="right" src="/images/top_sploderheads.png" alt="back to top" width="60" height="60" /></a>

<p>To win the game, be the last player standing. It's that simple. Enjoy!</p>


</div><div class="spacer">&nbsp;</div></div>
			<div id="sidebar">
				
				
				
				
				
				
				
				
				
				<br /><br /><br />
				<div class="spacer">&nbsp;</div>
			</div>			

    <?php include('../content/footernavigation.php') ?>

</body>
</html>
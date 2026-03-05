<?php
require_once '../content/initialize.php';

include_once('../database/connect.php');
require_once('../repositories/repositorymanager.php');
$time = time();
$pagechange = $time - 900;
$userRepository = RepositoryManager::get()->getUserRepository();
$members = $userRepository->getOnlineMembers();
$total = count($members);
// Set header as javascript
header('Content-Type: text/javascript');
?>
try {

var net_result = ' <div class="users_online"><ul><?php
for ($i = 0; $i < $total; $i++) {
    if ($members[$i]['status'] == "online") {
        if ($members[$i]['lastpagechange'] > $pagechange) {
            $status = "online";
            $title = "Active";
        } else {
            $status = "idle";
            $title = "Idle";
        }
    } elseif ($members[$i]['status'] == "creating") {
        $status = "making";
        $title = "Making a Game";
    } elseif ($members[$i]['status'] == "playing") {
        $status = "playing";
        $title = "Playing a Game";
    }

    echo '<li><a href="../members/index.php?u=' . $members[$i]['username'] . '"><img src="../php/avatarproxy.php?size=24&u=' . $members[$i]['username'] . '" alt="' . $members[$i]['username'] . '" border="0" style="width:24px;height:24px;margin:-6px 8px" />' . $members[$i]['username'] . '</a><img style="margin-left:30px" class="status" src="../images/status_' . $status . '.gif" width="11" height="11" title="' . $title . '"/></li>';
}



?></ul><div class="spacer">&nbsp;</div></div>';

if (document && document.getElementById) {

var c = document.getElementById("whos_online_container");

if (c) {

c.innerHTML = net_result;

}

}

} catch (err) {


}
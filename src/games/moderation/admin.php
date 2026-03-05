<?php
require_once '../../content/initialize.php';
 require(__DIR__.'/../../content/disablemobile.php'); ?>
<?php include('php/verify.php'); ?>
<?php
include('php/admincheck.php');
if(!isAdmin($_SESSION['username'])) {
    die("Haxxor detected");
}
?>
<?php
require_once('../../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../../content/head.php'); ?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />

    <script type="text/javascript">window.rpcinfo = "Idling";</script>
    <?php include('../../content/onlinechecker.php'); ?>

</head>
<?php include('../../content/addressbar.php'); ?>

<body id="everyones" class="seventh">
    <?php include('../../content/headernavigation.php'); ?>

    <div id="page">
        <?php include('content/subnav.php'); ?>

        <div id="content">
            <?php if (isset($_GET['err'])) : ?>
                <p class="alert"><?= htmlspecialchars($_GET['err']) ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['msg'])) : ?>
                <p class="prompt"><?= htmlspecialchars($_GET['msg']) ?></p>
            <?php endif; ?>
            <h2>Permissions</h2>

            <form method="post" action="">
                <label for="username">Enter Username:</label>
                <input type="text" id="username" name="username" value=<?= json_encode($_POST['username'] ?? '') ?> required autocomplete="off" />
                <input type="submit" value="Submit" />
            </form>

            <?php
            $usernameValid = false;
            if (isset($_POST['username'])) {
                $userInfo = $userRepository->getUserIdFromUsername($_POST['username']);
                if ($userInfo === -1) {
                    echo '<br><br><p class="alert">User not found.</p>';
                } else {
                    $usernameValid = true;
                }
            }
            if ($usernameValid) {
                if ($_SESSION['username'] === $_POST['username']) {
                    echo '<br><br><p class="alert">You cannot edit your own permissions.</p>';
                    $usernameValid = false;
                }
            }
            if ($usernameValid) {
                $perms = $userRepository->getUserPerms($_POST['username']);
                $perms = $perms ?? '';
                // Permission letters: M = Moderator, R = Reviewer, E = Editor, A = Admin
                $permLabels = [
                    'M' => 'Moderator',
                    'R' => 'Reviewer',
                    'E' => 'Editor',
                    'A' => 'Admin'
                ];
            if (isset($_POST['save_perms'])) {
                // Save permissions
                $newPerms = '';
                foreach ($permLabels as $key => $label) {
                    if (isset($_POST["perm_$key"])) {
                        $newPerms .= $key;
                    }
                }
                $userRepository->setUserPerms($_POST['username'], $newPerms);
                echo '<br><br><p class="prompt">Permissions updated!</p>';
                // Reload updated perms
                $perms = $newPerms;
            }
            if (!isset($_POST['save_perms']) || !$usernameValid) {
                echo '<br><br>';
            }
            ?>
            <form method="post" action="">
                <input type="hidden" name="username" value="<?= htmlspecialchars($_POST['username']) ?>" />
                <table>
                    <tr>
                        <?php foreach ($permLabels as $key => $label): ?>
                            <td>
                                <label>
                                    <input type="checkbox" name="perm_<?= $key ?>" value="1" <?= strpos($perms, $key) !== false ? 'checked' : '' ?> />
                                    <?= htmlspecialchars($label) ?>
                                </label>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </table><br>
                <input type="submit" name="save_perms" value="Save Permissions" />
            </form>
<?php
    }
?>

            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">


            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../../content/footernavigation.php'); ?>
</body>

</html>
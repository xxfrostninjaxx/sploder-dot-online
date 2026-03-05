<?php

function display_user_info($username)
{
    require_once('../repositories/repositorymanager.php');
    require_once('../services/AwardsListRenderService.php');
    $userRepository = RepositoryManager::get()->getUserRepository();
    $awardsRepository = RepositoryManager::get()->getAwardsRepository();
    $awardsListRenderService = new AwardsListRenderService($awardsRepository);

    require_once('../services/GraphicListRenderService.php');
    $graphicsRepository = RepositoryManager::get()->getGraphicsRepository();
    $graphicListRenderService = new GraphicListRenderService($graphicsRepository);
    include_once('../database/connect.php');
    $db = getDatabase();

    // TODO:: just inline this when migrating to the repository
    $publicgames = " AND isdeleted=0 AND ispublished=1 AND isprivate=0";

    $row = $userRepository->getUserInfo($username);
    //check if all columns are empty or if row does not exist
    if (
        empty($row['description'])
        && empty($row['hobbies'])
        && empty($row['sports'])
        && empty($row['games'])
        && empty($row['movies'])
        && empty($row['bands'])
        && empty($row['respect'])
    ) {
        $showAbout = false;
    } else {
        $showAbout = true;
    }
    ?>
<script type="text/javascript">
function setClass(id, c) {
    var e = document.getElementById(id);
    if (e) e.className = c;
}
</script>
    <?php if ($showAbout) { ?>
<div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_about', 'shown'); return false;">About <?= $username ?></a></h4>
    <div class="mprofcontent hidden" id="mprof_about">
        <?php
        if ($row['description'] != '') {
            echo '<p class="intro">
    <img class="p_avatar" src="/php/avatarproxy.php?u=' . $username . '" width="48" height="48" alt="member speaking"/>
    ' . nl2br(htmlspecialchars($row['description'])) . '</p>';
        }
                $fields = [
                    'hobbies' => 'Hobbies',
                    'sports' => 'Favorite Sports',
                    'games' => 'Favorite Games',
                    'movies' => 'Favorite Movies',
                    'bands' => 'Favorite Bands',
                    'respect' => 'Whom I Respect'
                ];

                foreach ($fields as $column => $label) {
                    if ($row[$column] != '') {
                        echo '<div class="subsection">';
                        echo "<h5>$label</h5>";
                        echo "<p>" . htmlspecialchars($row[$column]) . "</p>";
                        echo '</div>';
                    }
                }
                ?>
        <div class="spacer">&nbsp;</div>
    </div>
</div>

    <?php } ?>
<?php
    // Get required data for awards
    $totalAwards = $awardsRepository->getAwardCount($username);
    if ($totalAwards > 0) {
        
?>
<div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_awards', 'shown'); return false;">Awards (<?= $totalAwards ?>)</a></h4>
    <div class="mprofcontent hidden" id="mprof_awards">
        <div id="profile_awards">
            <?php
            $awards = $awardsRepository->getAwardsPage($username, 0, 25);
            $material_list = $awardsListRenderService->getMaterialList();
            $awardsListRenderService->renderAwardsList($awards, 64, 'img');
            ?>
        </div>
        <div class="spacer">&nbsp;</div>
    </div>
</div>
<?php
    }
$totalGraphics = $graphicsRepository->getTotalPublicGraphicsByUsername($username);
if ($totalGraphics > 0){
?>
<div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_graphics', 'shown'); return false;">Graphics (<?= $totalGraphics ?>)</a><a name="top"></a></h4>
    <div class="mprofcontent hidden" id="mprof_graphics"><br>
    <?php        
        $graphicListRenderService->renderPartialViewForMemberPublicGraphics($username);
    ?>
</div></div>
<?php
}
?>
    <?php

    // Get required data for votes, comments, vote average, tributes, group memberships, and group ownerships
    // TODO: Group Memberships, Group Ownerships, Comment view page
    $votes_cast = $db->queryFirstColumn("SELECT COUNT(*) as votes
        FROM votes
        WHERE username = :username", 0, [
        ':username' => $username
    ]);

    $comments_made = $db->queryFirstColumn("SELECT COUNT(*) as comments
        FROM comments
        WHERE creator_name = :username", 0, [
        ':username' => $username
    ]);

    // Round the average vote to the nearest integer and make it a percentage out of 100
    // Scores are store in the database as integers from 1 to 5
    $vote_avg = $db->queryFirstColumn("SELECT AVG(score) as vote_avg 
        FROM votes
        WHERE username = :username", 0, [
        ':username' => $username
        ]) ?? 0;

    // Convert the average vote to a percentage out of 96 (the width of the bar [WHY GEOFF, WHY!!!])
    $max_score = 5;
    $vote_avg_percentage = ($vote_avg / $max_score) * 96;

    // Fetch all games made by the user that start with "Tribute to someoneese"
    // and have a valid username except for the user themselves in one query
    // All hail GitHub Copilot!!
    // TODO: in switching to a repository, consider caching, maybe improving query
    $validTributesCount = $db->queryFirstColumn("SELECT COUNT(*)
    FROM games g
    JOIN members m
    ON lower(m.username) = lower(substring(g.title from 12)) -- or use split_part if format is strict
    WHERE g.title ILIKE 'Tribute to %'
    AND g.author = :username
    $publicgames
    AND lower(m.username) <> lower(:username)
    ", 0, [
        ':username' => $username
    ]);
    ?>
<div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_activity', 'shown'); return false;"
            title="Things this member has accomplished">Actions</a></h4>
    <div class="mprofcontent hidden" id="mprof_activity">
        <dl class="mprofdata">
            <dt>Votes cast:</dt>
            <dd><?= $votes_cast ?></dd>
            <dt>Comments made:</dt>
            <dd><?= $comments_made ?><a href="/messages/?creator=<?= $username ?>"> view &raquo;</a></dd>
            <dt>Vote average:</dt>
            <dd style="position: relative; width: 96px; height: 24px; background-color: #666;"
                title="Average vote this member has cast on others' games">
                <div style="background: #ffec00; width: <?= $vote_avg_percentage ?>px; height: 24px;">&nbsp;</div>
                <div
                    style="width: 96px; height: 24px; position: absolute; top: 0; left: 0; z-index: 2; background-image: url('/chrome/starmask.png');">
                    &nbsp;
                </div>
            </dd>
            <dt>Tributes made:</dt>
            <dd><?= $validTributesCount ?></dd>
            <!-- <dt>Group Memberships:</dt>
            <dd>??</dd>
            <dt>Group Ownerships:</dt>
            <dd>??</dd> -->
        </dl>
        <div class="spacer">&nbsp;</div>
    </div>
</div>

    <?php
    // Get required data for reactions
    $five_star_faves = $db->queryFirstColumn("SELECT COUNT(*) as five_star_faves
        FROM votes v 
        JOIN games g
        ON v.g_id = g.g_id
        AND g.author = :username
        WHERE v.score = 5
        AND v.username != :username", 0, [
        ':username' => $username,
    ]);

    // Get required data for comments received
    // This is  calculated by counting the number of comments on the user's items.
    // The venue is in the format of venuetype-number-username
    // User's items are those whose venue contains the user's username. However, care must be taken that
    // someone with the username "user" does not get comments on items with the venue "user2" or "user3"
    // Comments received must not include the user's own comments on their own items

    $comments_received = $db->queryFirstColumn("SELECT COUNT(*) as comments_received
        FROM comments
        WHERE venue
        LIKE '%-:username'
        AND creator_name != :username", 0, [
        ':username' => $username
    ]);

    // TODO: Favourites

    // Get required data for tributes received
    // This is calculated by counting the number of games that have the title 'Tribute to username'
    // Care must be taken that the tribute is not to the user themselves and user1's tribute is not counted as user11's tribute
    // Tribute to detection is not case sensitive
    $tributes_received = $db->queryFirstColumn("SELECT COUNT(*) as tributes_received
        FROM games
        WHERE title ILIKE 'Tribute to ' || :username AND author != :username $publicgames", 0, [
        ':username' => $username
    ]);

    // Get required data for comment rating
    // This is calculated by averaging the ratings of other users on the user's comments
    // Round off till 3 decimal places
    // Prevent NULL depreciation warning
    $comment_rating = round($db->queryFirstColumn("SELECT AVG(score) as comment_rating
        FROM comments
        WHERE creator_name = :username", 0, [
        ':username' => $username
        ]) ?? 0, 3);

    // Get required data for contests won
    // This is calculated by counting the number of contests the user has won in the table 'contest_winner'
    // This table has 2 columns, g_id and contest_id
    // The user has won a contest if their g_id is present in the table
    $contests_won = $db->queryFirstColumn("SELECT COUNT(*) as contests_won
        FROM contest_winner cw
        JOIN games g
        ON cw.g_id = g.g_id
        WHERE g.author = :username", 0, [
        ':username' => $username
    ]);

    ?>

<div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_reactions', 'shown'); return false;"
            title="How other members react to this member.">Reactions</a></h4>
    <div class="mprofcontent hidden" id="mprof_reactions">
        <dl class="mprofdata">
            <dt>5-star faves:</dt>
            <dd><?= $five_star_faves ?></dd>
            <dt>Comments received:</dt>
            <dd><?= $comments_received ?></dd>
            <!-- <dt>Favorites <span style="color: #ff6666;">&hearts;</span>:</dt>
            <dd>??</dd> -->
            <dt>Tributes received:</dt>
            <dd><?= $tributes_received ?></dd>
            <dt>Comment rating:</dt>
            <dd><?= $comment_rating ?></dd>
            <dt>Contests won:</dt>
            <dd><?= $contests_won ?></dd>
        </dl>
        <div class="spacer">&nbsp;</div>
    </div>
</div>

<?php
// Get required data for reviews written
$gameRepository = RepositoryManager::get()->getGameRepository();
$reviews = $gameRepository->getReviewsByUsername($username);
if (count($reviews) > 0) {
?>
<div class="mprofgroup mprofsection">
    <h4><a href="#" onclick="setClass('mprof_reviews', 'shown'); return false;">Reviews by <?= htmlspecialchars($username) ?></a></h4>
    <div class="mprofcontent hidden" id="mprof_reviews">
        <ul style="color:#ccc;">
            <?php foreach ($reviews as $review): ?>
                <li>
                    <a href="../games/view-review.php?s=<?= $review['game_author_id'] ?>_<?= $review['g_id'] ?>&userid=<?= $review['userid'] ?>">
                        <?= htmlspecialchars($review['title']) ?>
                    </a> a review of "<?= htmlspecialchars($review['game_title']) ?>" by <?= htmlspecialchars($review['author']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="spacer">&nbsp;</div>
    </div>
</div>
<?php
}
} // End of function
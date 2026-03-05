<?php
function generateSearchResults(array $result)
{
    require_once(__DIR__ . "/../../services/FriendsListRenderService.php");
    $friendsRepository = RepositoryManager::get()->getFriendsRepository();
    $friendsListRenderService = new FriendsListRenderService($friendsRepository);

    echo $friendsListRenderService->renderPartialViewForMemberList($result);
}
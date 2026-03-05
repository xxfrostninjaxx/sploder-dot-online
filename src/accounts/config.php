<?php

require_once("../config/env.php");

$client_id = getenv("DISCORD_CLIENT_ID");

$secret_id = getenv("DISCORD_SECRET_ID");

$scopes = "identify";

$redirect_url = getenv("DOMAIN_NAME") . "/accounts/checkmigrationownership.php";

# IMPORTANT READ THIS:
# - Set the `$bot_token` to your bot token if you want to use guilds.join scope to add a member to your server
# - Check login.php for more detailed info on this.
# - Leave it as it is if you do not want to use 'guilds.join' scope.
$bot_token = getenv("DISCORD_BOT_TOKEN");

<?php
require_once '../content/initialize.php';

session_start();
unset($_SESSION);
session_regenerate_id();
session_destroy();
header('Location: ../index.php?msg=out');
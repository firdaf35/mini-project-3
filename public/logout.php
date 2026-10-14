<?php
// public/logout.php
session_start();
session_unset();
session_destroy();

header('Location: /inventory-app/public/login.php');
exit;
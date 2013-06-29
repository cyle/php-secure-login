<?php

$login_required = true;
require_once('login_check.php');

?>
<!-- page protected by a login -->
<html>
<head>
<title>protected page test</title>
</head>
<body>
<p>This page is protected by a login!</p>
<p><a href="logout.php">Log out</a></p>
</body>
</html>
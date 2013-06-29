<?php

require_once('login_check.php');

?>
<!-- page not protected by a login -->
<html>
<head>
<title>login test</title>
</head>
<body>
<p>This page is not protected by a login.</p>
<?php if ($current_user['loggedin'] == false) { ?>
<p><a href="login.php">Log in</a></p>
<p><a href="register.php">Register</a></p>
<?php } else { ?>
<p><a href="protected.php">Go to protected page</a></p>
<p><a href="logout.php">Log out</a></p>
<?php } ?>
</body>
</html>
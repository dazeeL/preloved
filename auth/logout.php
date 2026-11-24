<?php
session_start();
session_unset();
session_destroy();
header("Location: /preloved/auth/login.php");
exit();

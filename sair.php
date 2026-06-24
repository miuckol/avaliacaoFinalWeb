<?php
sess_start();
session_destroy();
header("Location: index.php");
exit;
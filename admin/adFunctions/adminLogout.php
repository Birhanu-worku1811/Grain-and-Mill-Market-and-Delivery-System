<?php
session_start();
session_destroy();

header("Location: ../adminPages/adminlogin.php");

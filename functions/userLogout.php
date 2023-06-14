<?php
if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }
unset($_SESSION['user_details']);
unset($_SESSION['logged_in']);
unset($_SESSION['from']); // Just Nothing but important. you can Ignore if confused

header("Location: ../index.php");

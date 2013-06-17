<?php
session_start();
session_destroy();
header("location: index.php?code=3");
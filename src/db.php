<?php
$host = "sql213.infinityfree.com";   // Your InfinityFree DB Host
$user = "if0_40481043";      // Your DB Username
$pass = "Tv5M6CcaHi0ZcQ0";   // Your DB Password
$db   = "if0_40481043_smartstep";  // Your DB Name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

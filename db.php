<?php

$conn = mysqli_connect('localhost', 'root', '', 'project');
if (!$conn) {
    die("Server is down: " . mysqli_connect_error());
}

?>
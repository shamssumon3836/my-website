<!DOCTYPE html>
<html>
<head>
<? include 'head.php'; ?>
<title>David's Website</title>
<link rel="stylesheet" type="text/css" href="styles/main.css">
</head>
<body>
<?
include './config.php';
if ($maintenance == false)
{
?>
<?php include './nav_bar.php'?>
<div id="content">
<h2>Welcome to my new website!</h2>
<p>Feel free to look around...</p>
</div>
<?
}
else{
?>
<p>Under maintenance...</p>
<?
}
?>
</body>
</html>

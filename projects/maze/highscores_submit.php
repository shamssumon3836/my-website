<?php
if (!isset($_POST["hash"])){
   die("Hash was not chosen!");
}
else{
   $hash = $_POST["hash"];
}

if (!isset($_POST["name"])){
   die("Name was not chosen!");
}
else{
   $name = $_POST["name"];
}
if (!preg_match("/^[a-zA-Z0-9]+$/",$name))
{
   echo "Name contains non alphanumeric characters, removing them...<br/>";
   $name = preg_replace("/[^a-zA-Z0-9]+/", "", $name);
}
if (!isset($_POST["score"])){
   die("Score was not chosen!");
   $score = 0;
}
else{
   $score = $_POST["score"];
}
if (!preg_match("/^-?\d+\.?\d*$/", $score))
{
   die("Score is not a valid number!");
   $score = 0;
}
$computed_hash = md5("$name $score #ecc>`r:fP");
if ($computed_hash != $hash)
{
   die("You trickster");
}
require_once 'highscores.php';
add_score($conn, $name, $score);
$conn->close();

?>

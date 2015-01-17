<?php
//$servername = "mysql.hostinger.co.il";
$servername = "localhost";
$username = "u364365495_admin";
$password = "WtTk9uNX";
$dbname = "u364365495_mzhs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function execute_sql($conn, $sql)
{
if ($conn->query($sql) === FALSE) {
    die("Error: " . $conn->error);
}
}

function create_highscores_table($conn)
{
$sql = "CREATE TABLE IF NOT EXISTS highscores (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
score INT(10) UNSIGNED NOT NULL
)";

execute_sql($conn, $sql);
}

function drop_highscores_table($conn)
{
$sql = "DROP TABLE IF EXISTS highscores";
execute_sql($conn, $sql);
}


function add_score($conn, $name, $score)
{
$sql = "INSERT INTO highscores (name, score) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $name, $score);
$stmt->execute();
$stmt->close();
delete_non_top10($conn);
}

function delete_score($conn, $id)
{
$sql = "DELETE FROM highscores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
}

function delete_non_top10($conn)
{
     $res = get_scores_array($conn);

     if (count($res) > 10)
     {
          uasort($res,  function ($a, $b){return $a['score'] - $b['score'];});
          $res = array_slice($res, 0, count($res)-10);
          foreach ($res as $row){
                delete_score($conn, $row['id']);
          }
     }

}
function get_scores_array($conn)
{
$sql = "SELECT id, name, score FROM highscores ORDER BY score DESC";
$result = $conn->query($sql);
$res = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
           array_push($res, $row);
    }
}
return $res;
}
function select_scores($conn)
{
     $scores_array = get_scores_array($conn);
     if (count($scores_array) == 0){echo ",;";}
     foreach ($scores_array as $row){
         echo htmlspecialchars($row["name"]) . "," . htmlspecialchars($row["score"]) . ";";
     }
}
create_highscores_table($conn);
delete_non_top10($conn);
?>

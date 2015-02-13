<? php
require_once 'highscores.php';
header("Content-type: text/plain");
select_scores($conn);
$conn - > close(); ?>
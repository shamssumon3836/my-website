<!DOCTYPE html>
<html>
<head>
<? include 'head.php'; ?>
<title>Projects list</title>
<link rel="stylesheet" type="text/css" href="styles/styles.css">
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

google.load("feeds", "1");

function initialize() {
  var feed = new google.feeds.Feed("http://github.com/d1618033.atom");
  feed.load(function(result) {
    if (!result.error) {
      var container = document.getElementById("feed");
      for (var i = 0; i < result.feed.entries.length; i++) {
        var entry = result.feed.entries[i];
        var div = document.createElement("div");
        div.innerHTML = entry.content;
        container.appendChild(div);
      }
    }
  });
}
google.setOnLoadCallback(initialize);

</script>
</head>
<body>
<?include './nav_bar.php'?>
<div id="content">
<p>Here's a few projects I've worked on lately...</p>
<ul id="projects_list">
<li><a href="/projects/recur.php">Recurrence Relation Solver</a></li>
<li><a href="/projects/random_gauss.php">Random Gaussian Generator</a></li>
<li><a href="/projects/maze/index.php">Maze Game</a></li>
</ul>
</div>
<div id="feed">
<h3>GitHub feed</h3>
</div>
</body>
</html>

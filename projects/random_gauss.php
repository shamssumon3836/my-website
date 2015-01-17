<!DOCTYPE html>
<html>
<head>
<title>Random Gaussian Generator</title>
<link rel="stylesheet" type="text/css" href="../styles.css">
<style type="text/css">



#gauss_form{
    position: absolute;
    margin-top: 0px;
}
#data_and_hist{
    position: absolute;
    margin-top: 50px;
}
#data{
    position: absolute;
    margin-left: 0px;
    margin-top: 60px;
}
#hist{
    position: absolute;
    margin-left: 200px;
    margin-top: 0px;
    padding-top: 35px;
    width: 900px;
    height: 450px;
}
</style>
<script>
function RandomGauss()
{
   this.stored = null;
   this.next = function(){
       if (this.stored == null){
            var u1 = Math.random();
            var u2 = Math.random();
            var z1 = Math.sqrt(-2 * Math.log(u1)) * Math.cos(2* Math.PI * u2);
            var z2 = Math.sqrt(-2 * Math.log(u1)) * Math.sin(2* Math.PI * u2);
            this.stored = z2;
            return z1;
       }
       else{
           var z2 = this.stored;
           this.stored = null;
           return z2;

       }
   }
}
function randn(n)
{
    var ret = [];
    var rg = new RandomGauss();
    for (var i=0; i<n; i++)
        ret.push(rg.next());
    return ret;
}
function onClickGenerate()
{
    var n = document.getElementsByName("num")[0].value;
    if (n > 0)
    {
    n = Math.floor(n/1);
    var rvs = randn(n);
    generateTable(rvs, "data_table");
    drawChart(rvs);
    }
}
function downloadFile(filename, text) {
  var a = document.createElement('a');
  a.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  a.setAttribute('download', filename);
  return a;
}
function generateTable(array, id)
{
    var table = document.getElementById(id);
    table.innerHTML = "";
    var row = table.insertRow(-1);
    var a = downloadFile("random_gauss.csv", array.join('\n'))
    a.innerHTML ="Download as csv";
    row.appendChild(a);
    for (var i in array)
    {
         var row = table.insertRow(-1);
         row.className = (i % 2 == 0 ? "even" : "odd");
         var cell = row.insertCell(-1);
         cell.innerHTML = array[i];

    }
}
</script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      function drawChart(array) {
        var arrayOfArrays = [['value']];
        for (var i=0; i<array.length; i++)
              arrayOfArrays.push([array[i]]);
        var data = google.visualization.arrayToDataTable(arrayOfArrays);

        var options = {
          title: 'Histogram of data',
          legend: { position: 'none' },
        };

        var chart = new google.visualization.Histogram(document.getElementById('hist'));
        chart.draw(data, options);
      }
    </script>
</head>
<body>
<?include '../nav_bar.php'?>
<div id="content">
<div id="gauss_form">
<p>
Number of random gaussians to generate: <input name="num" type="number" min=1 max=1000 value=100>
</p>
<input type="submit" value="Generate" onclick="onClickGenerate();">
</div>
<div id="data_and_hist">
<div id="data">
<table border=1 id="data_table">
</table>
</div>
<div id="hist"></div>
</div>
</div>
</body>
</html>

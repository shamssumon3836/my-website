function getscore(callback)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    var text=xmlhttp.responseText;
    var first = text.split(";")[0];
    var nameAndScore = first.split(",");
    var name = nameAndScore[0];
    var score= nameAndScore[1];
    if (name == "" || name == undefined || score == undefined || score == "") {name = "Guest"; score=0;}
    else{name=name.trim();}

    callback(name, score);
    }
}
xmlhttp.open("GET","highscores_display.php",true);
xmlhttp.send();
}

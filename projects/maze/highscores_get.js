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
        var data = JSON.parse(text);
        if (data.length == 0)
        {
            callback("", 0, []);
        }
        else
        {
            callback(data[0].name, data[0].score, data);
        }
    }
}
xmlhttp.open("GET","highscores_display.php",true);
xmlhttp.send();
}

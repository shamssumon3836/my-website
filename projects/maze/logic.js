var c,ctx;
var maxheight=300,maxwidth=1350;
var tile_h, tile_w, height, width;
var incR,inC;
var nrows=height/tile_h,ncols=width/tile_w;
var set;
var start_loc,end_loc;
var time,game_timer;
var score,bonus;
var board;
var player;
var game_active;

function ViewableWidget(id){
    this.id = id;
    this._open = false;
    this.changed = false;
}
ViewableWidget.prototype.open = function(){
    document.getElementById(this.id).style.visibility="visible";
    document.getElementById(this.id).style.zIndex=2;
    this._open = true;
}
ViewableWidget.prototype.close = function(){
    document.getElementById(this.id).style.visibility="hidden";
    document.getElementById(this.id).style.zIndex=1;
    this._open = false;
}
ViewableWidget.prototype.isOpen = function(){
    return this._open;
}

function Notebook(objs){
    this.objs = objs;
    this.open = function (name){
        for (var key in this.objs)
        {
            var obj = this.objs[key];
            if (key != name)
            {
                obj.close();
            }
            else
            {
                obj.open();
            }
        }
    };
}


var custom_form;
var settings_form;
var highscores_block;
var notebook;
var customized,custom_box_changed;
var settings_box_changed;
var vim_controls = {
    "Up": 75,
    "Down": 74,
    "Left": 72,
    "Right": 76,
};
var regular_controls = {
    "Up": 38,
    "Down": 40,
    "Left": 37,
    "Right": 39,

};
var controls = regular_controls;
var hs,user_name;

function tile(row,col,bottom,right,set,marked)
{
    this.row=row;
    this.col=col;
    this.bottom=bottom;
    this.right=right;
    this.set=set;
    this.marked=marked;

    this.draw=draw;
    function draw()
    {
        ctx.beginPath();
        if (this.bottom)
        {
            ctx.moveTo(this.col*tile_w,(this.row+1)*tile_h);
            ctx.lineTo((this.col+1)*tile_w,(this.row+1)*tile_h);
        }
        if (this.right)
        {
            ctx.moveTo((this.col+1)*tile_w,this.row*tile_h);
            ctx.lineTo((this.col+1)*tile_w,(this.row+1)*tile_h);
        }
        ctx.lineWidth=1;
        ctx.strokeStyle="black";

        ctx.stroke();
        ctx.fill();
    }
}





function playerObj(row,col)
{
    this.row=row;
    this.col=col;
    this.draw=draw;
    function draw()
    {
        ctx.beginPath();
        ctx.arc(this.col*tile_h+tile_h/2,this.row*tile_w+tile_w/2,tile_h/4,0,2*Math.PI)
        ctx.fillStyle="blue";
        ctx.lineWidth=1;
        ctx.strokeStyle="black";
        ctx.fill();
        ctx.stroke();
    }
}



function keyDown(event)
{
    var key=event.which;
    if (key == 83)
    {
        beginGame();
    }
    if (game_active)
    {
        var previous=new playerObj(player.row,player.col);

        switch (key)
        {
            case controls.Left:
            if (player.col>0)
            {
                if (!board[player.row][player.col-1].right)
                {
                    player.col--;
                    update_marked(previous);
                }
            }
            break;
            case controls.Up:
            if (player.row>0)
            {
                if (!board[player.row-1][player.col].bottom)
                {
                    player.row--;
                    update_marked(previous);
                }
            }
            break;
            case controls.Right:
            if (player.col<ncols-1)
            {
                if (!board[player.row][player.col].right)
                {
                    player.col++;
                    update_marked(previous);
                }
            }
            else if (player.col==ncols-1 && player.row==end_loc)
            {

                if (height+incR*tile_h>maxheight && width+incC*tile_w<=maxwidth)
                {
                    width=width+incC*tile_w;
                    height=maxheight;
                }
                else if (width+incC*tile_w>maxwidth && height+incR*tile_h<=maxheight)
                {
                    height=height+incR*tile_h;
                    width=maxwidth;
                }
                else if (width+incC*tile_w>maxwidth && height+incR*tile_h>maxheight)
                {
                    width=maxwidth;
                    height=maxheight;

                }
                else
                {
                    width=width+incC*tile_w;
                    height=height+incR*tile_h;
                }
                score=score+bonus;
                document.getElementById("score").innerHTML=score;
                maze();
            }

            break;
            case controls.Down:
            if (player.row<nrows-1)
            {
                if (!board[player.row][player.col].bottom)
                {
                    player.row++;
                    update_marked(previous);
                }
            }
            break;
        }

        drawGame();
    }
}
function update_marked(previous)
{
    if (board[player.row][player.col].marked)
    {
        board[previous.row][previous.col].marked=false;
        bonus--;
    }
    else
    {
        board[player.row][player.col].marked=true;
        bonus++;
    }
}
function onLoad()
{
    c=document.getElementById("game");
    ctx=c.getContext("2d");
    settings_form = new ViewableWidget("settingsBox");
    custom_form = new ViewableWidget("customBox");
    var gameWidget = new ViewableWidget("game");
    gameWidget.open = function(){
        ViewableWidget.prototype.open.call(this);
        clearScreen();
    };
    highscores_block = new ViewableWidget("highscores-table");
    highscores_block.open = function(){
        ViewableWidget.prototype.open.call(this);
        showTop10Highscores();
    };
    notebook = new Notebook({
        settings: settings_form,
        custom: custom_form,
        game: gameWidget,
        highscores: highscores_block,
    });
    notebook.open("game");
    checkForHS();
    player=new playerObj(0,0);
    customOff();
    game_active=false;
    custom_box_changed=false;
    settings_box_changed=false;
    maxheight=Math.floor(screen.availHeight/50)*50-300;
    maxwidth=Math.floor(screen.availWidth/50)*50-300;
    c.width=maxwidth;
    c.height=maxheight;


}
function checkForParemErrors()
{
    getParems();
    if (height>maxheight)
    {
        height=maxheight;
        document.getElementById("custNRows").value=maxheight/tile_h;
    }
    if (height<5*tile_h)
    {
        height=5*tile_h;
        document.getElementById("custNRows").value=5;
    }
    if (width>maxwidth)
    {
        width=maxwidth;
        document.getElementById("custNCols").value=maxwidth/tile_w;
    }
    if (width<5*tile_w)
    {
        width=5*tile_w;
        document.getElementById("custNCols").value=5;
    }
    if (time<=1)
    {
        time=1;
        document.getElementById("custTime").value=1;
    }
    if (incR>10)
    {
        incR=10;
        document.getElementById("incR").value=10;
    }
    if (incR<0)
    {
        incR=0;
        document.getElementById("incR").value=0;
    }
    if (incC>10)
    {
        incC=10;
        document.getElementById("incC").value=10;
    }
    if (incC<0)
    {
        incC=0;
        document.getElementById("incC").value=0;
    }

}
function setGameVisible()
{
    notebook.open("game");
}
function customOn()
{
    customized=true;
    document.getElementById("custom_mode").innerHTML="On";
    document.getElementById("hs_text").style.textDecoration="line-through";
}
function customOff()
{
    customized=false;
    document.getElementById("custom_mode").innerHTML="Off";
    document.getElementById("hs_text").style.textDecoration="none";
}

function custom()
{

    if (!custom_form.isOpen())
    {
        stopGame();
        notebook.open("custom");
    }
    else
    {
        notebook.open("game");
    }
}
function stopGame()
{
    game_timer=false;
    if (game_active)
    {
        game_active=false;
        document.getElementById("score").innerHTML=0;
    }
    document.getElementById("time").innerHTML=changeTimeFormat(document.getElementById("custTime").value);
}
function settings()
{

    if (!settings_form.isOpen())
    {
        stopGame();
        notebook.open("settings");
    }
    else
    {
        notebook.open("game");
    }
}
function highscores()
{

    if (!highscores_block.isOpen())
    {
        stopGame();
        notebook.open("highscores");
    }
    else
    {
        notebook.open("game");
    }
}
function resetToDefault()
{
    document.getElementById("custTime").value=120;
    document.getElementById("custNRows").value=5;
    document.getElementById("custNCols").value=5;
    document.getElementById("incR").value=1;
    document.getElementById("incC").value=1;
    document.getElementById("small").checked=true;
    customOff();
    custom_box_changed=false;
}
function resetToDefaultSettings()
{
    document.getElementById("regular").checked=true;
    settings_box_changed=false;
}
function onChangeCustomBox()
{
    checkForParemErrors();
    custom_box_changed=true;
}
function onChangeSettingsBox()
{
    settings_box_changed=true;
}
function getParems()
{
    time=document.getElementById("custTime").value;
    if (document.getElementById("big").checked)
    {
        tile_h=50;
        tile_w=50;
    }
    else
    {
        tile_h=25;
        tile_w=25;
    }
    height=document.getElementById("custNRows").value*tile_h;
    width=document.getElementById("custNCols").value*tile_w;
    incR=document.getElementById("incR").value;
    incC=document.getElementById("incC").value;

}
function getSettingsParems()
{
    if (document.getElementById("regular").checked)
    {
        controls = regular_controls;
    }
    else
    {
        controls = vim_controls;
    }
}
function okButton()
{
    if (custom_box_changed)
    {
        customOn();
        getParems();
        checkForParemErrors();

        document.getElementById("time").innerHTML=changeTimeFormat(time);
        setGameVisible();
    }
    else
    {
        cancelButton();
    }
}
function cancelButton()
{
    customOff();
    custom_box_changed=false;
    setGameVisible();
    resetToDefault();
}
function okButtonSettings()
{
    if (settings_box_changed)
    {
        getSettingsParems();
        setGameVisible();
    }
    else
    {
        cancelButtonSettings();
    }
}
function cancelButtonSettings()
{
    settings_box_changed=false;
    setGameVisible();
    resetToDefaultSettings();
}
function beginGame()
{
    setGameVisible();
    game_active=true;
    score=0;
    document.getElementById("score").innerHTML=score;
    if (!customized)
    {
        time=120;
        incR=1;
        incC=1;
        tile_h=25;
        tile_w=25;
        height=5*tile_h;
        width=5*tile_w;
    }
    else
    {
        getParems();
        checkForParemErrors();
        getSettingsParems();
    }
    document.getElementById("time").innerHTML=changeTimeFormat(time);
    maze();
    if (!game_timer)
    {
        game_timer=true;
        handleTime();
    }
}
function handleTime()
{
    if (game_timer)
    {
        var sd=new Date();
        time=time-1;


        document.getElementById("time").innerHTML=changeTimeFormat(time);
        if (time<=0)
            gameOver();
        var ed=new Date();
        setTimeout(function(){handleTime();},1000-(ed-sd));
    }
}
function changeTimeFormat(t)
{
    var min=Math.floor(t/60);
    if (min<10)
        min="0"+min;
    var seconds=t%60;
    if (seconds<10)
        seconds="0"+seconds;
    return min+":"+seconds;
}
function checkForHS()
{
    getscore(function(_user_name, _hs, all){
        setHS(_hs, _user_name);
    });
}
function setHS(_hs, _user_name)
{
    hs = _hs;
    user_name = _user_name;
    document.getElementById("hs").innerHTML=escapeHtml(hs);
    document.getElementById("user_name").innerHTML=escapeHtml(user_name);
}
function showTop10Highscores(){
    getscore(function(_user_name, _hs, array){
        populateTop10(array);
    });
}

function populateTop10(array){
    var table = document.getElementById("highscores-table");
    for (var i=1, len=table.rows.length; i<len; i++)
        table.deleteRow(1);
    for (var i=0; i<array.length; i++){
        var row = table.insertRow();
        row.insertCell().textContent = i+1;
        row.insertCell().textContent = array[i].name;
        row.insertCell().textContent = array[i].score;
    }
}

//taken from http://shebang.brandonmintern.com/foolproof-html-escaping-in-javascript/
function escapeHtml(str) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
};
function gameOver()
{
    game_timer=false;
    game_active=false;
    clearScreen();
    showLoading();
    getscore(function(_user_name, _hs, all){
        if (((all.length == 0) || (score>=all[all.length-1].score) || (all.length < 10)) && !customized)
        {
            user_name=prompt("Your score is in the top 10!\nPlease enter your user name:");
            hash=CryptoJS.MD5(user_name + " " + score + " #ecc>`r:fP");
            submit_score(user_name, score, hash);
            if (score > _hs)
                setHS(score, user_name);
        }
        else
        {
            alert("Time's up!\nYou scored "+score+" points.");
        }
    });
}
function showLoading()
{
    ctx.fillStyle = "blue";
    ctx.font = "bold 16px Arial";
    ctx.fillText("Loading...", 100, 100);
}
function maze()
{


    nrows=height/tile_h;
    ncols=width/tile_w;
    board=new Array(nrows);
    for (var i=0;i<nrows;i++)
        board[i]=new Array(ncols);
    for (var i=0;i<nrows;i++)
    {
        for (var j=0;j<ncols;j++)
            board[i][j]=new tile(i,j,0,0,null,false);
    }
    start_loc=Math.floor(Math.random()*nrows);
    end_loc=Math.floor(Math.random()*nrows);
    player.row=start_loc;
    player.col=0;
    board[player.row][player.col].marked=true;
    bonus=1;
    ellersAlgo();
    drawGame();

}



function drawGame()
{
    clearScreen();
    drawBoard();
    drawStartAndEnd();
    player.draw();
}

function drawBoard()
{
    for (var i=0;i<nrows;i++)
    {
        for (var j=0;j<ncols;j++)
        {
            board[i][j].draw();
        }
    }
    ctx.beginPath();
    ctx.moveTo(0,0);
    ctx.lineTo(0,height);
    ctx.moveTo(0,0);
    ctx.lineTo(width,0);
    ctx.lineWidth=1;
    ctx.strokeStyle="black";
    ctx.stroke();

}


/* eller's algorithm*/
function ellersAlgo()
{
    set=new Array();

    for (var r=0;r<nrows;r++)
    {
        for (var j=0;j<ncols;j++)
        {
            if (board[r][j].set==null)
            {
                var num_set=getEmpty(set);
                set[num_set]=new Array(1);
                set[num_set][0]=board[r][j];
                board[r][j].set=num_set;
            }
        }

        if (r!=nrows-1)
        {

            for (var j=0;j<ncols-1;j++)
            {
                if (board[r][j].set!=board[r][j+1].set && board[r][j].right==0)
                {
                    if (Math.random()<0.5)
                        board[r][j].right=1;
                    else
                    {
                        board[r][j].right=0;
                        mergeSets(board[r][j].set,board[r][j+1].set);
                    }
                }
                else
                    board[r][j].right=1;
            }
            board[r][ncols-1].right=1;


            for (var i=0;i<set.length;i++)
            {
                if (set[i].length)
                {
                    var open_cell=Math.floor(Math.random()*set[i].length);
                    board[r][set[i][open_cell].col].bottom=0;
                    for (var j=0;j<set[i].length;j++)
                    {
                        if (j==open_cell)
                            continue;
                        else
                        {
                            if (Math.random()<0.5)
                                board[r][set[i][j].col].bottom=0;
                            else
                                board[r][set[i][j].col].bottom=1;
                        }
                    }
                }
            }



            for (var j=0;j<ncols;j++)
            {
                if (!board[r][j].bottom)
                {
                    board[r+1][j].set=board[r][j].set;
                    set[board[r][j].set].push(board[r+1][j]);
                }
            }

            for (var i=0;i<set.length;i++)
            {
                for (var j=0;j<set[i].length;j++)
                {
                    if (set[i][j].row==r)
                    {
                        set[i]=elimFromArray(set[i][j],set[i]);
                        j=-1;
                    }
                }
            }
        }

        else
        {
            for (var j=0;j<ncols-1;j++)
            {
                if (board[nrows-1][j].set==board[nrows-1][j+1].set)
                    board[nrows-1][j].right=1;
                else
                {
                    board[r][j].right=0;
                    mergeSets(board[nrows-1][j].set,board[nrows-1][j+1].set);
                }
                board[nrows-1][j].bottom=1;
            }
            board[nrows-1][ncols-1].bottom=1;
            board[nrows-1][ncols-1].right=1;
        }
    }
}


function mergeSets(n1,n2)
{
    for (var i=0;i<set[n2].length;i++)
    {
        set[n1].push(set[n2][i]);
        board[set[n2][i].row][set[n2][i].col].set=n1;
    }
    set[n2]=new Array(0);
}
function getEmpty(arr)
{
    for (var i=0;i<arr.length;i++)
    {
        if (!arr[i].length)
            return i;
    }
    return arr.length;
}

function elimFromArray(cell,arr)
{
    var n=arr.length;
    var arr_temp=new Array(n-1);
    if (n>1)
    {


        var j=0;
        for (var i=0;i<n;i++)
        {
            if (arr[i]!=cell)
            {
                arr_temp[j]=arr[i];
                j++
            }
        }
    }
    return arr_temp;

}


function drawStartAndEnd()
{
    ctx.beginPath();
    ctx.moveTo(0,start_loc*tile_h);
    ctx.lineTo(0,(start_loc+1)*tile_h);
    ctx.strokeStyle="green";
    ctx.lineWidth=10;
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(ncols*tile_w,(end_loc)*tile_h);
    ctx.lineTo(ncols*tile_w,(end_loc+1)*tile_h);
    if (width==maxwidth)
        ctx.lineWidth=10;
    else
        ctx.lineWidth=5;
    ctx.strokeStyle="red";
    ctx.stroke();
}

function clearScreen()
{
    ctx.rect(0,0,maxwidth,maxheight);
    ctx.fillStyle="white";
    ctx.fill();
}

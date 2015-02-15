<!DOCTYPE html>
<html>
    <head>
        <title>Maze game</title>
        <? include '../../head.php'; ?>
<link rel="stylesheet" type="text/css" href="../../styles/styles.css">
<link rel="stylesheet" type="text/css" href="./styles.css">
    </head>

    <body onload="onLoad();" onkeydown="keyDown(event);">
<?include '../../nav_bar.php'?>
<div id="content">
    <p id="debug"></p>
    <div id="mainBar">
        <table>
            <tr>
                <td><button onclick="settings()" type="button">Settings</button></td>
                <td><button onclick="custom()" type="button">Custom</button></td>
                <td><button onclick="beginGame()" type="button">Start</button></td>
                <td><b>Time:</b> <span id="time">02:00</span></td>
                <td><b>Score:</b> <span id="score">0</span></td>
                <td><span id="hs_text"><b>Highscore:</b> <span id="hs"></span> (<span id="user_name"></span>) </span></td>
                <td><button onclick="highscores()" type="button">Show Top 10</button></td>
                <td><b>Custom Mode:</b> <span id="custom_mode"></span></td>
            </tr>
        </table>
    </div>

    <div class="form" id="customBox">
        <table>
        <tr><td>Time: <input  onchange="onChangeCustomBox()" type="number" min="0" id="custTime" value="120"> seconds</td></tr>
        <tr><td>Number of columns: <input  onchange="onChangeCustomBox()"  type="number" min="5" value="5" id="custNCols" value="120"></td></tr>
        <tr><td>Number of rows: <input  onchange="onChangeCustomBox()"  type="number" min="5" value="5" id="custNRows" value="120"></td></tr>
        <tr><td>Increment: &nbsp; Rows: <input  onchange="onChangeCustomBox()"  type="number" id="incR" min="0" max="10" value="1">
        &nbsp; Columns: <input  onchange="onChangeCustomBox()" type="number" id="incC" min="0" max="10" value="1"></td></tr>
        <tr><td> Size of tiles: <input onchange="onChangeCustomBox()" name="size" type="radio" id="big">Big</input>
        &nbsp; <input onchange="onChangeCustomBox()" name="size" type="radio" id="small" checked="checked">Small</input></td></tr>
        <tr>
        <td><button onclick="okButton()" id="okButton">Ok</button>&nbsp;<button onclick="cancelButton()" id="cancelButton">Cancel</button>&nbsp;<button onclick="resetToDefault()" id="resetButton">Reset</button></td>
        </tr>
        </table>
    </div>
    <div class="form" id="settingsBox">
    <table>
    <tr>
    <td>Controls:
        <input onchange="onChangeSettingsBox()" name="controls" type="radio" id="regular" checked="checked">Regular</input>
        &nbsp; <input onchange="onChangeSettingsBox()" name="controls" type="radio" id="vim">Vim</input>
    </td>
    </tr>
    <tr>
    <td><button onclick="okButtonSettings()" id="okButtonSettings">Ok</button>&nbsp;<button onclick="cancelButtonSettings()" id="cancelButtonSettings">Cancel</button>&nbsp;<button onclick="resetToDefaultSettings()" id="resetButtonSettings">Reset</button></td>
    </tr>
    </table>
    </div>
    <div id="gameBoard">
        <canvas id="game" height="300" width="1350"/>
    </div>
    <div id="highscores-list">
    <table id="highscores-table" border=1>
    <tr>
        <th>Position</th>
        <th>Name</th>
        <th>Score</th>
    </tr>
    </table>
    </div>
    </div>
    <script src="../../js/md5.js"></script>
         <script src="highscores_submit.js"></script>
         <script src="highscores_get.js"></script>
        <script src="logic.js"></script>

    </body>
</html>

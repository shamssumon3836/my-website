<!DOCTYPE html>
<html>
    <head>
        <title>Maze game</title>
        <? include '../../head.php'; ?>
<link rel="stylesheet" type="text/css" href="../../styles.css">
<link rel="stylesheet" type="text/css" href="./styles.css">
<script src="http://crypto-js.googlecode.com/svn/tags/3.0.2/build/rollups/md5.js"></script>
         <script src="highscores_submit.js"></script>
         <script src="highscores_get.js"></script>
        <script src="logic.js"></script>

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
                <td><b>Custom Mode:</b> <span id="custom_mode"></span></td>
            </tr>
        </table>
    </div>

    <div class="form" id="customBox">
        <p>Time: <input  onchange="onChangeCustomBox()" type="number" min="0" id="custTime" value="120"> seconds</p>
        <p>Number of columns: <input  onchange="onChangeCustomBox()"  type="number" min="5" value="5" id="custNCols" value="120"></p>
        <p>Number of rows: <input  onchange="onChangeCustomBox()"  type="number" min="5" value="5" id="custNRows" value="120"></p>
        <p>Increment: &nbsp; Rows: <input  onchange="onChangeCustomBox()"  type="number" id="incR" min="0" max="10" value="1">
        &nbsp; Columns: <input  onchange="onChangeCustomBox()" type="number" id="incC" min="0" max="10" value="1"></p>
        <p> Size of tiles: <input onchange="onChangeCustomBox()" name="size" type="radio" id="big">Big</input>
        &nbsp; <input onchange="onChangeCustomBox()" name="size" type="radio" id="small" checked="checked">Small</input></p>
        <p><button onclick="okButton()" id="okButton">Ok</button>&nbsp;<button onclick="cancelButton()" id="cancelButton">Cancel</button>&nbsp;<button onclick="resetToDefault()" id="resetButton">Reset</button></p>
    </div>
    <div class="form" id="settingsBox">
    <p>Controls:
        <input onchange="onChangeSettingsBox()" name="controls" type="radio" id="regular" checked="checked">Regular</input>
        &nbsp; <input onchange="onChangeSettingsBox()" name="controls" type="radio" id="vim">Vim</input>
    </p>
    <p><button onclick="okButtonSettings()" id="okButtonSettings">Ok</button>&nbsp;<button onclick="cancelButtonSettings()" id="cancelButtonSettings">Cancel</button>&nbsp;<button onclick="resetToDefaultSettings()" id="resetButtonSettings">Reset</button></p>
    </div>
    <div id="gameBoard">
        <canvas id="game" height="300" width="1350"/>
    </div>
    </div>
    </body>
</html>

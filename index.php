<?php
    require "PartyJeopardy.php";
    $game = PartyJeopardy::loadLastState();

    if(empty($game->getPlayers()) and empty($_GET["state"])){
        $state = "setup";
    } else {
        $state = (!empty($_GET["state"]) ? $_GET["state"] : "");
    }
?>
<html lang="en">
<head>
    <title>PartyJeopardy</title>
    <link rel="stylesheet" href="./assets/styles/bootstrap.min.css">
    <style>
        .center-col {
            display: inline-block;
            float: none;
            margin-right: -4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <br>
        <br>
        <div class="row">
            <div class="col-lg-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Jeopardy
                    </div>
                    <div class="panel-body">
                        <?php
                            switch($state){
                                default:
                                case "board":
                                    require "QuestionBoard.php";
                                    showBoard($game, $game->getCurrentBoard());
                                    break;
                                case 'question':
                                    require "JeopardyQuestion.php";
                                    showQuestion($game, $_GET["board"], $_GET["category"], $_GET["question"]);
                                    break;
                                case 'end':
                                    ?>
                                        <h1>The End</h1>
                                        Thanks for playing PartyJeopardy! To play again, remove the <code>GameState</code> file in ./states or press the <a href="index.php?state=reset" class="btn btn-danger btn-xs">Reset</a> button!<br><br>
                                    <?php
                                    break;
                                case 'reset':
                                    @unlink("./states/GameState");
                                    header("Location: index.php");
                                    break;
                                case 'updatePoints':
                                    if(isset($_GET["add"])){
                                        $game->addPlayerPoints($_GET["player"], (int)$_GET["value"]);
                                    } else {
                                        $game->subtractPlayerPoints($_GET["player"], (int)$_GET["value"]);
                                    }
                                    header("Location: index.php");
                                    break;
                                case 'setup':
                                    if(is_file("./states/GameState")){
                                        ?>
                                            <div class="alert alert-danger">There's already a game in progress. You should press <a href="index.php?state=reset" class="btn btn-danger btn-xs">Reset</a> before you try to start a new one.</div>
                                        <?php
                                    }
                                    ?>
                                        <h1>PartyJeopardy Setup</h1>
                                        Welcome to PartyJeopardy! This is FLOSS software by Niklas Kreer to play the famous Jeopardy! quiz game on a local event.<br>
                                        To play, you'll need the following:<br><br>
                                        <ul>
                                            <li>A JSON file with your clues in the <code>game</code> folder. An example is included with the software.</li>
                                            <li>2-6 Players</li>
                                            <li>A JavaScript enabled Web-Browser. PartyJeopardy was tested to work with Chromium.</li>
                                        </ul><hr>
                                        We're now going to set up the game.<br>
                                        <form class="form-inline" action="index.php" method="get">
                                            <input type="hidden" name="state" value="hiddenSetup">
                                            <h3>Clues</h3>
                                            Please tell me what clues you want to play with.<br><br>
                                            <select class="form-control" name="file">
                                            <?php
                                            foreach(scandir("game") as $file){
                                                if(is_file("game/".$file)){
                                                    echo '<option>'.$file.'</option>';
                                                }
                                            }
                                            ?>
                                            </select>
                                            <br>
                                            <h3>Players</h3>
                                            Now, please tell me the names of all players. One name per row.<br><br>
                                            <textarea class="form-control" name="players" rows="4" placeholder="Player 1..."></textarea><br><br>
                                            <h3>Daily Double</h3>
                                            How many Daily Doubles do you want to have in your game?<br><br>
                                            <input type="text" name="doubles" value="3" class="form-control"><br><br>
                                            <h3>Finish setup</h3>
                                            We're done! Click the button to initialise the game.<br>
                                            <input type="submit" class="btn btn-success" value="Start Game">
                                        </form>
                                    <?php
                                    break;
                                case 'hiddenSetup':
                                    $game->setupQuestions($_GET["file"], (int)$_GET["doubles"]);
                                    $game->players = explode("\n", urldecode($_GET["players"]));
                                    $game->saveState();
                                    header("Location: index.php");
                                    break;
                                case 'buzzerTest':
                                    require "JeopardyQuestion.php";
                                    enableBuzzer($game);
                                    ?>
                                    <h1>Buzzer Test</h1>
                                    <div class="well" id="buzzerName"></div><br><br>
                                    Each player gets their own key they can use to activate the buzzer. This functionality is supported for up to six players. You can try if it works correctly on this page.<br><br>
                                    <table class="table table-bordered table-condensed table-striped">
                                        <tr>
                                            <td><b>Player</b></td>
                                            <td><b>Key</b></td>
                                        </tr>
                                        <tr>
                                            <td>Player 1</td>
                                            <td>A</td>>
                                        </tr>
                                        <tr>
                                            <td>Player 2</td>
                                            <td>L</td>
                                        </tr>
                                        <tr>
                                            <td>Player 3</td>
                                            <td>U</td>
                                        </tr>
                                        <tr>
                                            <td>Player 4</td>
                                            <td>B</td>
                                        </tr>
                                        <tr>
                                            <td>Player 5</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>Player 6</td>
                                            <td>9</td>
                                        </tr>
                                        <tr>
                                            <td>Reset Buzzers</td>
                                            <td>R</td>
                                        </tr>
                                    </table>
                                    <?php
                                    break;
                                case 'waiting': 
                                    ?>
                                    <script>
                                        // Update this every second
                                        setInterval(updateTime, 1000);

                                        function updateTime(){
                                            document.getElementById("time").innerHTML = "<h1>" + (new Date()).toLocaleTimeString() + "</h1>"
                                        }
                                    </script>
                                    <style>
                                        /* Make the font a lot bigger, just for the time */
                                        h1 {
                                            font-size: 100px;
                                        }
                                    </style>
                                    <div id="time" class="text-center"></div>
                                    <?php
                                    break;
                            }
                            if(isset($_GET["redirect"])){
                                header("Location: index.php?".urldecode($_GET["redirect"]));
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Players
                    </div>
                    <div class="panel-body">
                        <?php
                            if(count($game->getPlayers()) > 0){
                                foreach($game->getPlayers() as $id => $player){
                                    echo '<h3>'.$player.'</h3>';
                                    echo '<h4>'.$game->getPlayerPoints($id).' Points</h4>';
                                    echo '<form method="get" action="index.php">';
                                    echo '<input type="hidden" name="state" value="updatePoints">';
                                    echo '<input type="hidden" name="player" value="'.$id.'">';
                                    echo '<div class="input-group">';
                                    echo '<input type="text" name="value" placeholder="Add or subtract" class="form-control">';
                                    echo '<span class="input-group-btn">';
                                    echo '<input type="submit" name="add" value="+" class="btn btn-success">';
                                    echo '<input type="submit" name="remove" value="-" class="btn btn-danger">';
                                    echo '</span>';
                                    echo '</div>';
                                    echo '</form>';
                                }
                            } else {
                                ?>
                                <div class="text-center">
                                    <h3>NO PLAYERS</h3>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Game Tools
                    </div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-justified">
                            <a href="index.php?state=end" class="btn btn-danger">End Game</a>
                            <a href="https://github.com/nkreer/PartyJeopardy" class="btn btn-info">Source Code</a>
                        </div>
                        <div class="btn-group btn-group-justified">
                            <a href="index.php?state=board" class="btn btn-default">Show Board</a>
                        </div>
                        <div class="btn-group btn-group-justified">
                            <a href="index.php?state=setup" class="btn btn-default">PartyJeopardy Setup</a>
                        </div>
                        <div class="btn-group btn-group-justified">
                            <a href="index.php?state=buzzerTest" class="btn btn-default">Test buzzers</a>
                        </div>
                        <div class="btn-group btn-group-justified">
                            <a href="index.php?state=waiting" class="btn btn-default">Waiting screen</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <hr>
            <div class="text-right">
                PartyJeopardy is FLOSS software by Niklas Kreer. It is available on <a href="https://github.com/nkreer/PartyJeopardy">GitHub</a> and licenced to the public domain.
            </div>
        </footer>
    </div>
</body>
</html>
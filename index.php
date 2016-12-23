<?php
    require "PartyJeopardy.php";
    $game = PartyJeopardy::loadLastState();

    $state = (!empty($_GET["state"]) ? $_GET["state"] : "");
?>
<html lang="en">
<head>
    <title>PartyJeopardy</title>
    <link rel="stylesheet" href="./assets/styles/bootstrap.min.css">
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
                                        Thanks for playing PartyJeopardy! To play again, remove the <code>GameState</code> file in ./states!<br>
                                    <?php
                                    break;
                                case 'updatePoints':
                                    if(isset($_GET["add"])){
                                        $game->addPlayerPoints($_GET["player"], (int)$_GET["value"]);
                                    } else {
                                        $game->subtractPlayerPoints($_GET["player"], (int)$_GET["value"]);
                                    }
                                    header("Location: index.php");
                                    break;
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
                        ?>
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
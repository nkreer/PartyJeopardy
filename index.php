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
                        Player settings
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <hr>
            <div class="text-right">
                PartyJeopardy is free Software by Niklas Kreer. It is available on <a href="https://github.com/nkreer/PartyJeopardy">GitHub</a> and licenced to the public domain.
            </div>
        </footer>
    </div>
</body>
</html>
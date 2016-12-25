<?php

function enableBuzzer(PartyJeopardy $game){
    // What follows is ugly JavaScript
    ?>
        <script>
            var active = false;

            addEventListener ('keydown', function (event){
                var char = String.fromCharCode(event.which);
                if(active == false){
                    active = true;
                    switch(char){
                        case 'A':
                            buzzer(0);
                            break;
                        case 'L':
                            buzzer(1);
                            break;
                        case 'U':
                            buzzer(2);
                            break;
                        case 'B':
                            buzzer(3);
                            break;
                        case '1':
                            buzzer(4);
                            break;
                        case '9':
                            buzzer(5);
                            break;
                        default:
                            active = false;
                            break;
                        }
                    } else {
                        if(char == "R"){
                            active = false;
                            buzzer("reset");
                        }
                    }
            });

            function buzzer(buzzerId){
                switch(buzzerId){
                    default:
                        active = false;
                        break;
                    case 'reset':
                        document.getElementById("buzzerName").innerHTML = "";
                        break;
                    <?php
                    foreach($game->getPlayers() as $id => $player){
                        echo 'case '.$id.':'."\n".' document.getElementById("buzzerName").innerHTML = "<h1>'.trim($player).'</h1>";'."\n".' break;'."\n"; 
                    }
                    ?>
                }
            }            
        </script>
    <?php
}

function showQuestion(PartyJeopardy $game, $board, $category, $id){
    echo '<div class="text-center">';
    if($game->isDailyDouble($board, $category, $id) and empty($_GET["play"])){
        echo '<h1>DAILY DOUBLE</h1>';
        echo '<a href="index.php?state=question&play=yes&board='.$board.'&category='.$category.'&question='.$id.'" class="btn btn-primary btn-lg">PLAY NOW</a>';
    } else {
        ?>
        <!-- Button functionality to reveal the correct question/answer and the buzzer -->
        <script>
            function showAnswer(){
                document.getElementById("answer").style.display = "block";
            }
        </script>
        <div id="buzzerName"></div>
        <?php
        enableBuzzer($game);
        $question = $game->getQuestion($board, $category, $id);
        $game->playQuestion($board, $category, $id);
        if(isset($question["image"])){
            echo '<img src="'.$question["image"].'" class="img-thumbnail"><br>';
        }
        echo '<h2>'.$question["question"].'</h2><br><hr><br>';
        echo '<a onclick="showAnswer()" class="btn btn-info">Show Question</a><br><br>';
        echo '<div id="answer" style="display:none;"><div class="panel panel-info"><div class="panel-heading">Answer</div><div class="panel-body"><h2>'.$question["answer"].'</h2></div></div></div>';
        foreach($game->getPlayers() as $playerId => $player){
            echo '<a class="btn btn-success btn-xs" href="index.php?state=updatePoints&add=yes&value='.$game->getQuestionValue($id, $board).'&player='.$playerId.'"> Give points to '.$player.'</a> ';
        }
    }
    echo '<br><br><a href="index.php" class="btn btn-info btn-xs">Return to Board</a>';
    echo '</div>';
}
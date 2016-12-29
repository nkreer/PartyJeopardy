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
                    var sound = new Audio("assets/sounds/buzzer.wav");
                    switch(char){
                        case 'A':
                            buzzer(0);
                            sound.play();
                            break;
                        case 'L':
                            buzzer(1);
                            sound.play();
                            break;
                        case 'U':
                            buzzer(2);
                            sound.play();
                            break;
                        case 'B':
                            buzzer(3);
                            sound.play();
                            break;
                        case '1':
                            buzzer(4);
                            sound.play();
                            break;
                        case '9':
                            buzzer(5);
                            sound.play();
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
                        echo 'case '.$id.':'."\n".' document.getElementById("buzzerName").innerHTML = "<h1 style=\'color:red;\'>'.trim($player).'</h1>";'."\n".' break;'."\n"; 
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
        // Play a sound
        echo '<script>var sound = new Audio("assets/sounds/dailyDouble.wav"); sound.play();</script>';
        echo '<h1>DAILY DOUBLE</h1>';
        echo '<a href="index.php?state=question&play=yes&board='.$board.'&category='.$category.'&question='.$id.'" class="btn btn-primary btn-lg">PLAY NOW</a>';
    } else {
        enableBuzzer($game);
        $question = $game->getQuestion($board, $category, $id);
        $game->playQuestion($board, $category, $id);
        ?>
        <!-- Button functionality to reveal the correct question/answer -->
        <script>
            function showAnswer(){
                document.getElementById("answer").innerHTML = '<div class="panel panel-info"><div class="panel-body"><h2><?php echo $question["answer"]; ?></h2></div></div>';
            }
        </script>
        <div id="buzzerName"></div>
        <?php
        echo '<h1>'.$question["question"].'</h1>';
        if(isset($question["image"])){
            echo '<img src="'.$question["image"].'" class="img-thumbnail"><br>';
        }
        echo '<hr><div id="answer"><a onclick="showAnswer()" class="btn btn-info">Show Solution</a><br><br></div>';
        // Give points
        foreach($game->getPlayers() as $playerId => $player){
            echo '<a class="btn btn-success btn-xs" href="index.php?state=updatePoints&add=yes&value='.$game->getQuestionValue($id, $board).'&player='.$playerId.'">Give points to '.$player.'</a> ';
        }
        echo '<br><br>';
        // Take points
        foreach($game->getPlayers() as $playerId => $player){
            echo '<a class="btn btn-danger btn-xs" href="index.php?state=updatePoints&remove=yes&value='.$game->getQuestionValue($id, $board).'&player='.$playerId.'&redirect='.urlencode("state=question&board=".$board."&question=".$id."&category=".$category."&play=yes").'">Take from '.$player.'</a> ';
        }
    }
    echo '<br><br><a href="index.php" class="btn btn-info btn-xs">Return to Board</a>';
    echo '</div>';
}
<?php

function showQuestion(PartyJeopardy $game, $board, $category, $id){
    echo '<div class="text-center">';
    if($game->isDailyDouble($board, $category, $id) and empty($_GET["play"])){
        echo '<h1>DAILY DOUBLE</h1>';
        echo '<a href="index.php?state=question&play=yes&board='.$board.'&category='.$category.'&question='.$id.'" class="btn btn-primary btn-lg">PLAY NOW</a>';
    } else {
        $question = $game->getQuestion($board, $category, $id);
        $game->playQuestion($board, $category, $id);
        if(isset($question["image"])){
            echo '<img src="'.$question["image"].'" class="img-thumbnail"><br>';
        }
        echo '<h2>'.$question["question"].'</h2><br><hr><br>';
        echo '<script>function showAnswer() {document.getElementById("answer").style.display = "block";}</script>';
        echo '<a onclick="showAnswer()" class="btn btn-info">Show Question</a><br><br>';
        echo '<div id="answer" style="display:none;"><div class="panel panel-info"><div class="panel-heading">Answer</div><div class="panel-body"><h2>'.$question["answer"].'</h2></div></div></div>';
        foreach($game->getPlayers() as $playerId => $player){
            echo '<a class="btn btn-success btn-xs" href="index.php?state=updatePoints&add=yes&value='.$game->getQuestionValue($id, $board).'&player='.$playerId.'"> Give points to '.$player.'</a> ';
        }
    }
    echo '<br><br><a href="index.php" class="btn btn-info btn-xs">Return to Board</a>';
    echo '</div>';
}
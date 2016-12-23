<?php

function showQuestion(PartyJeopardy $game, $board, $category, $id){
    echo '<div class="text-center">';
    if($game->isDailyDouble($board, $category, $id) and empty($_GET["play"])){
        echo '<h1>DAILY DOUBLE</h1>';
        echo '<a href="index.php?state=question&play=yes&board='.$board.'&category='.$category.'&question='.$id.'" class="btn btn-primary btn-lg">PLAY NOW</a>';
    } else {
        $question = $game->getQuestion($board, $category, $id);
        $game->playQuestion($board, $category, $id);
        echo '<h2>'.$question["question"].'</h2><br><hr><br>';
        echo '<div id="answer" style="display:none;"><h1>'.$question["answer"].'</h1></div>';
        echo '<script>function showAnswer() {document.getElementById("answer").style.display = "block";}</script>';
        echo '<a onclick="showAnswer()" class="btn btn-info">Show Question</a><br><br>';
        foreach($game->getPlayers() as $playerId => $player){
            echo '<a class="btn btn-success btn-xs" href="index.php?state=updatePoints&add=yes&value='.PartyJeopardy::getQuestionValue($id).'&player='.$playerId.'"> Give points to '.$player.'</a> ';
        }
    }
    echo '<br><br><a href="index.php" class="btn btn-info btn-xs">Return to Board</a>';
    echo '</div>';
}
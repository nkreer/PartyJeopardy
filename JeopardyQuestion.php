<?php

function showQuestion(PartyJeopardy $game, $board, $category, $id){
    echo '<div class="text-center">';
    if($game->isDailyDouble($board, $category, $id) and empty($_GET["play"])){
        echo '<h1>DAILY DOUBLE</h1>';
        echo '<a href="index.php?state=question&play=yes&board='.$board.'&category='.$category.'&question='.$id.'">PLAY NOW</a>';
    } else {
        $question = $game->getQuestion($board, $category, $id);
        $game->playQuestion($board, $category, $id);
        echo '<h2>'.$question["question"].'</h2>';
    }
    echo '<a href="index.php" class="btn btn-primary btn-lg">Return to Board</a>';
    echo '</div>';
}
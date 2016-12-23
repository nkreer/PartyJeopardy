<?php

function showBoard(PartyJeopardy $game, $board){
    $gameBoard = $game->getBoard($board);
    echo '<div class="row">';
    foreach($gameBoard["categories"] as $category => $questions){
        ?>
            <div class="col-lg-3 text-center">
                <h3><?php echo $category; ?></h3>
                <hr>
                <?php
                    foreach($questions as $id => $question){
                        if(!$game->isQuestionPlayed($board, $category, $id)){
                            ?>
                                <a href="index.php?state=question&board=<?php echo $board; ?>&question=<?php echo $id; ?>&category=<?php echo $category; ?>" class="btn btn-primary btn-lg"><?php echo PartyJeopardy::getQuestionValue($id); ?></a>
                            <?php
                        } else {
                            ?>
                                <a href="#" class="btn btn-danger btn-lg"><?php echo PartyJeopardy::getQuestionValue($id) ?></a>
                            <?php
                        }
                        echo '<hr>';
                    }
                ?>
            </div>
        <?php
    }
    echo '</div>';
}
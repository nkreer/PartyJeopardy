<?php

class PartyJeopardy{

    public static function loadLastState(){
        if(file_exists("./states/GameState")){
            return unserialize(file_get_contents("./states/GameState"));
        } else {
            return new PartyJeopardy();
        }
    }

    public function saveState(){
        @mkdir("./states");
        return file_put_contents("./states/GameState", serialize($this));
    }

    private $questions = [];
    private $playedQuestions = [];
    private $dailyDoubles = [];

    public $players = [];
    private $playerPoints = [];

    public function setupQuestions($file, $doubles = 3){
        $this->questions = json_decode(file_get_contents("./game/".$file), true)["boards"];
        for($dailyDoubles = 1; $dailyDoubles <= $doubles; $dailyDoubles++){
            $this->dailyDoubles[] = $this->pickRandomQuestion();
        }
        foreach($this->questions as $board => $data){
            foreach($data["categories"] as $category => $question){
                $this->playedQuestions[$board][$category] = [];
            }
        }
    }

    public function getPlayerPoints($player){
        if(empty($this->playerPoints[$player])){
            $this->playerPoints[$player] = 0;
        }
        return $this->playerPoints[$player];
    }

    public function addPlayerPoints($player, $points){
        $this->playerPoints[$player] += $points;
        $this->saveState();
    }

    public function subtractPlayerPoints($player, $points){
        $this->playerPoints[$player] -= $points;
        $this->saveState();
    }

    public function getPlayers(){
        return $this->players;
    }

    public function getQuestion($board, $category, $id){
        return $this->questions[$board]["categories"][$category][$id];
    }

    public function isDailyDouble($board, $category, $id){
        return in_array([$board, $category, $id], $this->dailyDoubles);
    }

    public function getCurrentBoard(){
        if(!$this->isPlayedEntirely(0)){
            return 0;
        } elseif(!$this->isPlayedEntirely(1)){
            return 1;
        } elseif(!$this->isPlayedEntirely(2)){
            return 2;
        } else {
            header("Location: index.php?state=end");
            return 0;
        }
    }

    public function isPlayedEntirely($board){
        $played = true;
        foreach($this->playedQuestions[$board] as $category => $questions){
            if(count($questions) < ($board !== 2 ? 5 : 1)){
                $played = false;
                break;
            }
        }
        return $played;
    }

    public function pickRandomQuestion(){
        $board = rand(0, 1);
        $categoryName = array_rand($this->questions[$board]["categories"]);
        $questionId = array_rand($this->questions[$board]["categories"][$categoryName]);
        return [$board, $categoryName, $questionId];
    }

    public function getBoard($count){
        return $this->questions[$count];
    }

    public function isQuestionPlayed($board, $category, $id){
        return isset($this->playedQuestions[$board][$category][$id]);
    }

    public function playQuestion($board, $category, $id){
        $this->playedQuestions[$board][$category][$id] = true;
        $this->saveState();
    }

    public function getQuestionValue($count, $board){
        switch($count){
            default:
            case 0:
                $basePoints = 100;
            break;
            case 1:
                $basePoints = 400;
            break;
            case 2:
                $basePoints = 600;
            break;
            case 3:
                $basePoints = 800;
            break;
            case 4:
                $basePoints = 1000;
            break;
        }
        if($board == 0){
            return $basePoints;
        } elseif($board == 1) {
            return $basePoints * 2;
        } else {
            return 100;
        }
    }

}
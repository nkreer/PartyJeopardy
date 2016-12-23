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


    const AMOUNT_DAILY_DOUBLE = 3;

    private $questions;
    private $playedQuestions = [];
    private $dailyDoubles = [];

    public function __construct(){
        $this->questions = json_decode(file_get_contents("./game/questions.json"), true)["boards"];
        for($dailyDoubles = 1; $dailyDoubles <= self::AMOUNT_DAILY_DOUBLE; $dailyDoubles++){
            $this->dailyDoubles[] = $this->pickRandomQuestion();
        }
        foreach($this->questions as $board => $data){
            foreach($data["categories"] as $category => $question){
                $this->playedQuestions[$board][$category] = [];
            }
        }
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

    public static function getQuestionValue($count){
        switch($count){
            default:
            case 0:
                return 20;
            break;
            case 1:
                return 50;
            break;
            case 2:
                return 100;
            break;
            case 3:
                return 200;
            break;
            case 4:
                return 500;
            break;
        }
    }

}
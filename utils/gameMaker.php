<?php

// This script creates a random jeopardy game using jservice.io
echo "Starting generation\n";

$data["boards"] = [0 => [], 1 => [], 2 => []];

for($b = 0; $b <= 1; $b++){
	// Boards 1 + 2
	for($i = 1; $i <= 6; $i++) {
		$download = file_get_contents("http://jservice.io/api/category?id=".mt_rand(1, 10000));
		$download = json_decode($download, true);
		echo "Getting category ".$download["title"]." for board ".$b."\n";
		foreach($download["clues"] as $id => $clue){
			$data["boards"][$b]["categories"][$download["title"]][] = ["question" => $clue["question"], "answer" => $clue["answer"]];
			if($id >= 4){
				break;
			}
		}
	}
}

// Final Jeopardy
echo "Downloading Final Jeopardy\n";
$download = file_get_contents("http://jservice.io/api/category?id=".mt_rand(1, 10000));
$download = json_decode($download, true);
$clue = $download["clues"][array_rand($download["clues"])];
$data["boards"][2]["categories"][$download["title"]][] = ["question" => $clue["question"], "answer" => $clue["answer"]];

file_put_contents("../game/generated-".time().".json", json_encode($data, JSON_PRETTY_PRINT));
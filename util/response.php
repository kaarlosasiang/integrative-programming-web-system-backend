<?php
// response(200, true, ["username" => "clarence", "email" => "japinanclarence@gmail.com", "skills" => ["drawing", "singing", "dancing"]]);

function response(int $statusCode, bool $success, $data = [], $toCache = false)
{
	$responseData = [];
	//set content type
	header("Content-Type: application/json;charset=utf-8");

	if ($toCache == true) {
		header('Cache-control: max-age=60');
	} else {
		header('Cache-control: no-cache, no-store');
	}

	//set response code
	http_response_code($statusCode);
	//set reponse data
	$responseData["success"]  = $success;
	foreach ($data as $key => $value) {
		$responseData[$key] = $value;
	}

	echo json_encode($responseData);
}

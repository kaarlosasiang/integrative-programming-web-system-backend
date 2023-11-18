<?php

namespace api;

use model\SubjectModel;

require_once(__DIR__ . "/../model/SubjectModel.php");
require_once(__DIR__ . "/Controller.php");

class Subject extends Controller
{
	public function __construct()
	{
		$requestMethod = $_SERVER["REQUEST_METHOD"];

		switch ($requestMethod) {
			case "POST": {
					$this->create();
					break;
				}
			case "GET": {
					if (array_key_exists("id", $_GET) || !empty($_GET["id"])) {
						$this->show();
					} else if (array_key_exists("query", $_GET) || !empty($_GET["query"])) {
						$this->search();
					} else {
						$this->all();
					}
					break;
				}
			case "PATCH": {
					$this->update();
					break;
				}
			case "DELETE": {
					$this->delete();
					break;
				}
			default: {
					response(400, false, ["message" => "Request method: {$requestMethod} not allowed!"]);
					break;
				}
		}
	}
	public function create()
	{
		$data = json_decode(file_get_contents("php://input"));

		Controller::verifyJsonData($data);

		//set json data from request body
		$code = $data->code;
		$description = $data->description;
		$unit = $data->unit;
		$type = $data->type;



		$result = SubjectModel::create($code, $description, $unit, $type);

		if (!$result) {
			response(400, false, ["message" => "Registration failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Registered successfully!"]);
		}
	}
	public function show()
	{
		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		$results = SubjectModel::find($code, "code");

		if (!$results) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}

		response(200, true, $results);
	}
	public function search()
	{
		$query = isset($_GET["query"]) ? $_GET["query"] : null;

		$results = SubjectModel::search($query);

		if (!$results) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}

		response(200, true, ["data" => $results]);
	}
	public function all()
	{
		$results = SubjectModel::all();

		if (!$results) {
			response(200, false, ["message" => "No registered subjects currently"]);
			exit;
		}

		$numRows = count($results);

		foreach ($results as $result) {

			$returnData[] = [
				"code" => $result["code"],
				"description" => $result["description"],
				"unit" => $result["unit"],
				"type" => $result["type"]
			];
		}
		response(200, true, ["row_count" => $numRows, "data" => $returnData]);
	}
	public function update()
	{
		$data = json_decode(file_get_contents("php://input"));

		Controller::verifyJsonData($data);

		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		//set json data from request body
		$description = $data->description;
		$unit = $data->unit;
		$type = $data->type;


		if (!SubjectModel::find($code, "code")) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}

		$result = SubjectModel::update($code, $description, $unit, $type);

		if (!$result) {
			response(400, false, ["message" => "Update failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Update successfull!"]);
		}
	}
	public function delete()
	{
		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		$results = SubjectModel::find($code, "code");

		if (!$results) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}

		if (SubjectModel::delete($code, "code")) {
			response(200, true, ["message" => "Delete successful"]);
		} else {
			response(400, false, ["message" => "Delete Failed!"]);
		}
	}
}
new Subject();

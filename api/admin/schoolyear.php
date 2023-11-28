<?php

namespace api\admin;

use api\Controller;
use model\SchoolYearModel;
use middleware\AuthMiddleware;

require_once(__DIR__ . "/../../model/SchoolYearModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class SchoolYear extends Controller
{
	private $authResult;
	public function __construct()
	{
		$this->authResult = AuthMiddleware::authenticate();
		//verify user role
		Controller::verifyRole($this->authResult, Controller::ADMIN_ROLE);

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
		$year = $data->year;
		$semester = $data->semester;

		$result = SchoolYearModel::create($year, $semester);

		if (!$result) {
			response(400, false, ["message" => "Registration failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Registered successfully!"]);
		}
	}
	public function show()
	{
		$id = isset($_GET["id"]) ? $_GET["id"] : null;

		$results = SchoolYearModel::find($id, "id");

		if (!$results) {
			response(404, false, ["message" => "School year not found!"]);
			exit;
		}

		response(200, true, $results);
	}
	public function search()
	{
		$query = isset($_GET["query"]) ? $_GET["query"] : null;

		$results = SchoolYearModel::search($query);

		if (!$results) {
			response(404, false, ["message" => "School year not found!"]);
			exit;
		}

		response(200, true, ["data" => $results]);
	}
	public function all()
	{
		$results = SchoolYearModel::all();

		if (!$results) {
			response(200, false, ["message" => "No registered school year currently"]);
			exit;
		}

		$numRows = count($results);

		foreach ($results as $result) {

			$returnData[] = [
				"id" => $result["id"],
				"year" => $result["year"],
				"semester" => $result["semester"],
				"status" => $result["status"]
			];
		}
		response(200, true, ["row_count" => $numRows, "data" => $returnData]);
	}
	public function update()
	{
		$data = json_decode(file_get_contents("php://input"));

		Controller::verifyJsonData($data);

		$id = isset($_GET["id"]) ? $_GET["id"] : null;

		//set json data from request body
		$year = $data->year;
		$semester = $data->semester;
		$status = $data->status;


		if (!SchoolYearModel::find($id, "id")) {
			response(404, false, ["message" => "School year not found!"]);
			exit;
		}

		$result = SchoolYearModel::update($id, $year, $semester, $status);

		if (!$result) {
			response(400, false, ["message" => "Update failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Update successfull!"]);
		}
	}
	public function delete()
	{
		$id = isset($_GET["id"]) ? $_GET["id"] : null;

		$results = SchoolYearModel::find($id, "id");

		if (!$results) {
			response(404, false, ["message" => "School year not found!"]);
			exit;
		}

		if (SchoolYearModel::delete($id, "id")) {
			response(200, true, ["message" => "Delete successful"]);
		} else {
			response(400, false, ["message" => "Delete Failed!"]);
		}
	}
}
new SchoolYear();

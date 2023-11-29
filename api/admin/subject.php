<?php

namespace api\admin;

use api\Controller;
use model\SubjectModel;
use middleware\AuthMiddleware;
use model\FacultyModel;
use model\SchoolYearModel;

require_once(__DIR__ . "/../../model/SubjectModel.php");
require_once(__DIR__ . "/../../model/FacultyModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class Subject extends Controller
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
					if (array_key_exists("code", $_GET) || !empty($_GET["code"])) {
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
		$schoolyear = $data->schoolYear;
		$status = $data->status;
		$faculty = $data->faculty;

		$result = SubjectModel::create($code, $description, $unit, $type, $schoolyear, $status, $faculty);

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

		$schoolyear = SchoolYearModel::find($results["school_year"], "id")["school_year"];

		//fetch faculty assigned
		$faculty = FacultyModel::find($results["faculty"], "faculty_id");
		$facultyName = $faculty["first_name"] . " " . $faculty["middle_name"] . " " . $faculty["last_name"];


		$returnData = [
			"code" => $results["code"],
			"description" => $results["description"],
			"unit" => $results["unit"],
			"type" => $results["type"],
			"status" => $results["status"],
			"school_year" => $schoolyear,
			"faculty" => $facultyName,
			"created_at" => $results["created_at"],
			"updated_at" => $results["updated_at"]
		];
		response(200, true, $returnData);
	}
	public function search()
	{
		$query = isset($_GET["query"]) ? $_GET["query"] : null;

		$results = SubjectModel::search($query);

		if (!$results) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}

		foreach ($results as $result) {
			$schoolyear = SchoolYearModel::find($result["school_year"], "id")["school_year"];

			//fetch faculty assigned
			$faculty = FacultyModel::find($result["faculty"], "faculty_id");
			$facultyName = $faculty["first_name"] . " " . $faculty["middle_name"] . " " . $faculty["last_name"];

			$returnData[] = [
				"code" => $result["code"],
				"description" => $result["description"],
				"unit" => $result["unit"],
				"type" => $result["type"],
				"status" => $result["status"],
				"school_year" => $schoolyear,
				"faculty" => $facultyName,
				"created_at" => $result["created_at"],
				"updated_at" => $result["updated_at"]
			];
		}

		response(200, true, ["data" => $returnData]);
	}
	public function all()
	{
		$results = SubjectModel::all();

		if (!$results) {
			response(200, false, ["message" => "No registered subjects currently"]);
			exit;
		}

		foreach ($results as $result) {
			$schoolyear = SchoolYearModel::find($result["school_year"], "id")["school_year"];

			//fetch faculty assigned
			$faculty = FacultyModel::find($result["faculty"], "faculty_id");
			$facultyName = $faculty["first_name"] . " " . $faculty["middle_name"] . " " . $faculty["last_name"];

			$returnData[] = [
				"code" => $result["code"],
				"description" => $result["description"],
				"unit" => $result["unit"],
				"type" => $result["type"],
				"status" => $result["status"],
				"school_year" => $schoolyear,
				"faculty" => $facultyName
			];
		}

		$numRows = count($results);

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
		$schoolyear = $data->schoolYear;
		$status = $data->status;
		$faculty = $data->faculty;

		if (!SubjectModel::find($code, "code")) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}

		$result = SubjectModel::update($code, $description, $unit, $type, $schoolyear, $status, $faculty);

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

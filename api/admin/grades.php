<?php

namespace api\admin;

use api\Controller;
use model\UserModel;
use model\GradesModel;
use model\FacultyModel;
use model\SubjectModel;
use model\SchoolYearModel;
use middleware\AuthMiddleware;
use model\FacultySubjectsModel;

require_once(__DIR__ . "/../../model/SubjectModel.php");
require_once(__DIR__ . "/../../model/FacultyModel.php");
require_once(__DIR__ . "/../../model/FacultySubjectsModel.php");
require_once(__DIR__ . "/../../model/GradesModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class Grades extends Controller
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
						$this->facultySubjects();
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
		$code = $data->subjectCode;
		$facultyId = $data->facultyId;

		$result = FacultySubjectsModel::create($code, $facultyId);

		if (!$result) {
			response(400, false, ["message" => "Assignment Failed failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Assigned successfully!"]);
		}
	}
	public function all()
	{
		$results = FacultyModel::all();
		if (!$results) {
			response(200, false, ["message" => "No registered faculty currently"]);
			exit;
		}

		$numRows = count($results);

		response(200, true, ["row_count" => $numRows, "data" => $results]);
	}
	public function facultySubjects()
	{

		$facultyId = isset($_GET["id"]) ? $_GET["id"] : null;

		$results = FacultySubjectsModel::find($facultyId, "faculty_id", true);

		if (!$results) {
			response(200, false, ["message" => "No registered subjects currently"]);
			exit;
		}

		foreach ($results as $result) {

			$faculty = FacultyModel::find($facultyId, "faculty_id");

			$this->getFacultyFullname($faculty["user_id"]);

			$subjects = SubjectModel::find($result["subject_code"], "code");

			$returnData[] = [
				"code" => $subjects["code"],
				"description" => $subjects["description"],
				"unit" => $subjects["unit"],
				"type" => $subjects["type"],
				"status" => $subjects["status"],
				"created_at" => $subjects["created_at"],
				"updated_at" => $subjects["updated_at"]
			];
		}

		$numRows = count($results);

		response(200, true, ["row_count" => $numRows, "data" => $returnData]);
	}
	private function getFacultyFullname($facultyUserId)
	{
		$faculty = UserModel::find($facultyUserId, "user_id");
		$middlename = substr($faculty["middle_name"], 0, 1) . ".";
		return $faculty["first_name"] . " $middlename " . $faculty["last_name"];
	}
}
new Grades();

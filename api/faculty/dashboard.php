<?php

namespace api\admin;

use api\Controller;
use model\UserModel;
use model\CourseModel;
use model\FacultyModel;
use model\StudentModel;
use middleware\AuthMiddleware;
use model\FacultySubjectsModel;
use model\GradesModel;

require_once(__DIR__ . "/../../model/CourseModel.php");
require_once(__DIR__ . "/../../model/FacultyModel.php");
require_once(__DIR__ . "/../../model/StudentModel.php");
require_once(__DIR__ . "/../../model/FacultySubjectsModel.php");
require_once(__DIR__ . "/../../model/GradesModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class Dashboard extends Controller
{
	private $authResult;
	public function __construct()
	{
		$this->authResult = AuthMiddleware::authenticate();
		//verify user role
		Controller::verifyRole($this->authResult, Controller::FACULTY_ROLE);

		$requestMethod = $_SERVER["REQUEST_METHOD"];

		switch ($requestMethod) {

			case "GET": {
					$this->dashBoardData();
					break;
				}

			default: {
					response(400, false, ["message" => "Request method: {$requestMethod} not allowed!"]);
					break;
				}
		}
	}
	public function dashBoardData()
	{
		$id = isset($_GET["id"]) ? $_GET["id"] : null;

		if ($id !== $this->authResult) {
			response(403, false, ["message" => "Unauthorized"]);
			exit;
		}

		$faculty = FacultyModel::fetchId($id, "user_id")["faculty_id"];

		$results = FacultySubjectsModel::find($faculty, "faculty_id", true);

		if (!$results) {
			response(200, false, ["message" => "No registered subjects currently"]);
			exit;
		}

		foreach ($results as $result) {
			//fetch handled students
			$handledStudents = GradesModel::where([
				"subject_code" => $result["subject_code"],
				"faculty_id" => $faculty
			], true);

			//count students by subjects
			$studentCountBySub = count($handledStudents);

			$returnData[$result["subject_code"]] = $studentCountBySub;
		}
		response(200, true, ["data" => $returnData]);
	}
}
new Dashboard();

<?php

namespace api\admin;

use api\Controller;
use model\GradesModel;
use model\SchoolYearModel;
use middleware\AuthMiddleware;

require_once(__DIR__ . "/../../model/CourseModel.php");
require_once(__DIR__ . "/../../model/GradesModel.php");
require_once(__DIR__ . "/../../model/StudentModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class Dashboard extends Controller
{
	private $authResult;
	public function __construct()
	{
		$this->authResult = AuthMiddleware::authenticate();
		//verify user role
		Controller::verifyRole($this->authResult, Controller::STUDENT_ROLE);

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
		$studentId = isset($_GET["id"]) ? $_GET["id"] : null;

		$activeSchoolYear = SchoolYearModel::find("1", "status");
		$subjects = GradesModel::find($studentId, "student_id", true);

		response(200, true, ["active_school_year" => $activeSchoolYear, "subjects" => $subjects]);
	}
}
new Dashboard();

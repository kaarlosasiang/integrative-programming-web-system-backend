<?php

namespace api\admin;

use api\Controller;
use model\UserModel;
use model\GradesModel;
use model\FacultyModel;
use model\StudentModel;
use model\SubjectModel;
use model\SchoolYearModel;
use middleware\AuthMiddleware;
use model\FacultySubjectsModel;

require_once(__DIR__ . "/../../model/SubjectModel.php");
require_once(__DIR__ . "/../../model/FacultyModel.php");
require_once(__DIR__ . "/../../model/GradesModel.php");
require_once(__DIR__ . "/../../model/StudentModel.php");
require_once(__DIR__ . "/../../model/FacultySubjectsModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class Grades extends Controller
{
	private $authResult;
	public function __construct()
	{
		$this->authResult = AuthMiddleware::authenticate();

		Controller::verifyRole($this->authResult, Controller::FACULTY_ROLE);
		$requestMethod = $_SERVER["REQUEST_METHOD"];

		switch ($requestMethod) {
			case "GET": {
					if (array_key_exists("code", $_GET) || !empty($_GET["code"])) {
						$this->show();
					} else if (array_key_exists("id", $_GET) || !empty($_GET["id"])) {
						$this->all();
					}
					break;
				}
			case "PATCH": {
					$this->addGrades();
					break;
				}
			default: {
					response(400, false, ["message" => "Request method: {$requestMethod} not allowed!"]);
					break;
				}
		}
	}

	public function show()
	{
		$id = isset($_GET["id"]) ? $_GET["id"] : null;

		if ($id !== $this->authResult) {
			response(403, false, ["message" => "Unauthorized"]);
			exit;
		}

		$faculty = FacultyModel::find($id, "user_id");

		if (!$faculty) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		$subject = SubjectModel::find($code, "code");

		if (!$subject) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}
		// $facultyName = $faculty["first_name"] . " " . $faculty["middle_name"] . " " . $faculty["last_name"];

		//fetch students based on enrolled subject and faculty
		$fetchAssignedStudents = GradesModel::where([
			"subject_code" => $code,
			"faculty_id" => $faculty["faculty_id"]
		], true);

		if (!$fetchAssignedStudents) {
			response(200, true, ["message" => "No enrolled students currently!"]);
			exit;
		}
		//initialized return data
		$returnData = [];

		//loop through enrolled students
		foreach ($fetchAssignedStudents as $assignedStudent) {
			$studentName = StudentModel::find($assignedStudent["student_id"], "student_id");
			$studentName = UserModel::find($studentName["user_id"], "user_id");
			// Format fullname
			$middlename = substr($studentName["middle_name"], 0, 1) . ".";
			$fullname = $studentName["first_name"] . " $middlename " . $studentName["last_name"];

			$returnData[] = [
				"student_id" => $assignedStudent["student_id"],
				"fullname" => $fullname,
				"subject_code" => $assignedStudent["subject_code"],
				"grade" => $assignedStudent["grades"]
			];
		}

		response(200, true, ["data" => $returnData]);
	}
	public function all()
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
			$subjectInfo = SubjectModel::find($result["subject_code"], "code");

			$schoolyear = SchoolYearModel::find($subjectInfo["school_year"], "id")["school_year"];

			//fetch faculty fullname
			$userInfo = UserModel::find($id, "user_id");
			$middlename = substr($userInfo["middle_name"], 0, 1) . ".";
			$facultyName =  $userInfo["first_name"] . " $middlename " . $userInfo["last_name"];

			$returnData[] = [
				"code" => $subjectInfo["code"],
				"description" => $subjectInfo["description"],
				"unit" => $subjectInfo["unit"],
				"type" => $subjectInfo["type"],
				"status" => $subjectInfo["status"],
				"school_year" => $schoolyear,
				"faculty" => $facultyName
			];
		}

		$numRows = count($results);

		response(200, true, ["row_count" => $numRows, "data" => $returnData]);
	}
	public function addGrades()
	{

		$id = isset($_GET["id"]) ? $_GET["id"] : null;

		if ($id !== $this->authResult) {
			response(403, false, ["message" => "Unauthorized"]);
			exit;
		}

		$faculty = FacultyModel::find($id, "user_id");

		if (!$faculty) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		$subject = SubjectModel::find($code, "code");

		if (!$subject) {
			response(404, false, ["message" => "Subject not found!"]);
			exit;
		}

		$data = json_decode(file_get_contents("php://input"));
		Controller::verifyJsonData($data);

		$studentId = $data->student_id;
		$grades = $data->grade;

		//fetch students based on enrolled subject and faculty
		$fetchAssignedStudents = GradesModel::where([
			"subject_code" => $code,
			"faculty_id" => $faculty["faculty_id"],
			"student_id" => $studentId
		]);

		if (!$fetchAssignedStudents) {
			response(404, false, ["message" => "Student not found!"]);
			exit;
		}

		$result = GradesModel::addGrades($code, $studentId, $faculty["faculty_id"], $grades);

		if (!$result) {
			response(400, false, ["message" => "Failed to add grades"]);
			exit;
		}

		response(200, true, ["message" => "Successfully added grades"]);
	}
}
new Grades();

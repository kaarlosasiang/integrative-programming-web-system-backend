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
use model\StudentModel;

require_once(__DIR__ . "/../../model/SubjectModel.php");
require_once(__DIR__ . "/../../model/FacultyModel.php");
require_once(__DIR__ . "/../../model/StudentModel.php");
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
					if (array_key_exists("id", $_GET) || !empty($_GET["id"]) && array_key_exists("action", $_GET) && ($_GET["action"] === "assign_faculty")) {
						$this->assignFaculty();
					} else if (array_key_exists("action", $_GET) && ($_GET["action"] === "assign_student")) {
						$this->assignStudent();
					}
					break;
				}
			case "GET": {
					if (array_key_exists("action", $_GET) && $_GET["action"] == "unassigned_subjects") {
						// echo "2";
						$this->facultyUnassignedSubjects();
					} else if (array_key_exists("action", $_GET) &&  $_GET["action"] == "student_records") {
						// echo "3";
						$this->fetchStudentRecords();
					} else if (array_key_exists("id", $_GET) || !empty($_GET["id"])) {
						// echo "4";
						$this->facultySubjects();
					} else {
						// echo "5";
						$this->all();
					}
					break;
				}
			case "DELETE": {
					if (array_key_exists("action", $_GET) && $_GET["action"] == "unenroll") {
						// echo "2";
						$this->unenrollStudent();
					} else {
						$this->unassignFaculty();
					}
					break;
				}
			default: {
					response(400, false, ["message" => "Request method: {$requestMethod} not allowed!"]);
					break;
				}
		}
	}
	public function assignStudent()
	{
		$data = json_decode(file_get_contents("php://input"));

		Controller::verifyJsonData($data);

		//set json data from request body
		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		$facultyId = $data->facultyId;
		$studentId = $data->studentId;

		$facultyAssignedSubjects = FacultySubjectsModel::where([
			"faculty_id" => $facultyId,
			"subject_code" => $code
		]);

		if (!$facultyAssignedSubjects) {
			response(200, false, ["message" => "Subject is not handled by the faculty chosen!"]);
			exit;
		}

		$isStudentAssigned = GradesModel::where([
			"faculty_id" => $facultyId,
			"subject_code" => $code
		]);

		if ($isStudentAssigned) {
			response(409, false, ["message" => "Student is already enrolled to this subject!"]);
			exit;
		}

		$result = GradesModel::create($code, $studentId, $facultyId);

		if (!$result) {
			response(400, false, ["message" => "Assignment Failed failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Assigned successfully!"]);
		}
	}

	public function assignFaculty()
	{
		$data = json_decode(file_get_contents("php://input"));

		Controller::verifyJsonData($data);

		//set json data from request body
		$code = $data->subjectCode;

		$facultyId = isset($_GET["id"]) ? $_GET["id"] : null;

		$facultyAssignedSubjects = FacultySubjectsModel::where([
			"faculty_id" => $facultyId,
			"subject_code" => $code
		]);

		if ($facultyAssignedSubjects) {
			response(200, false, ["message" => "Subject is already assigned to faculty!"]);
			exit;
		}


		$result = FacultySubjectsModel::create($code, $facultyId);

		if (!$result) {
			response(400, false, ["message" => "Assignment Failed!"]);
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
	public function facultyUnassignedSubjects()
	{
		$facultyId = isset($_GET["id"]) ? $_GET["id"] : null;

		$results = FacultySubjectsModel::find($facultyId, "faculty_id", true);

		$subjects = SubjectModel::all();

		//if faculty has no assigned subjects, iterate all subjects
		if (!$results) {
			foreach ($subjects as $subject) {
				$returnData[] = [
					"code" => $subject["code"],
					"description" => $subject["description"],
					"unit" => $subject["unit"],
					"type" => $subject["type"],
					"status" => $subject["status"],
					"created_at" => $subject["created_at"],
					"updated_at" => $subject["updated_at"]
				];
			}
		} else {
			// Extract subject codes from $results
			$resultSubjectCodes = array_column($results, 'subject_code');

			// Filter subjects that are not in $results
			$missingSubjects = array_filter($subjects, function ($subject) use ($resultSubjectCodes) {
				return !in_array($subject['code'], $resultSubjectCodes);
			});
			//iterate all unassigned subjects
			foreach ($missingSubjects as $missingSubject) {
				$returnData[] = $missingSubject;
			}
		}
		response(200, true, ["data" => $returnData]);
	}
	public function fetchStudentRecords()
	{
		$facultyId = isset($_GET["id"]) ? $_GET["id"] : null;
		$subjectCode = isset($_GET["subject_code"]) ? $_GET["subject_code"] : null;

		$results = FacultyModel::find($facultyId, "faculty_id");

		if (!$results) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		//fetch students based on enrolled subject and faculty
		$fetchAssignedStudents = GradesModel::where([
			"subject_code" => $subjectCode,
			"faculty_id" => $facultyId
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
	public function fetchUnassignedStudents()
	{
		$facultyId = isset($_GET["id"]) ? $_GET["id"] : null;
		$subjectCode = isset($_GET["subject_code"]) ? $_GET["subject_code"] : null;

		$results = FacultyModel::find($facultyId, "faculty_id");

		if (!$results) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		$students = StudentModel::all();

		$fetchAssignedStudents = GradesModel::where([
			"subject_code" => $subjectCode,
			"faculty_id" => $facultyId
		], true);

		// Initialize $returnData as an empty array
		$returnData = [];

		if ($fetchAssignedStudents) {
			// Extract student IDs from the result
			$assignedStudentsIds = array_column($fetchAssignedStudents, 'student_id');

			// Filter unassigned students
			$unassignedStudents = array_filter($students, function ($student) use ($assignedStudentsIds) {
				return !in_array($student['student_id'], $assignedStudentsIds);
			});

			foreach ($unassignedStudents as $unassignedStudent) {
				// Format fullname
				$middlename = substr($unassignedStudent["middle_name"], 0, 1) . ".";
				$fullname = $unassignedStudent["first_name"] . " $middlename " . $unassignedStudent["last_name"];

				$returnData[] = [
					"student_id" => $unassignedStudent["student_id"],
					"fullname" => $fullname
				];
			}
		} else {
			// If no assigned students, loop through all students
			foreach ($students as $student) {
				// Format fullname
				$middlename = substr($student["middle_name"], 0, 1) . ".";
				$fullname = $student["first_name"] . " $middlename " . $student["last_name"];

				$returnData[] = [
					"student_id" => $student["student_id"],
					"fullname" => $fullname
				];
			}
		}

		response(200, true, ["data" => $returnData]);
	}
	private function getFacultyFullname($facultyUserId)
	{
		$faculty = UserModel::find($facultyUserId, "user_id");
		$middlename = substr($faculty["middle_name"], 0, 1) . ".";
		return $faculty["first_name"] . " $middlename " . $faculty["last_name"];
	}
	public function unassignFaculty()
	{

		$facultyId = isset($_GET["id"]) ? $_GET["id"] : null;
		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		$faculty = FacultyModel::find($facultyId, "faculty_id");

		if (!$faculty) {
			response(404, false, ["message" => "Faculty is not found!"]);
			exit;
		}

		$subject = SubjectModel::find($code, "code");
		if (!$subject) {
			response(404, false, ["message" => "Subject is not found!"]);
			exit;
		}
		// fetch students based on enrolled subject and faculty
		$fetchAssignedStudents = GradesModel::where([
			"subject_code" => $code,
			"faculty_id" => $facultyId
		], false, "AND");

		if ($fetchAssignedStudents) {
			response(200, true, ["message" => "Cannot unassign faculty, students are enrolled to the subject!"]);
			exit;
		}

		$unassign = FacultySubjectsModel::unassign($facultyId, $code);

		if (!$unassign) {
			response(400, false, ["message" => "Failed to unassign faculty"]);
			exit;
		}
		response(200, true, ["message" => "Successfully unassigned faculty!"]);
	}
	public function unenrollStudent()
	{
		$studentId = isset($_GET["id"]) ? $_GET["id"] : null;
		$code = isset($_GET["code"]) ? $_GET["code"] : null;

		$student = StudentModel::find($studentId, "student_id");

		if (!$student) {
			response(404, false, ["message" => "Student is not found!"]);
			exit;
		}

		$subject = SubjectModel::find($code, "code");
		if (!$subject) {
			response(404, false, ["message" => "Subject is not found!"]);
			exit;
		}

		$unenroll = GradesModel::unenroll($studentId, $code);

		if (!$unenroll) {
			response(400, false, ["message" => "Failed to unenroll student!"]);
			exit;
		}

		response(200, true, ["message" => "Student successfuly unenrolled!"]);
	}
}
new Grades();

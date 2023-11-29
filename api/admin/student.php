<?php

namespace api\admin;

use api\Controller;
use middleware\AuthMiddleware;
use model\CourseModel;
use model\InstituteModel;
use model\StudentModel;
use model\UserModel;

require_once(__DIR__ . "/../../model/CourseModel.php");
require_once(__DIR__ . "/../../model/InstituteModel.php");
require_once(__DIR__ . "/../../model/UserModel.php");
require_once(__DIR__ . "/../../model/StudentModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class Student extends Controller
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
		$firstname = $data->firstname;
		$lastname = $data->lastname;
		$middlename = $data->middlename;
		$birthday = $data->birthday;
		$gender = $data->gender;
		$street = $data->street;
		$barangay = $data->barangay;
		$municipality = $data->municipality;
		$province = $data->province;
		$zipcode = $data->zipcode;
		$email = $data->email;
		$contact = $data->contact;
		$course = $data->course;
		$institute = $data->institute;
		$guardian_name = $data->guardian_name;
		$guardian_contact = $data->guardian_contact;
		$guardian_address = $data->guardian_address;

		//verify if email is already taken
		$fetchEmail = UserModel::find($email, "email");
		if ($fetchEmail) {
			response(409, false, ["message" => "Email already taken"]);
			exit;
		}

		//generate user id
		$fetchAll = StudentModel::all();

		if (!$fetchAll) {
			//initial value
			$recordCount = 0;
		} else {
			$recordCount = count($fetchAll);
		}

		$student_id = Controller::generateIdNum($recordCount); //generated id

		//set student default password
		$password = password_hash($student_id, PASSWORD_DEFAULT);

		if (!CourseModel::find($course, "slug")) {
			response(400, false, ["message" => "Course does not exists"]);
			exit;
		}

		if (!InstituteModel::find($institute, "slug")) {
			response(400, false, ["message" => "Insitute does not exists"]);
			exit;
		}

		$result = StudentModel::create($student_id, $firstname, $middlename, $lastname, $birthday, $gender, $contact, $email, $password, Controller::STUDENT_ROLE, $street, $barangay, $municipality, $province, $zipcode, $institute, $course, $guardian_name, $guardian_contact, $guardian_address);

		if (!$result) {
			response(400, false, ["message" => "Registration failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Registered successfully!"]);
		}
	}
	public function show()
	{
		$studentId = isset($_GET["id"]) ? $_GET["id"] : null;

		$results = StudentModel::find($studentId, "student_id");

		if (!$results) {
			response(404, false, ["message" => "Student not found!"]);
			exit;
		}

		response(200, true, $results);
	}
	public function search()
	{
		$query = isset($_GET["query"]) ? $_GET["query"] : null;

		$results = StudentModel::search($query);

		if (!$results) {
			response(404, false, ["message" => "Student not found!"]);
			exit;
		}

		response(200, true, ["data" => $results]);
	}
	public function all()
	{
		$results = StudentModel::all();
		if (!$results) {
			response(200, false, ["message" => "No registered students currently"]);
			exit;
		}

		$numRows = count($results);

		response(200, true, ["row_count" => $numRows, "data" => $results]);
	}
	public function update()
	{
		$data = json_decode(file_get_contents("php://input"));

		$student_id = isset($_GET["id"]) ? $_GET["id"] : null;

		Controller::verifyJsonData($data);

		//set json data from request body
		$firstname = $data->firstname;
		$lastname = $data->lastname;
		$middlename = $data->middlename;
		$birthday = $data->birthday;
		$gender = $data->gender;
		$street = $data->street;
		$barangay = $data->barangay;
		$municipality = $data->municipality;
		$province = $data->province;
		$zipcode = $data->zipcode;
		$email = $data->email;
		$contact = $data->contact;
		$course = $data->course;
		$institute = $data->institute;
		$guardian_name = $data->guardian_name;
		$guardian_contact = $data->guardian_contact;
		$guardian_address = $data->guardian_address;

		$student = StudentModel::find($student_id, "student_id");

		if (!$student) {
			response(404, false, ["message" => "Student not found!"]);
			exit;
		}

		if (!CourseModel::find($course, "slug")) {
			response(400, false, ["message" => "Course does not exists"]);
			exit;
		}

		if (!InstituteModel::find($institute, "slug")) {
			response(400, false, ["message" => "Insitute does not exists"]);
			exit;
		}

		$registerStudent = StudentModel::update($student['user_id'], $student_id, $firstname, $middlename, $lastname, $birthday, $gender, $contact, $email, $street, $barangay, $municipality, $province, $zipcode, $institute, $course, $guardian_name, $guardian_contact, $guardian_address);

		if (!$registerStudent) {
			response(400, false, ["message" => "Update failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Updated successfully!"]);
		}
	}
	public function delete()
	{
		$studentId = isset($_GET["id"]) ? $_GET["id"] : null;

		$results = StudentModel::find($studentId, "student_id");

		if (!$results) {
			response(404, false, ["message" => "Student not found!"]);
			exit;
		}

		if (UserModel::delete($results["user_id"], "user_id")) {
			response(200, true, ["message" => "Delete successful"]);
		} else {
			response(400, false, ["message" => "Delete Failed!"]);
		}
	}
}
new Student();

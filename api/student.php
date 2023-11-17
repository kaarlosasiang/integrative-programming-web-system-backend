<?php

namespace api;

use model\StudentModel;

require_once(__DIR__ . "/../model/StudentModel.php");
require_once(__DIR__ . "/Controller.php");

class Student extends Controller
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
		$contact = $data->contact;
		$course = $data->course;
		$institute = $data->institute;
		$guardian_name = $data->guardian_name;
		$guardian_contact = $data->guardian_contact;
		$guardian_address = $data->guardian_address;



		$result = StudentModel::all();

		if (!$result) {
			//initial value
			$recordCount = 0;
		} else {
			// generate user id
			$recordCount = count(StudentModel::all());
		}

		// dd($recordCount);
		$student_id = Controller::generateIdNum($recordCount);

		$result = StudentModel::create($student_id, $firstname, $lastname, $middlename, $birthday, $gender, $street, $barangay, $municipality, $province, $zipcode, $contact, $institute, $course, $guardian_name, $guardian_contact, $guardian_address);

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

		foreach ($results as $result) {

			$returnData[] = [
				"student_id" => $result["student_id"],
				"firstname" => $result["first_name"],
				"lastname" => $result["last_name"],
				"middlename" => $result["middle_name"],
				"birthday" => $result["birthday"],
				"gender" => $result["gender"],
				"street" => $result["purok"],
				"barangay" => $result["barangay"],
				"municipality" => $result["municipality"],
				"province" => $result["province"],
				"zipcode" => $result["zipcode"],
				"contact" => $result["contact_number"],
				"institute" => $result["institute"],
				"course" => $result["course"],
				"guardian_name" => $result["guardian_name"],
				"guardian_contact" => $result["guardian_contact"],
				"guardian_address" => $result["guardian_address"],
				"registered_at" => $result["registered_at"],
				"updated_at" => $result["updated_at"]
			];
		}
		response(200, true, ["row_count" => $numRows, "data" => $returnData]);
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
		$contact = $data->contact;
		$course = $data->course;
		$institute = $data->institute;
		$guardian_name = $data->guardian_name;
		$guardian_contact = $data->guardian_contact;
		$guardian_address = $data->guardian_address;



		if (!StudentModel::find($student_id, "student_id")) {
			response(404, false, ["message" => "Student not found!"]);
			exit;
		}

		$result = StudentModel::update($student_id, $firstname, $lastname, $middlename, $birthday, $gender, $street, $barangay, $municipality, $province, $zipcode, $contact, $institute, $course, $guardian_name, $guardian_contact, $guardian_address);

		if (!$result) {
			response(400, false, ["message" => "Update failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Update successfull!"]);
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

		if (StudentModel::delete($studentId, "student_id")) {
			response(200, true, ["message" => "Delete successful"]);
		} else {
			response(400, false, ["message" => "Delete Failed!"]);
		}
	}
}
new Student();

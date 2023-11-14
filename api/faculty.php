<?php

namespace api;

use model\FacultyModel;

require_once(__DIR__ . "/../model/FacultyModel.php");
require_once(__DIR__ . "/Controller.php");

class Faculty extends Controller
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

		//set json data from request body
		$firstname = $data->firstname;
		$lastname = $data->lastname;
		$middlename = $data->middlename;
		$birthday = $data->birthday;
		$gender = $data->gender;
		$contact = $data->contact;
		$course = $data->course;
		$institute = $data->institute;

		Controller::verifyJsonData($data);

		$result = FacultyModel::create($firstname, $lastname, $middlename, $birthday, $gender, $contact, $institute, $course);

		if (!$result) {
			response(400, false, ["message" => "Registration failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Registered successfully!"]);
		}
	}
	public function show()
	{
		$id = $_GET["id"] ? $_GET["id"] : null;

		$results = FacultyModel::find($id, "id");

		if (!$results) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		response(200, true, $results);
	}
	public function search()
	{
		$query = $_GET["query"] ? $_GET["query"] : null;

		$results = FacultyModel::search($query);

		if (!$results) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		response(200, true, ["data" => $results]);
	}
	public function all()
	{
		$results = FacultyModel::all();
		if (!$results) {
			response(200, false, ["message" => "No registered faculty currently"]);
			exit;
		}

		$numRows = count($results);

		foreach ($results as $result) {

			$returnData[] = [
				"row_count" => $numRows,
				"id" => $result["id"],
				"firstname" => $result["first_name"],
				"lastname" => $result["last_name"],
				"middlename" => $result["middle_name"],
				"birthday" => $result["birthday"],
				"gender" => $result["gender"],
				"contact" => $result["contact_number"],
				"institute" => $result["institute"],
				"course" => $result["course"],
				"registered_at" => $result["registered_at"],
				"updated_at" => $result["updated_at"]
			];
		}
		response(200, true, ["data" => $returnData]);
	}
	public function update()
	{
		$data = json_decode(file_get_contents("php://input"));

		$id = $_GET["id"] ? $_GET["id"] : null;

		//set json data from request body
		$firstname = $data->firstname;
		$lastname = $data->lastname;
		$middlename = $data->middlename;
		$birthday = $data->birthday;
		$gender = $data->gender;
		$contact = $data->contact;
		$course = $data->course;
		$institute = $data->institute;

		Controller::verifyJsonData($data);

		if (!FacultyModel::find($id, "id")) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		$result = FacultyModel::update($id, $firstname, $lastname, $middlename, $birthday, $gender, $contact, $institute, $course);

		if (!$result) {
			response(400, false, ["message" => "Update failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Update successfull!"]);
		}
	}
	public function delete()
	{
		$id = $_GET["id"] ? $_GET["id"] : null;

		$results = FacultyModel::find($id, "id");

		if (!$results) {
			response(404, false, ["message" => "Faculty not found!"]);
			exit;
		}

		if (FacultyModel::delete($id, "id")) {
			response(200, true, ["message" => "Delete successful"]);
		} else {
			response(400, false, ["message" => "Delete Failed!"]);
		}
	}
}
new Faculty();

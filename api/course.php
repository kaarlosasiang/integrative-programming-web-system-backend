<?php

namespace api;

use model\CourseModel;

require_once(__DIR__ . "/../model/CourseModel.php");
require_once(__DIR__ . "/Controller.php");

class Course extends Controller
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
		$title = $data->title;
		$slug = $data->slug;
		$description = $data->description;

		Controller::verifyJsonData($data);

		$result = CourseModel::create($title, $slug, $description);

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

		$results = CourseModel::find($id, "id");

		if (!$results) {
			response(404, false, ["message" => "Course not found!"]);
			exit;
		}

		response(200, true, $results);
	}
	public function search()
	{
		$query = $_GET["query"] ? $_GET["query"] : null;

		$results = CourseModel::search($query);

		if (!$results) {
			response(404, false, ["message" => "Course not found!"]);
			exit;
		}

		response(200, true, ["data" => $results]);
	}
	public function all()
	{
		$results = CourseModel::all();

		if (!$results) {
			response(200, false, ["message" => "No registered course currently"]);
			exit;
		}

		$numRows = count($results);

		foreach ($results as $result) {

			$returnData[] = [
				"row_count" => $numRows,
				"id" => $result["id"],
				"title" => $result["title"],
				"slug" => $result["slug"],
				"description" => $result["description"]
			];
		}
		response(200, true, ["data" => $returnData]);
	}
	public function update()
	{
		$data = json_decode(file_get_contents("php://input"));

		$id = $_GET["id"] ? $_GET["id"] : null;

		//set json data from request body
		$title = $data->title;
		$slug = $data->slug;
		$description = $data->description;

		Controller::verifyJsonData($data);

		if (!CourseModel::find($id, "id")) {
			response(404, false, ["message" => "Course not found!"]);
			exit;
		}

		$result = CourseModel::update($id, $title, $slug, $description);

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

		$results = CourseModel::find($id, "id");

		if (!$results) {
			response(404, false, ["message" => "Course not found!"]);
			exit;
		}

		if (CourseModel::delete($id, "id")) {
			response(200, true, ["message" => "Delete successful"]);
		} else {
			response(400, false, ["message" => "Delete Failed!"]);
		}
	}
}
new Course();

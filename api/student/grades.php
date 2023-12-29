<?php

namespace api\admin;

use api\Controller;
use middleware\AuthMiddleware;
use model\GradesModel;

require_once(__DIR__ . "/../../model/CourseModel.php");
require_once(__DIR__ . "/../../model/InstituteModel.php");
require_once(__DIR__ . "/../../model/GradesModel.php");
require_once(__DIR__ . "/../../model/StudentModel.php");
require_once(__DIR__ . "/../../middleware/AuthMiddleware.php");
require_once(__DIR__ . "/../Controller.php");

class Grades extends Controller
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
					if (array_key_exists("id", $_GET) || !empty($_GET["id"])) {
						$this->show();
					}
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
		$studentId = isset($_GET["id"]) ? $_GET["id"] : null;

		$results = GradesModel::find($studentId, "student_id", true);

		if (!$results) {
			response(404, false, ["message" => "No subject enrolled"]);
			exit;
		}

		response(200, true, ["data" => $results]);
	}
}
new Grades();

<?php

namespace api;

use model\UserModel;

require_once(__DIR__ . "/../../model/UserModel.php");
require_once(__DIR__ . "/../Controller.php");

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
		$email = $data->email;
		$password = $data->password;


		Controller::verifyJsonData($data);

		$emailTaken = UserModel::find($email, "email");
		if ($emailTaken) {
			response(409, false, ["message" => "Email already taken!"]);
			exit;
		}

		$password = password_hash($password, PASSWORD_DEFAULT);

		$result = UserModel::create($firstname, $lastname, $email, $password);

		if (!$result) {
			response(400, false, ["message" => "Registration failed!"]);
			exit;
		} else {
			response(201, true, ["message" => "Registered successfully!"]);
		}
	}
}
new Student();

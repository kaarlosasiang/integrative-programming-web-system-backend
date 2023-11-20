<?php

namespace api;

use middleware\JwtHandler;
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
					$this->login();
					break;
				}
			default: {
					response(400, false, ["message" => "Request method: {$requestMethod} not allowed!"]);
					break;
				}
		}
	}
	public function login()
	{
		$data = json_decode(file_get_contents("php://input"));
		$email = $data->email;
		$password = $data->password;


		Controller::verifyJsonData($data);

		$result = UserModel::find($email, "email");

		if (!$result || !password_verify($password, $result["password"])) {
			response(409, false, ["message" => "Invalid Credentials!"]);
			exit;
		}

		$jwtToken = new JwtHandler();
		$token = $jwtToken->jwtEncodeData("itp130-api", ["user_id" => $result["user_id"]]);

		$responseData = [
			"message" => "Login successful",
			"user_id" => $result["user_id"],
			"role" => $result["role"],
			"token" => $token
		];

		response(200, true, $responseData);
	}
}
new Student();

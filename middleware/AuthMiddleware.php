<?php

namespace middleware;

use model\UserModel;
use PDOException;

require_once(__DIR__ . "/JwtHandler.php");
require_once(__DIR__ . "/../model/UserModel.php");

class AuthMiddleware extends JwtHandler
{
	public $id;
	public $authenticated = false;

	public function __construct($id, $authenticated)
	{
		parent::__construct();
		$this->id = $id;
		$this->authenticated = $authenticated;
	}

	public static function authenticate()
	{
		$headers = getallheaders();

		if (!array_key_exists("Authorization", $headers) || !preg_match('/Bearer\s(\S+)/', $headers["Authorization"], $matches)) {
			response(403, false, ["message" => "Token not found!"]);
			exit;
		}

		$jwt = new JwtHandler();

		$data = $jwt->jwtDecodeData($matches[1]);

		//verifies token syntax errors
		if (!isset($data["data"]->user_id)) {
			response(403, false, ["message" => $data["message"]]);
			exit;
		}
		//verifies user id validity
		if (isset($data["data"]->user_id) && !UserModel::find($data["data"]->user_id, "user_id")) {
			response(403, false, ["message" =>  "Invalid token!"]);
			exit;
		}
		//authentication successfull
		if (isset($data["data"]->user_id) && UserModel::find($data["data"]->user_id, "user_id")) {
			$rawId = $data["data"]->user_id;
			return $rawId;
		}
	}
}

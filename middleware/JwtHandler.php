<?php

namespace middleware;

require_once(__DIR__ . "/../vendor/autoload.php");

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler
{
	private $jwt_secret;
	private $token;
	private $issuedAt;
	private $expire;
	private $jwt;

	public function __construct()
	{
		$this->jwt_secret = "itp130_api";

		//default timezone
		date_default_timezone_set('Asia/Manila');
		$this->issuedAt = time();

		//set token validity
		$this->expire = $this->issuedAt + 86400;
	}

	public function jwtEncodeData($iss, $data)
	{

		$this->token = [
			"iss" => $iss,
			"aud" => $iss,
			//set issued time for the token
			"iat" => $this->issuedAt,
			//add expiration
			"exp" => $this->expire,
			//payload 
			"data" => $data
		];

		$this->jwt = JWT::encode($this->token, $this->jwt_secret, 'HS256');
		return $this->jwt;
	}
	public function jwtDecodeData($jwt_token)
	{
		try {
			$decode = JWT::decode($jwt_token, new Key($this->jwt_secret, 'HS256'));
			return [
				"data" => $decode->data
			];
		} catch (Exception $e) {
			return [
				"message" => $e->getMessage()
			];
		}
	}
}

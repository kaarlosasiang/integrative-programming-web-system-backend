<?php

namespace api;

use model\SchoolYearModel;
use model\UserModel;
use model\StudentModel;

require_once(__DIR__ . "/../util/header.php");
require_once(__DIR__ . "/../util/util.php");
require_once(__DIR__ . "/../model/SchoolYearModel.php");

class Controller
{
	public const STUDENT_ROLE = "2";
	public const FACULTY_ROLE = "1";
	public const ADMIN_ROLE = "0";

	public static function verifyJsonData($data)
	{
		if (!array_key_exists("CONTENT_TYPE", $_SERVER) || $_SERVER["CONTENT_TYPE"] !== "application/json") {
			response(400, false, ["message" => "Content type header not set to JSON"]);
			exit;
		}
		if (!$data) {
			response(400, false, ["message" => "Request body is not valid JSON"]);
			exit;
		}
	}
	/**	
	 * @param string $userId - verify the current user
	 * @param string $requiredRole - verify the required role to access the page
	 * @param bool $allowAccess - grant access to the required role
	 */
	public function verifyRole($userId, $requiredRole, $allowAccess = true)
	{
		$role = UserModel::find($userId, "user_id");

		if ($role["role"] !== $requiredRole && $allowAccess) {
			response(403, false, ["message" => "Access denied for user role: {$role['role']}"]);
			exit;
		} else if ($role["role"] === $requiredRole && !$allowAccess) {
			response(403, false, ["message" => "Access denied for user role: {$role['role']}"]);
			exit;
		}
	}
	public static function generateIdNum($recordCount)
	{
		// Get the current year
		$currentYear = SchoolYearModel::find("1", "status")["year"];

		if ($recordCount > 0) {
			$recordCount = ++$recordCount; // Increment by 1 for the next record
		} else {
			$recordCount = 1; // If no records, start from 1
		}

		// Generate a 4-digit padded number based on the record count
		$incrementedNumber = sprintf("%04d", $recordCount);

		// Concatenate the current year and incremented number with a hyphen
		$idNumber = $currentYear . '-' . $incrementedNumber;

		if (StudentModel::find($idNumber, "student_id")) {
			$recordCount = ++$recordCount;

			$incrementedNumber = sprintf("%04d", $recordCount);

			$idNumber = $currentYear . '-' . $incrementedNumber;
			return $idNumber;
		}
		return $idNumber;
	}
}

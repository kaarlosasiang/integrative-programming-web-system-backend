<?php

namespace api;

use model\StudentModel;

require_once(__DIR__ . "/../util/header.php");
require_once(__DIR__ . "/../util/util.php");

class Controller
{


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
	public static function generateIdNum($recordCount)
	{
		// Get the current year
		$currentYear = date("Y");

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

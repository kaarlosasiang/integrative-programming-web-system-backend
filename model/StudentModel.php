<?php

namespace model;

use database\Database;
use PDOException;

require_once(__DIR__ . "/../database/Database.php");

class StudentModel
{
	private const TABLE = "students";

	/**
	 * Perform insert operation to the database
	 * @return true if success
	 */
	public static function create(
		$userid,
		$studentId,
		$purok,
		$barangay,
		$municipality,
		$province,
		$zipCode,
		$institute,
		$course,
		$guardianName,
		$guardianContact,
		$guardianAddress

	) {
		try {
			$query = "INSERT INTO " . self::TABLE . " SET user_id = :userid, student_id = :studentId, purok = :purok, barangay = :barangay, municipality = :municipality, province = :province, zipcode = :zipCode, institute = :institute, course = :course, guardian_name = :guardianName, guardian_contact = :guardianContact, guardian_address = :guardianAddress";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":userid", $userid);
			$stmt->bindParam(":studentId", $studentId);
			$stmt->bindParam(":purok", $purok);
			$stmt->bindParam(":barangay", $barangay);
			$stmt->bindParam(":municipality", $municipality);
			$stmt->bindParam(":province", $province);
			$stmt->bindParam(":zipCode", $zipCode);
			$stmt->bindParam(":institute", $institute);
			$stmt->bindParam(":course", $course);
			$stmt->bindParam(":guardianName", $guardianName);
			$stmt->bindParam(":guardianContact", $guardianContact);
			$stmt->bindParam(":guardianAddress", $guardianAddress);

			$result = $stmt->execute() ? true : false;
			return $result;
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
	}
	/**
	 * Perform fetch operation strictly  based on the condition
	 */
	public static function find($column, $condition, $fetchAll = false)
	{
		try {
			//query statement
			$query = "SELECT * FROM " . self::TABLE . " WHERE $condition = :$condition";

			//prepared statement
			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":$condition", $column);
			$stmt->execute();
			//verifies if there's a returned value
			if ($stmt->rowCount() == 0) {
				return null;
				exit;
			}
			if ($fetchAll === true) {
				$result = $stmt->fetchAll();
			} else {
				$result = $stmt->fetch();
			}
			return $result;
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
	}
	/**
	 * Perform fetch operation based on the condition
	 * @return null if condition is not found
	 * @return array result
	 */
	public static function search($column)
	{
		try {
			//query statement
			$query = "SELECT * FROM " . self::TABLE . " WHERE first_name LIKE :firstName OR middle_name LIKE :middleName OR last_name LIKE :lastName";

			//prepared statement
			$stmt = Database::connect()->prepare($query);

			$searchPattern = "%" . $column . "%";
			$stmt->bindParam(":firstName", $searchPattern);
			$stmt->bindParam(":middleName", $searchPattern);
			$stmt->bindParam(":lastName", $searchPattern);

			$stmt->execute();
			//verifies if there's a returned value
			if ($stmt->rowCount() == 0) {
				return null;
				exit;
			}
			//fetch and return result
			$result = $stmt->fetchAll();
			return $result;
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
	}
	/**
	 * Fetch all data from resource
	 *
	 */
	public static function all()
	{
		try {
			//query statement
			$query = "SELECT * FROM " . self::TABLE;
			//prepared statement
			$stmt = Database::connect()->prepare($query);

			$stmt->execute();
			//verifies if there's a returned value
			if ($stmt->rowCount() == 0) {
				return null;
				exit;
			}
			//fetch and return result
			$result = $stmt->fetchAll();
			return $result;
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
	}
	/**
	 * Update data set based on the condition
	 * @return bool true if successul
	 */
	public static function update(
		$studentId,
		$firstname,
		$lastname,
		$middlename,
		$birthday,
		$gender,
		$purok,
		$barangay,
		$municipality,
		$province,
		$zipCode,
		$contactNumber,
		$institute,
		$course,
		$guardianName,
		$guardianContact,
		$guardianAddress
	) {
		try {
			$query = "UPDATE " . self::TABLE . " SET first_name = :firstname, last_name = :lastname, middle_name = :middlename, birthday = :birthday, gender = :gender, purok = :purok, barangay = :barangay, municipality = :municipality, province = :province, zipcode = :zipCode, contact_number = :contactNumber, institute = :institute, course = :course, guardian_name = :guardianName, guardian_contact = :guardianContact, guardian_address = :guardianAddress  WHERE student_id = :studentId";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":studentId", $studentId);
			$stmt->bindParam(":firstname", $firstname);
			$stmt->bindParam(":lastname", $lastname);
			$stmt->bindParam(":middlename", $middlename);
			$stmt->bindParam(":birthday", $birthday);
			$stmt->bindParam(":gender", $gender);
			$stmt->bindParam(":purok", $purok);
			$stmt->bindParam(":barangay", $barangay);
			$stmt->bindParam(":municipality", $municipality);
			$stmt->bindParam(":province", $province);
			$stmt->bindParam(":zipCode", $zipCode);
			$stmt->bindParam(":contactNumber", $contactNumber);
			$stmt->bindParam(":institute", $institute);
			$stmt->bindParam(":course", $course);
			$stmt->bindParam(":guardianName", $guardianName);
			$stmt->bindParam(":guardianContact", $guardianContact);
			$stmt->bindParam(":guardianAddress", $guardianAddress);

			$result = $stmt->execute() ? true : false;
			return $result;
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
	}
	/**
	 * Delete data on the database
	 * @return bool true if successful
	 */
	public static function delete($column, $condition)
	{
		try {
			//query statement
			$query = "DELETE FROM " . self::TABLE . " WHERE $condition = :$condition";
			//prepared statement
			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":$condition", $column);


			$result = $stmt->execute() ? true : false;

			return $result;
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
	}
	public static function studentCountByCourse($courses)
	{
		try {
			foreach ($courses as $course) {

				$query = "SELECT * FROM students WHERE course =  :course";

				$stmt = Database::connect()->prepare($query);

				$stmt->bindParam(":course", $course["slug"]);
				$stmt->execute();

				$rowCount = $stmt->rowCount();

				$returnData[$course["slug"]] = $rowCount;
			}
			return $returnData;
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
	}
}

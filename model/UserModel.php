<?php

namespace model;

use database\Database;
use PDOException;

require_once(__DIR__ . "/../database/Database.php");
require_once(__DIR__ . "/../middleware/JwtHandler.php");

class UserModel
{
	private const TABLE = "users";

	/**
	 * Perform insert operation to the database
	 * @return true if success
	 */
	public static function create(
		$firstname,
		$middlename,
		$lastname,
		$birthday,
		$gender,
		$contactnumber,
		$email,
		$password,
		$role
	) {
		try {
			$query = "INSERT INTO " . self::TABLE . " SET first_name = :firstname, middle_name = :middlename, last_name = :lastname, birthday = :birthday, gender = :gender, email = :email, contact_number = :contactnumber, password = :password, role = :role";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":firstname", $firstname);
			$stmt->bindParam(":middlename", $middlename);
			$stmt->bindParam(":lastname", $lastname);
			$stmt->bindParam(":birthday", $birthday);
			$stmt->bindParam(":gender", $gender);
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":contactnumber", $contactnumber);
			$stmt->bindParam(":password", $password);
			$stmt->bindParam(":role", $role);

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
	 * Perform insert operation to the database
	 * @return true if success
	 */
	public static function update(
		$userId,
		$firstname,
		$middlename,
		$lastname,
		$birthday,
		$gender,
		$contactnumber,
		$email
	) {
		try {
			$query = "UPDATE " . self::TABLE . " SET first_name = :firstname, middle_name = :middlename, last_name = :lastname, birthday = :birthday, gender = :gender, email = :email, contact_number = :contactnumber WHERE user_id = :userId";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":userId", $userId);
			$stmt->bindParam(":firstname", $firstname);
			$stmt->bindParam(":middlename", $middlename);
			$stmt->bindParam(":lastname", $lastname);
			$stmt->bindParam(":birthday", $birthday);
			$stmt->bindParam(":gender", $gender);
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":contactnumber", $contactnumber);

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
}

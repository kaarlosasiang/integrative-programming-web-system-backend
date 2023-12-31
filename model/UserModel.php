<?php

namespace model;

use database\Database;
use PDOException;

require_once(__DIR__ . "/../database/Database.php");

class UserModel
{
	private const TABLE = "admin";

	/**
	 * Perform insert operation to the database
	 * @return true if success
	 */
	public static function create(
		$firstname,
		$lastname,
		$email,
		$password
	) {
		try {
			$query = "INSERT INTO " . self::TABLE . " SET first_name = :firstname, last_name = :lastname, email = :email, password = :password";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":firstname", $firstname);
			$stmt->bindParam(":lastname", $lastname);
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":password", $password);


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
}

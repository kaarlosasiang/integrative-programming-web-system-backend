<?php

namespace model;

use database\Database;
use PDOException;

require_once(__DIR__ . "/../database/Database.php");

class SubjectModel
{
	private const TABLE = "subjects";

	/**
	 * Perform insert operation to the database
	 * @return true if success
	 */
	public static function create(
		$code,
		$description,
		$unit,
		$type
	) {
		try {
			$query = "INSERT INTO " . self::TABLE . " SET code = :code, description = :description, unit = :unit, type = :type";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":code", $code);
			$stmt->bindParam(":description", $description);
			$stmt->bindParam(":unit", $unit);
			$stmt->bindParam(":type", $type);

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
	 * @return null if condition is not found
	 * @return array result
	 */
	public static function find($column, $condition)
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
			//fetch and return result
			$result = $stmt->fetch();
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
			$query = "SELECT * FROM " . self::TABLE . " WHERE code LIKE :code OR description LIKE :description";

			//prepared statement
			$stmt = Database::connect()->prepare($query);

			$searchPattern = "%" . $column . "%";

			$stmt->bindParam(":code", $searchPattern);
			$stmt->bindParam(":description", $searchPattern);

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
		$code,
		$description,
		$unit,
		$type
	) {
		try {
			$query = "UPDATE " . self::TABLE . " SET  description = :description, unit = :unit, type = :type WHERE code = :code";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":code", $code);
			$stmt->bindParam(":description", $description);
			$stmt->bindParam(":unit", $unit);
			$stmt->bindParam(":type", $type);

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

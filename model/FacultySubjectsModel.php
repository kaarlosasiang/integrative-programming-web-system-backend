<?php

namespace model;

use database\Database;
use PDOException;

require_once(__DIR__ . "/../database/Database.php");

class FacultySubjectsModel
{
	private const TABLE = "faculty_subjects";

	/**
	 * Perform insert operation to the database
	 * @return true if success
	 */
	public static function create(
		$subject_code,
		$faculty_id
	) {
		try {
			$query = "INSERT INTO " . self::TABLE . " SET subject_code = :subjectCode, faculty_id = :facultyId";

			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":subjectCode", $subject_code);
			$stmt->bindParam(":facultyId", $faculty_id);


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
			$query = "SELECT * FROM " . self::TABLE . " WHERE faculty_id LIKE :facultyId OR subject_code LIKE :subjectCode";

			//prepared statement
			$stmt = Database::connect()->prepare($query);

			$searchPattern = "%" . $column . "%";

			$stmt->bindParam(":facultyId", $searchPattern);
			$stmt->bindParam(":subjectCode", $searchPattern);

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
	// public static function update(
	// 	$subject_code,
	// 	$faculty_id
	// ) {
	// 	try {
	// 		$query = "UPDATE " . self::TABLE . " SET subject_code = :subjectCode, faculty_id = :facultyId";

	// 		$stmt = Database::connect()->prepare($query);

	// 		$stmt->bindParam(":subjectCode", $subject_code);
	// 		$stmt->bindParam(":facultyId", $faculty_id);


	// 		$result = $stmt->execute() ? true : false;
	// 		return $result;
	// 	} catch (PDOException $e) {
	// 		$response = [
	// 			"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
	// 		];
	// 		response(500, false, $response);
	// 		exit;
	// 	}
	// }
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

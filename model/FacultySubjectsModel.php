<?php

namespace model;

use Exception;
use PDOException;
use database\Database;

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
	public static function unassign($facultyId, $subjectcode)
	{
		try {
			//query statement
			$query = "DELETE FROM " . self::TABLE . " WHERE faculty_id = :facultyId AND subject_code = :subjectCode";
			//prepared statement
			$stmt = Database::connect()->prepare($query);

			$stmt->bindParam(":facultyId", $facultyId);
			$stmt->bindParam(":subjectCode", $subjectcode);

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
	public static function where(array $conditions, $fetchAll = false, string $logicalOperator = "AND")
	{
		try {
			$whereClause = self::buildWhereClause($conditions, $logicalOperator);

			$query = "SELECT * FROM " . self::TABLE . " {$whereClause}";
			$stmt = Database::connect()->prepare($query);

			foreach ($conditions as $key => $value) {
				// Check if the value is an array (for IN condition)
				if (is_array($value)) {
					// Check for "!=" in the array key for NOT IN condition
					if (strpos($key, '!=') !== false) {
						$key = trim(str_replace('!=', '', $key));
					}

					// Ensure parameter names have a consistent format with ":" prefix and numeric suffix
					foreach ($value as $index => $val) {
						$placeholder = ":{$key}_{$index}";
						$stmt->bindValue($placeholder, $val);
					}
				} else {
					// For other conditions, use the original parameter name with ":" prefix
					$placeholder = ":{$key}";
					$stmt->bindValue($placeholder, $value);
				}
			}

			$stmt->execute();

			if ($fetchAll === true) {
				$result = $stmt->fetchAll();
			} else {
				$result = $stmt->fetch();
			}

			return $result;
		} catch (Exception $e) {
			$responseMessage = "Error: {$e->getMessage()} on line {$e->getLine()}";
			response(500, false, ["message" => $responseMessage]);
			exit;
		}
	}
	// Build the WHERE clause for the query
	protected static function buildWhereClause(array $conditions, string $logicalOperator = 'AND')
	{
		if (empty($conditions)) {
			return ''; // Return an empty string if no conditions are provided
		}

		$validOperators = ['AND', 'OR'];
		$logicalOperator = strtoupper($logicalOperator);
		$logicalOperator = in_array($logicalOperator, $validOperators) ? $logicalOperator : 'AND';

		$whereClause = 'WHERE ';
		$conditionsArray = [];

		foreach ($conditions as $column => $value) {
			// If the value is an array, use IN operator
			if (is_array($value)) {
				$notIn = false;
				$comparisonOperator = 'IN';

				// Check for "!=" in the array key for NOT IN condition
				if (strpos($column, '!=') !== false) {
					$notIn = true;
					$comparisonOperator = 'NOT IN';
					$column = trim(str_replace('!=', '', $column));
				}

				$paramNames = [];
				foreach ($value as $index => $val) {
					$paramName = ":{$column}_{$index}";
					$paramNames[] = $paramName;
					$conditions[$paramName] = $val;
				}

				$conditionValues = implode(',', $paramNames);
				$conditionsArray[] = "$column $comparisonOperator ($conditionValues)";
				unset($conditions[$column]);

				if ($notIn) {
					$conditionsArray[count($conditionsArray) - 1] = "{$conditionsArray[count($conditionsArray) - 1]}";
				}

				unset($conditions[$column]);
			} else {
				$comparisonOperator = '=';
				$conditionValue = ":$column";

				// Check for comparison operators other than '='
				if (strpos($column, ' ') !== false) {

					[$column, $comparisonOperator] = explode(' ', $column, 2); //separates column string (column and operator) and set to a variable

					$conditionValue = ":$column";
				}

				$conditionsArray[] = "$column $comparisonOperator $conditionValue";
			}
		}

		$whereClause .= implode(" $logicalOperator ", $conditionsArray);

		return $whereClause;
	}
}

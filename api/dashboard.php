<?php

namespace api;

use model\CourseModel;
use model\FacultyModel;
use model\StudentModel;

require_once(__DIR__ . "/../model/CourseModel.php");
require_once(__DIR__ . "/../model/FacultyModel.php");
require_once(__DIR__ . "/../model/StudentModel.php");
require_once(__DIR__ . "/Controller.php");

class Dashboard extends Controller
{
	public function __construct()
	{
		$requestMethod = $_SERVER["REQUEST_METHOD"];

		switch ($requestMethod) {

			case "GET": {
					$this->dashBoardData();
					break;
				}

			default: {
					response(400, false, ["message" => "Request method: {$requestMethod} not allowed!"]);
					break;
				}
		}
	}
	public function dashBoardData()
	{
		$students = StudentModel::all();
		$faculties = FacultyModel::all();
		$courses = CourseModel::all();
		$fcdset = StudentModel::find("FCDSET", "institute", true);
		$fgbm = StudentModel::find("FGBM", "institute", true);
		$fnahs = StudentModel::find("FNAHS", "institute", true);
		$fted = StudentModel::find("FTED", "institute", true);
		$fals = StudentModel::find("FALS", "institute", true);

		$studentCount = $students == null ? 0 : count($students);
		$facultiesCount = $faculties == null ? 0 : count($faculties);
		$fcdsetCount = $fcdset == null ? 0 : count($fcdset);
		$fgbmCount = $fgbm == null ? 0 : count($fgbm);
		$fnahsCount = $fnahs == null ? 0 : count($fnahs);
		$ftedCount =  $fted == null ? 0 : count($fted);
		$falsCount = $fals == null ? 0 : count($fals);

		$studentCountByCourse = StudentModel::studentCountByCourse($courses);


		response(200, true, [
			"student_count" => $studentCount,
			"faculty_count" => $facultiesCount,
			"fcdset_count" => $fcdsetCount,
			"fgbm_count" => $fgbmCount,
			"fnahs_count" => $fnahsCount,
			"fted_count" => $ftedCount,
			"fals_count" => $falsCount,
			"student_count_by_course" => $studentCountByCourse
		]);
	}
}
new Dashboard();

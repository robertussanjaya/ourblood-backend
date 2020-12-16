<?php

	header("Content-type:application/json");

	require_once "../include/koneksi.php";

	$query = "SELECT * FROM `request` ORDER BY `timestamp` DESC";

	$result = mysqli_query($con, $query);

	$response = array();

	while ($row = mysqli_fetch_assoc($result)){

		$response[] = $row;

	}

	echo json_encode(array("error" => false, "data" => $response));
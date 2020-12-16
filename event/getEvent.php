<?php

	header("Content-type:application/json");

	require_once "../include/koneksi.php";

	$query = "SELECT * FROM `event` ORDER BY STR_TO_DATE(tanggal, '%d/%m/%Y') DESC, id_event DESC";

	$result = mysqli_query($con, $query);

	$response = array();

	while($row = mysqli_fetch_assoc($result)){

		$response[] = $row;

	}

	echo json_encode(array("error" => false, "hasil" => $response));

?>
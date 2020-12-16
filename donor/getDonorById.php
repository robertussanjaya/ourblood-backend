<?php

	header("Content-type:application/json");
	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		$id_user = $_POST['id_user'];

		$query = "SELECT * FROM `request` WHERE id_user = '$id_user' ORDER BY `timestamp` DESC";

		$result = mysqli_query($con, $query);

		if(mysqli_num_rows($result) > 0){

			while($row = mysqli_fetch_assoc($result)){

				$response[] = $row;

			}

			echo json_encode(array("error" => false, "data" => $response));

		} else {

			$response['error'] = true;
			$response['data'] = null;

			echo json_encode($response);
		}


	} else {

		$response['error'] = true;
		$response['data'] = null;

		echo json_encode($response);

	}


?>
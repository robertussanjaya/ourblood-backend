<?php

	header("Content-type:application/json");
	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		$id_user = $_POST['id_user'];

		$query = "SELECT * FROM `event` WHERE id_user = '$id_user'";

		$result = mysqli_query($con, $query);

		if(mysqli_num_rows($result) > 0){

			while($row = mysqli_fetch_assoc($result)){

				$response[] = $row;

			}

			echo json_encode(array("error" => false, "hasil" => $response));

		} else {

			$response['error'] = false;
			$response['hasil'] = null;

		echo json_encode($response);

		}


	} else {

		$response['error'] = true;
		$response['hasil'] = null;

		echo json_encode($response);

	}


?>
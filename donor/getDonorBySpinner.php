<?php

	header("Content-type:application/json");
	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		$goldar = $_POST['gol_dar'];
		$rhesus = $_POST['rhesus'];

		if (empty($goldar)){
			$query = "SELECT * FROM `request` WHERE rhesus = '$rhesus'
						ORDER BY `timestamp` DESC";
		}

		if (empty($rhesus)){
			$query = "SELECT * FROM `request` WHERE gol_dar = '$goldar'
						ORDER BY `timestamp` DESC";
		}

		if (empty($rhesus) && empty($goldar)){
			$query = "SELECT * FROM `request` ORDER BY `timestamp` DESC";
		}

		if (!empty($rhesus) && !empty($goldar)){
			$query = "SELECT * FROM `request` WHERE gol_dar = '$goldar' AND rhesus = '$rhesus'
						ORDER BY `timestamp` DESC";
		}

		$result = mysqli_query($con, $query);

		if(mysqli_num_rows($result) > 0){

			while($row = mysqli_fetch_assoc($result)){

				$response[] = $row;

			}

			echo json_encode(array("error" => false, "data" => $response));

		} else {

			$response['error'] = true;
			$response['data'] = "Data Tidak Ditemukan";

			echo json_encode($response);
		}


	} else {

		$response['error'] = true;
		$response['data'] = "Terjadi Kesalahan";

		echo json_encode($response);

	}


?>
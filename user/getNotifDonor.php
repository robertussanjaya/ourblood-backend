<?php

	header("Content-type:application/json");
	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$gol_dar = $_POST['gol_dar'];
		$rhesus = $_POST['rhesus'];

		if (empty($gol_dar) || empty($rhesus)){
			$response['error'] = true;
			$response['message'] = 'Data Kosong';
		}

		$query = "SELECT * FROM `request` WHERE `gol_dar` = '$gol_dar' AND `rhesus` = '$rhesus' ORDER BY `timestamp` DESC LIMIT 10";
		$result = mysqli_query($con, $query);

		if (mysqli_num_rows($result) > 0){

			while($row = mysqli_fetch_assoc($result)){
				
				$response[] = $row;
			}

			echo json_encode(array("error" => false, "data" => $response));

		} 
		else {

			$response['error'] = true;
			$response['message'] = 'Notifikasi Kosong..';
			echo json_encode($response);
		}

	}
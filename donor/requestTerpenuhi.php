<?php

	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$id_request = $_POST['id_request'];

		$sql = "DELETE FROM `request` WHERE id_request = $id_request";
		$result = mysqli_query($con, $sql);

		if($result){
			$response['error'] = false;
			$response['message'] = "Permintaan Pendonor Dihapus";
		}
		else {
			$response['error'] = true;
			$response['message'] = "Terjadi Kesalahan..";	
		}

	} else {
		$response['error'] = true;
		$response['message'] = "Terjadi Kesalahan..";
	}

	echo json_encode($response);
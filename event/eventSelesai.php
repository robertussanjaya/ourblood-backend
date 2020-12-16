<?php

	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$id_event = $_POST['id_event'];

		$sql = "DELETE FROM `event` WHERE id_event = $id_event";
		$result = mysqli_query($con, $sql);

		if($result){
			$response['error'] = false;
			$response['message'] = "Event Dihapus";
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
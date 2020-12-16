<?php

	header("Content-type:application/json");
	require_once "../include/koneksi.php";

	$response = array();

	if($_SERVER["REQUEST_METHOD"] == 'POST'){

		$id = $_POST['id_user'];

		if (empty($id)){

			$response['error'] = true;
			$response['message'] = "User ID tidak ada";
			echo json_encode($response);

		}

		$query = "SELECT * FROM `user` WHERE id_user = $id";
		$result = mysqli_query($con, $query);

		if (mysqli_num_rows($result) > 0 ){

			$row = mysqli_fetch_assoc($result);

			$response['error'] = false;
			$response['message'] = $row['username'];
			$response['picture'] = $row['foto'];
			echo json_encode($response);

		} else {

			$response['error'] = true;
			$response['user'] = "User tidak ditemukan";
			echo json_encode($response);

		}

	}

?>
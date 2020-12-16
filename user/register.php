<?php

require_once '../include/koneksi.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$no_telp = $_POST['no_telp'];
	$alamat = $_POST['alamat'];
	$gol_dar = $_POST['gol_dar'];
	$rhesus = $_POST['rhesus'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$token = $_POST['token'];
	$foto = 'default.jpg';

	do{

		if (empty($username) || empty($email) || empty($password) || empty($no_telp) || empty($alamat) 
			|| empty($gol_dar) || empty($rhesus) ){

			$response['error'] = true;
			$response['message'] = "Isi Data Secara Lengkap";

			echo json_encode($response, JSON_PRETTY_PRINT);
			break;

		}

		$validate = filter_var($email, FILTER_VALIDATE_EMAIL);

		if (!$validate){

			$response['error'] = true;
			$response['message'] = "Bentuk Email Tidak Valid";

			echo json_encode($response, JSON_PRETTY_PRINT);
			break;

		}

		$checkEmail = mysqli_query($con, "SELECT * FROM `user` WHERE email = '$email'");
		$checkNum = mysqli_query($con, "SELECT * FROM `user` WHERE no_telp = '$no_telp'");

		$resEmail = mysqli_num_rows($checkEmail);
		$resNum = mysqli_num_rows($checkNum);

		if ($resNum > 0) {

			$response['error'] = true;
			$response['message'] = "Nomor Telepon Sudah Terdaftar";

			echo json_encode($response, JSON_PRETTY_PRINT);
			break;

		}

		if ($resEmail > 0){

			$response['error'] = true;
			$response['message'] = "Email Sudah Terdaftar";

			echo json_encode($response, JSON_PRETTY_PRINT);
			break;

		}

		$query = "INSERT INTO `user` (username, email, password, no_telp, alamat, gol_darah, rhesus, foto, lat, lng, token) 
					VALUES ('$username', '$email', '$password', '$no_telp', '$alamat', '$gol_dar', '$rhesus', '$foto', '$lat', '$lng', '$token')";

		$result = mysqli_query($con, $query);

		if ($result) {
			
			$response['error'] = false;
			$response['message'] = "Berhasil Mendaftar! Silakan Login";

			echo json_encode($response, JSON_PRETTY_PRINT);
			break;

		} 

		if (!$result) {

			$response['error'] = true;
			$response['message'] = "Gagal Mendaftarkan Akun..";

			echo json_encode($response, JSON_PRETTY_PRINT);
			break;

		}

	} while (false);

} 


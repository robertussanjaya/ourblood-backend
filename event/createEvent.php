<?php

	require_once("../include/koneksi.php");

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$id_user = $_POST['id_user'];
		$nama_event = $_POST['nama_event'];
		$lokasi = $_POST['lokasi'];
		$tanggal = $_POST['tanggal'];
		$waktu = $_POST['waktu'];
		$waktu_selesai = $_POST['waktu_selesai'];
		$keterangan = $_POST['keterangan'];
		$foto = upload();
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];

		do{

			if ( empty($nama_event) | empty($lokasi) | empty($tanggal) | empty($waktu) | empty($waktu_selesai) | !$foto){

				$response['error'] = true;
				$response['message'] = "Isi Form Secara Lengkap!";
				break;

			}

			$query = "INSERT INTO `event` (id_user, nama_event, lokasi, tanggal, waktu, 
										waktu_selesai, keterangan, foto, lat, lng) 
							VALUES ('$id_user', '$nama_event', '$lokasi', '$tanggal', '$waktu', 
										'$waktu_selesai', '$keterangan', '$foto', '$lat', '$lng')";

			$result = mysqli_query($con, $query);

			if ($result) {
				
				$response['error'] = false;
				$response['message'] = "Sukses Menambahkan Event!";

			}

			if (!$result) {
				
				$response['error'] = true;
				$response['message'] = "Gagal Menambahkan Event..";

			}


		} while(false);

	} else {

		$response['error'] = true;
		$response['message'] = "Request Bukan POST";

	}

	echo json_encode($response, JSON_PRETTY_PRINT);

	function upload(){

		$part = "../upload/event/";
		$filename = "img".rand(9,9999).".jpg";

		if($_FILES['imageUpload']){

			$destinationFile = $part.$filename;
			if (move_uploaded_file($_FILES['imageUpload']['tmp_name'], $destinationFile)){

				return $filename;

			} else { return false; }

		} else { 

			echo"Pilih gambar dulu !";
			return false;

		 }

	}

?>
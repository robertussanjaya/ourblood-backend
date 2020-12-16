<?php

	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$id_event = $_POST['id_event'];
		$id_user = $_POST['id_user'];
		$nama_event = $_POST['nama_event'];
		$lokasi = $_POST['lokasi'];
		$tanggal = $_POST['tanggal'];
		$waktu = $_POST['waktu'];
		$selesai = $_POST['waktu_selesai'];
		$keterangan = $_POST['keterangan'];
		$foto = upload();
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];

		do {

			if (empty($nama_event) || empty($lokasi) || empty($tanggal) || empty($selesai) || empty($foto)){

				$response['error'] = true;
				$response['message'] = "Isi Secara Lengkap";
				break;

			}

			$sql = "UPDATE `event` SET id_user = '$id_user', nama_event = '$nama_event', 
						lokasi = '$lokasi', tanggal = '$tanggal', waktu = '$waktu', 
							waktu_selesai = '$selesai', keterangan = '$keterangan', foto = '$foto',
								lat = '$lat', lng = '$lng' WHERE id_event = '$id_event' ";
			$result = mysqli_query($con, $sql);

			if ($result){

				$response['error'] = false;
				$response['message'] = "Event Telah Diperbarui";
				break;

			} else if (!$result){

				$response['error'] = true;
				$response['message'] = "Terjadi Kesalahan..";
				break;

			}

		} while(false);

	} else {

		$response['error'] = true;
		$response['message'] = "Terjadi Kesalahan";

	}

	echo json_encode($response);

	function upload(){

		$part = "../upload/event/";
		$filename = "img".rand(9,9999).".jpg";

		if($_FILES['imageUpload']){

			$destinationFile = $part.$filename;
			if (move_uploaded_file($_FILES['imageUpload']['tmp_name'], $destinationFile)){

				return $filename;

			} else { return false; }

		} else { 

			// echo"Pilih gambar dulu !";
			return false;

		 }

	}
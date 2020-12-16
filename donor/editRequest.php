<?php

	require_once "../include/koneksi.php";

	$response = array();
	$key = 'key=FIREBASE_API_KEY';

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$id_request = $_POST['id_request'];
		$id_user = $_POST['id_user'];
		$nama_pasien = $_POST['nama_pasien'];
		$no_hp = $_POST['no_hp'];
		$lokasi = $_POST['lokasi'];
		$jumlah = $_POST['jumlah'];
		$gol_dar =$_POST['gol_dar'];
		$rhesus = $_POST['rhesus'];
		$foto = upload();
		$ket = $_POST['ket'];
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];

		do{

			if (empty($nama_pasien) || empty($no_hp) || empty($lokasi)  || empty($jumlah) || empty($gol_dar)
				|| empty($rhesus) || !$foto) {

				$response['error'] = true;
				$response['message'] = "Isi Secara Lengkap";
				break;

			}

			$sql = "UPDATE `request` SET id_user = $id_user, nama_pasien = '$nama_pasien', 
							no_hp = '$no_hp', lokasi = '$lokasi', jumlah = $jumlah, gol_dar = '$gol_dar', 
							rhesus = '$rhesus', foto = '$foto', keterangan = '$ket', lat = '$lat', lng = '$lng' WHERE id_request = $id_request";
			$result = mysqli_query($con, $sql);

			if($result){

				$cariPendonor = "SELECT * FROM `user` WHERE gol_darah = '$gol_dar' AND 
									rhesus = '$rhesus'";

  				$hasil = mysqli_query($con, $cariPendonor);

				$registration_id = '[';

				if($hasil && mysqli_num_rows($hasil) > 0){

				    while($row = mysqli_fetch_assoc($hasil)){

				      $registration_id .= '"'. $row['token'] .'"';
				      $registration_id .= ',';

				    }

				    $registration_id .= ']';

				    $body = 'Dibutuhkan Golongan Darah ' . $gol_dar . ' ' . $rhesus . 
						    	' Sebanyak ' . $jumlah . ' Pendonor';

					pushNotif($registration_id, $key, $body);

					$response['error'] = false;
					$response['message'] = "Permintaan Telah Diperbarui";

				  } else {

				  	break;

				  }

			} 

			if(!$result){

				$response['error'] = true;
				$response['message'] = "Terjadi Kesalahan";

			}

		} while(false);

	} else {

		$response['error'] = true;
		$response['message'] = "Terjadi Kesalahan";

	}

	echo json_encode($response);


	function upload(){

		$part = "../upload/donor/";
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

	function pushNotif($registration_id, $key, $body){

		  $curl = curl_init();

		  curl_setopt_array($curl, array(

		  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{
		  "registration_ids": ' . $registration_id . ',
		  "notification": {
		      "title": "Hai Sobat Donor",
		      "body": "'. $body .'"
		  }
		}',
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: " . $key,
		    "Content-Type: application/json",
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		// echo $response;

  }
<?php

	require_once("../include/koneksi.php");

	$response = array();

	$key = 'key=FIREBASE_API_KEY';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$id_user = $_POST['id_user'];
		$nama_pasien = $_POST['nama_pasien'];
		$no_hp = $_POST['no_hp'];
		$lokasi = $_POST['lokasi'];
		$jumlah = $_POST['jumlah'];
		$gol_dar =$_POST['gol_dar'];
		$rhesus = $_POST['rhesus'];
		$foto = upload();
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		$ket = $_POST['ket'];

		do {

			if (empty($nama_pasien) || empty($no_hp) || empty($lokasi)  || empty($jumlah) || empty($gol_dar)
				|| empty($rhesus) || !$foto) {

				$response['error'] = true;
				$response['message'] = "Isi Form Secara Lengkap";
				break;

			}

			$query = "INSERT INTO `request` (id_user, nama_pasien, no_hp, lokasi, 
												jumlah, gol_dar, rhesus, foto, keterangan, lat, lng)
							VALUES ($id_user,  '$nama_pasien', '$no_hp', '$lokasi', 
									$jumlah, '$gol_dar', '$rhesus', '$foto', '$ket', '$lat', '$lng')";

			$result = mysqli_query($con, $query);

			if ($result) {
				
				$response['error'] = false;
				$response['message'] = "Sukses Menambahkan Pencarian Pendonor";

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

				  } else {

				  	break;

				  }

				break;
				
			}

			if (!$result){

				$response['error'] = true;
				$response['message'] = "Gagal Menambahkan Request Donor";
				break;

			}


		} while(false);

	} else {

		$response['error'] = true;
		$response['message'] = "Request Bukan POST";

	}

	echo json_encode($response, JSON_PRETTY_PRINT);


	function upload(){

		$part = "../upload/donor/";
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
          "body": "'. $body .'",
          "click_action": "NOTIFIKASI_FCM"
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
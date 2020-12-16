<?php

	require_once "../include/koneksi.php";

	$response = array();

	if ($con){

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){

			$email = $_POST['email'];
			$password = $_POST['password'];
			$token = $_POST['token'];

			do {

				if (empty($email)) {
					
					$response['error'] = true;
					$response['message'] = "Email Kosong";
					break;

				}

				if (empty($password)) {
					
					$response['error'] = true;
					$response['message'] = "Password Kosong";
					break;

				}

				$checkEmail = mysqli_query($con, "SELECT * FROM `user` WHERE email = '$email'");

				if (mysqli_num_rows($checkEmail) > 0) {
					
					$checkPass = mysqli_query($con, "SELECT * FROM `user` WHERE email = '$email'");
					$row = mysqli_fetch_assoc($checkPass);

					if ($password == $row['password']) {

						$update = "UPDATE `user` SET token = '$token' WHERE email = '$email'";
						$hasil = mysqli_query($con, $update);

						if($hasil){

							$response = array("error" => false, "message" => "Login Berhasil..", "result" =>
										array(
											array("username" => $row['username'], 
													"email" => $row['email'],
													"id_user" => $row['id_user'],
													"gol_darah" => $row['gol_darah'],
													"rhesus" => $row['rhesus'],
													"foto" => $row['foto'],
													"lat" => $row['lat'],
													"lng" => $row['lng'],
													"telepon" => $row['no_telp'],
													"alamat" => $row['alamat'])
										));

						} else {

							$response['error'] = true;
							$response['message'] = "Token tidak terupdate";

						}
		

					} else {

						$response['error'] = true;
						$response['message'] = "Password Salah";

					}

				} else {

					$response['error'] = true;
					$response['message'] = "Email Tidak Terdaftar";

				}


			} while (false);

		}

	} else {

		$response['error'] = true;
		$response['message'] = "Koneksi Error";

	}

	echo json_encode($response, JSON_PRETTY_PRINT);
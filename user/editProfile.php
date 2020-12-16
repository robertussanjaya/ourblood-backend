<?php

	require_once "../include/koneksi.php";

	$response = array();

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$id_user = $_POST['id_user'];
		$username = $_POST['username'];
		$email = $_POST['email'];

		$password = $_POST['password'];
		$password_baru = $_POST['password_baru'];

		$no_telp = $_POST['no_telp'];
		$alamat = $_POST['alamat'];
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		$foto = upload();

		do {

			if (empty($username) || empty($email) || empty($no_telp) || empty($alamat) ) {

				$response['error'] = true;
				$response['message'] = "Isi Secara Lengkap";
				break;

			}

			if (!empty($username) || !empty($email) || !empty($no_telp) || !empty($alamat)){

				$validate = filter_var($email, FILTER_VALIDATE_EMAIL);

				if (!$validate){

					$response['error'] = true;
					$response['message'] = "Email Tidak Valid";
					break;

				}

				//Jika ubah password
				if (!empty($password) && !empty($password_baru)){

					$match = mysqli_query($con, "SELECT * FROM `user` WHERE id_user = '$id_user'");
					$row = mysqli_fetch_assoc($match);

					if ($password == $row['password']) {

						$query = "UPDATE `user` SET username = '$username', email = '$email', 
								password = '$password_baru',  no_telp = '$no_telp', alamat = '$alamat',
								foto = '$foto', lat = '$lat', lng = '$lng' WHERE id_user = '$id_user'";
						$result = mysqli_query($con, $query);

						$getUser = mysqli_query($con, "SELECT * FROM `user` WHERE id_user = '$id_user'");
						$rows = mysqli_fetch_assoc($getUser);

						if ($result){

							$response = array("error" => false, "message" => "Profil Berhasil Diubah", "result" =>
											array(
												array("username" => $rows['username'], 
														"email" => $rows['email'],
														"id_user" => $rows['id_user'],
														"foto" => $rows['foto'],
														"lat" => $rows['lat'],
														"lng" => $rows['lng'],
														"telepon" => $rows['no_telp'],
														"alamat" => $rows['alamat'])
											));
							break;

						} else if (!$result) {

							$response['error'] = true;
							$response['message'] = "Terjadi Kesalahan";
							break;

						}
						

					} else if ($password != $row['password']) {

						$response['error'] = true;
						$response['message'] = "Password Lama Salah";
						break;

					}

			}

			if (empty($password) && empty($password_baru)){ // jika ubah profil saja

				$query = "UPDATE `user` SET username = '$username', email = '$email', 
							no_telp = '$no_telp', alamat = '$alamat', foto = '$foto', lat = '$lat', 
								lng = '$lng' WHERE id_user = '$id_user'";

				$result = mysqli_query($con, $query);

				$getUser = mysqli_query($con, "SELECT * FROM `user` WHERE id_user = '$id_user'");
				$baris = mysqli_fetch_assoc($getUser);

				if ($result){

					$response = array("error" => false, "message" => "Profil Berhasil Diubah", "result" =>
										array(
											array("username" => $baris['username'], 
													"email" => $baris['email'],
													"id_user" => $baris['id_user'],
													"foto" => $baris['foto'],
													"lat" => $baris['lat'],
													"lng" => $baris['lng'],
													"telepon" => $baris['no_telp'],
													"alamat" => $baris['alamat'])
										));
					break;

				} else if (!$result){

					$response['error'] = true;
					$response['message'] = "Terjadi Kesalahan";
					break;

				}

			}

			if (empty($password) || empty($password_baru)){ //jika isi salah 1 password aja

				$response['error'] = true;
				$response['message'] = "Password Gagal Diubah";
				break;

			} 

		}

		} while(false);

	} else {

		$response['error'] = true;
		$response['message'] = "Terjadi Kesalahan";

	}

	echo json_encode($response);

	function upload(){

		$part = "../upload/user/";
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
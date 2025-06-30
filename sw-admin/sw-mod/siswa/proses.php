<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else {
require_once '../../../sw-library/sw-config.php';
require_once '../../login/login_session.php';
require_once '../../../sw-library/sw-function.php';
require_once '../../../sw-library/qr_code/qrlib.php';

$max_size = 2000000; //2MB
// $salt = '$%DEf0&TTd#%dSuTyr47542"_-^@#&*!=QxR094{a911}+';

$date = date('Y-m-d');
$time = date('H:i:s');
$year = date('Y');

   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   

switch (@$_GET['action']){

case 'add':
    $error = array();

    // Validate input fields
    if (empty($_POST['nis'])) {
        $error[] = 'NIS tidak boleh kosong';
    } else {
        $nis = anti_injection($_POST['nis']);
    }

    if (empty($_POST['nama_siswa'])) {
        $error[] = 'Nama tidak boleh kosong';
    } else {
        $nama_siswa = anti_injection($_POST['nama_siswa']);
    }

    if (empty($_POST['nisn'])) {
        $error[] = 'NISN tidak boleh kosong';
    } else {
        $nisn = anti_injection($_POST['nisn']);
    }

    if (empty($_POST['id_kelas'])) {
        $error[] = 'Kelas tidak boleh kosong';
    } else {
        $id_kelas = anti_injection($_POST['id_kelas']);
    }

    if (empty($_POST['jenis_kelamin'])) {
        $error[] = 'Jenis Kelamin tidak boleh kosong';
    } else {
        $jenis_kelamin = anti_injection($_POST['jenis_kelamin']);
    }

    if (empty($_POST['agama'])) {
        $error[] = 'Agama tidak boleh kosong';
    } else {
        $agama = anti_injection($_POST['agama']);
    }

    if (empty($_POST['no_hp'])) {
        $error[] = 'No HP tidak boleh kosong';
    } else {
        $no_hp = anti_injection($_POST['no_hp']);
    }

    if (empty($_POST['tahun_ajaran'])) {
        $error[] = 'Tahun ajaran tidak boleh kosong';
    } else {
        $tahun_ajaran = anti_injection($_POST['tahun_ajaran']);
    }

    if (empty($_POST['building_id'])) {
        $error[] = 'Lokasi tidak boleh kosong';
    } else {
        $building_id = anti_injection($_POST['building_id']);
    }

    // Handle photo upload
    $photo = $_FILES["photo"]["name"];
    $photo_path = '';
    
    if ($photo != '') {
        $lokasi_file = $_FILES['photo']['tmp_name'];
        $ukuran_file = $_FILES['photo']['size'];
        $extension = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
        $photo = strip_tags(md5($photo)) . '.' . $extension;

        // Validate image format
        if (!in_array($extension, ['jpg', 'jpeg', 'gif'])) {
            echo 'Gambar/Foto yang di unggah tidak sesuai dengan format, Berkas harus berformat JPG, JPEG, GIF..!';
            exit;
        }

        // Resize image
        if ($ukuran_file <= $max_size) {
            $directory = '../../../sw-content/siswa/' . $photo;
            $src = imagecreatefromstring(file_get_contents($lokasi_file));
            list($width, $height) = getimagesize($lokasi_file);
                $newwidth = 400; // Set desired width
                $k = $width / $newwidth;
                $newheight = (int)($height / $k);
                $tmp = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagejpeg($tmp, $directory, 90);
            $photo_path = $photo; // Save the photo path for the database
        } else {
            echo 'Gambar yang di unggah terlalu besar Maksimal Size 2MB..!';
            exit;
        }
    } else {
        $photo_path = '-'; // Default value if no photo is uploaded
    }

    // If no errors, proceed to insert data
    if (empty($error)) {
        $stmt = $connection->prepare("INSERT INTO student (nis, nama_siswa, nisn, building_id, id_kelas, jenis_kelamin, agama, no_hp, tahun_ajaran, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssssss", $nis, $nama_siswa, $nisn, $building_id, $id_kelas, $jenis_kelamin, $agama, $no_hp, $tahun_ajaran, $photo_path);


        if ($stmt->execute()) {
            echo 'success';
            
            // Generate QR Code dengan format: NIS-NAMA-TAHUN
            $qrContent = "$nis-$nama_siswa-$tahun_ajaran";
            $qrCodeFileName = 'qr-'.md5($nis).'.png'; // Nama file random berdasarkan NIS
            $qrCodePath = '../../../sw-content/siswa-code-qr/'.$qrCodeFileName;
            
            // Pastikan folder untuk QR Code ada
            if (!file_exists('../../../sw-content/siswa-code-qr/')) {
                mkdir('../../../sw-content/siswa-code-qr/', 0777, true);
            }
            
            // Generate QR Code menggunakan library QRcode
            QRcode::png($qrContent, $qrCodePath, QR_ECLEVEL_Q, 8, 2);
            
            // Update database dengan path QR Code
            $updateQrQuery = "UPDATE student SET qr_code = ? WHERE nis = ?";
            $stmtUpdate = $connection->prepare($updateQrQuery);
            $stmtUpdate->bind_param("ss", $qrCodeFileName, $nis);
            $stmtUpdate->execute();
            $stmtUpdate->close();

        } else {
            echo 'Data tidak berhasil disimpan: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        foreach ($error as $key => $values) {
            echo $values . '<br>';
        }
    }
    break;

    



/* ------------------------------
    Update
---------------------------------*/
case 'update':
    $error = array();
    if (empty($_POST['nis'])) {
        $error[] = 'NIS tidak boleh kosong';
    } else {
        $nis = mysqli_real_escape_string($connection, $_POST['nis']);
    }

    if (empty($_POST['siswa_nis'])) {
        $error[] = 'NIS tidak boleh kosong';
    } else {
        $siswa_nis = mysqli_real_escape_string($connection, $_POST['siswa_nis']);
    }

    if (empty($_POST['nama_siswa'])) {
        $error[] = 'Nama tidak boleh kosong';
    } else {
        $nama_siswa = mysqli_real_escape_string($connection, $_POST['nama_siswa']);
    }

    if (empty($_POST['nisn'])) {
        $error[] = 'NISN tidak boleh kosong';
    } else {
        $nisn = mysqli_real_escape_string($connection, $_POST['nisn']);
    }

    if (empty($_POST['id_kelas'])) {
        $error[] = 'Kelas tidak boleh kosong';
    } else {
        $id_kelas = mysqli_real_escape_string($connection, $_POST['id_kelas']);
    }

    if (empty($_POST['jenis_kelamin'])) {
        $error[] = 'Jenis Kelamin tidak boleh kosong';
    } else {
        $jenis_kelamin = mysqli_real_escape_string($connection, $_POST['jenis_kelamin']);
    }

    if (empty($_POST['agama'])) {
        $error[] = 'Agama tidak boleh kosong';
    } else {
        $agama = mysqli_real_escape_string($connection, $_POST['agama']);
    }

    if (empty($_POST['no_hp'])) {
        $error[] = 'No HP tidak boleh kosong';
    } else {
        $no_hp = mysqli_real_escape_string($connection, $_POST['no_hp']);
    }

    if (empty($_POST['tahun_ajaran'])) {
        $error[] = 'Tahun Ajaran tidak boleh kosong';
    } else {
        $tahun_ajaran = mysqli_real_escape_string($connection, $_POST['tahun_ajaran']);
    }

    if (empty($_POST['building_id'])) {
        $error[] = 'Lokasi tidak boleh kosong';
    } else {
        $building_id = mysqli_real_escape_string($connection, $_POST['building_id']);
    }

    // Handle photo upload
    $photo = $_FILES["photo"]["name"];
    $lokasi_file = $_FILES['photo']['tmp_name'];
    $ukuran_file = $_FILES['photo']['size'];

    if (empty($error)) {
        // If a new photo is uploaded
        if ($photo != '') {
            $extension = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
            $photo_name = strip_tags(md5($photo)) . '.' . $extension;

            // Validate image format
            if (!in_array($extension, ['jpg', 'jpeg', 'gif'])) {
                echo 'Gambar/Foto yang di unggah tidak sesuai dengan format, Berkas harus berformat JPG, JPEG, GIF..!';
                exit;
            }

            // Resize image
            if ($ukuran_file <= $max_size) {
                $directory = '../../../sw-content/siswa/' . $photo_name;
                $src = imagecreatefromstring(file_get_contents($lokasi_file));
                list($width, $height) = getimagesize($lokasi_file);
                $newwidth = 400; // Set desired width
                $k = $width / $newwidth;
                $newheight = (int)($height / $k);
                $tmp = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                imagejpeg($tmp, $directory, 90);
                $photo_path = $photo_name; // Save the photo path for the database
            } else {
                echo 'Gambar yang di unggah terlalu besar Maksimal Size 2MB..!';
                exit;
            }
        } else {
            // If no new photo uploaded, keep the old photo
            $photo_path = null;
        }

        // Build update query
        $update = "UPDATE student SET 
                    nis='$siswa_nis',
                    nama_siswa='$nama_siswa',
                    nisn='$nisn',
                    building_id='$building_id',
                    id_kelas='$id_kelas',
                    jenis_kelamin='$jenis_kelamin',
                    agama='$agama',
                    no_hp='$no_hp',
                    tahun_ajaran='$tahun_ajaran'";

        if ($photo_path !== null) {
            $update .= ", photo='$photo_path'";
        }

        $update .= " WHERE nis='$nis'";

        if ($connection->query($update) === false) {
            echo 'Data tidak berhasil disimpan!';
        } else {
            echo 'success';
        }
    } else {
        foreach ($error as $key => $values) {
            echo $values;
        }
    }
break;


/* --------------- Delete ------------*/
case 'delete':
  $id       = anti_injection(($_POST['id']));

    $cari =mysqli_query($connection,"SELECT photo,qr_code from student WHERE nis='$id'");
    $data =mysqli_fetch_assoc($cari);
    $images_delete = strip_tags($data['photo']);
    $directory='../../../sw-content/siswa/'.$images_delete.'';

  $deleted  = "DELETE FROM student WHERE nis='$id'";
    if($connection->query($deleted) === true) {
        echo'success';
        if(file_exists("../../../sw-content/siswa/$images_delete")){
          unlink ($directory);
        }

        if(file_exists("../../../sw-content/siswa-code-qr/$data[qr_code]")){
          $qrcode ='../../../sw-content/siswa-code-qr/'.$data['qr_code'].'';
          unlink ($qrcode);
        }

      } else { 
        //tidak berhasil
        echo'Data tidak berhasil dihapus.!';
        die($connection->error.__LINE__);
  }


/* ------------- IMPORT --------------*/
break;
case 'import':
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    if (!empty($_FILES['files']['name']) && in_array($_FILES['files']['type'], $csvMimes)) {
        // If the file is uploaded
        if (is_uploaded_file($_FILES['files']['tmp_name'])) {
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['files']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                // Get row data for student import
                $nis = $line[0];
                $nama_siswa = $line[1];
                $nisn = $line[2];
                $id_kelas = $line[3];
                $jenis_kelamin = $line[4];
                $agama = $line[5];
                $no_hp = $line[6];
                $tahun_ajaran = $line[7];
                $building_id = $line[8];

                // Check if student exists
                $query = "SELECT nis FROM student WHERE nis='$nis'";
                $result = $connection->query($query);

                if ($result->num_rows > 0) {
                    // Update student data in the database
                    $update = "UPDATE student SET 
                        nama_siswa='$nama_siswa',
                        nisn='$nisn',
                        id_kelas='$id_kelas',
                        jenis_kelamin='$jenis_kelamin',
                        agama='$agama',
                        no_hp='$no_hp',
                        tahun_ajaran='$tahun_ajaran',
                        building_id='$building_id'
                        WHERE nis='$nis'";
                    $connection->query($update);
                } else {
                    // Insert new student data
                    $add = "INSERT INTO student (nis, nama_siswa, nisn, id_kelas, jenis_kelamin, agama, no_hp, tahun_ajaran, building_id, created_at, created_cookies) VALUES (
                        '$nis', '$nama_siswa', '$nisn', '$id_kelas', '$jenis_kelamin', '$agama', '$no_hp', '$tahun_ajaran', '$building_id', '$date $time', '-')";
                    if ($connection->query($add) === false) {
                        echo 'Data Siswa Tidak dapat di Import.!';
                    }
                }
            }

            // Close opened CSV file
            fclose($csvFile);
            echo 'success';
        } else {
            echo 'Data Siswa tidak berhasil di import.!';
        }
    } else {
        echo 'File tidak sesuai format, Upload file CSV.!';
    }

    break;

}

}

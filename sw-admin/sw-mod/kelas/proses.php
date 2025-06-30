<?php
session_start();
if (empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])) {
    header('location:../../login/');
    exit;
} else {
    require_once '../../../sw-library/sw-config.php';
    require_once '../../login/login_session.php';
    include('../../../sw-library/sw-function.php');

    switch (@$_GET['action']) {
        case 'add':
            $error = array();
            if (empty($_POST['nama_kelas'])) {
                $error[] = 'Nama Kelas tidak boleh kosong';
            } else {
                $nama_kelas = mysqli_real_escape_string($connection, $_POST['nama_kelas']);
            }

            if (empty($_POST['id_jurusan'])) {
                $error[] = 'Jurusan tidak boleh kosong';
            } else {
                $id_jurusan = mysqli_real_escape_string($connection, $_POST['id_jurusan']);
            }

            if (empty($error)) { 
                $add = "INSERT INTO class (nama_kelas, id_jurusan) VALUES ('$nama_kelas', '$id_jurusan')"; 
                if ($connection->query($add) === false) { 
                    echo 'Data tidak berhasil disimpan!';
                } else {
                    echo 'success';
                }
            } else {           
                echo implode(", ", $error);
            }
            break;

        case 'update':
            $error = array();
            if (empty($_POST['id'])) {
                $error[] = 'ID tidak boleh kosong';
            } else {
                $id = mysqli_real_escape_string($connection, $_POST['id']);
            }

            if (empty($_POST['nama_kelas'])) {
                $error[] = 'Nama Kelas tidak boleh kosong';
            } else {
                $nama_kelas = mysqli_real_escape_string($connection, $_POST['nama_kelas']);
            }

            if (empty($_POST['id_jurusan'])) {
                $error[] = 'Jurusan tidak boleh kosong';
            } else {
                $id_jurusan = mysqli_real_escape_string($connection, $_POST['id_jurusan']);
            }

            if (empty($error)) { 
                $update = "UPDATE class SET nama_kelas='$nama_kelas', id_jurusan='$id_jurusan' WHERE id_kelas='$id'"; 
                if ($connection->query($update) === false) { 
                    echo 'Data tidak berhasil disimpan!';
                } else {
                    echo 'success';
                }
            } else {           
                echo implode(", ", $error);
            }
            break;

        case 'delete':
            $id = mysqli_real_escape_string($connection, $_POST['id']);
            // Check if class is used in other tables (example: students)
            $check_query = "SELECT id_kelas FROM student WHERE id_kelas='$id' LIMIT 1";
            $check_result = $connection->query($check_query);
            if ($check_result && $check_result->num_rows > 0) {
                echo 'Data digunakan, Data tidak dapat dihapus.!';
            } else {
                $delete_query = "DELETE FROM class WHERE id_kelas='$id'";
                if ($connection->query($delete_query) === true) {
                    echo 'success';
                } else {
                    echo 'Data tidak berhasil dihapus.!';
                }
            }
            break;
    }
}

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
            if (empty($_POST['nama_mapel'])) {
                $error[] = 'Nama Mapel tidak boleh kosong';
            } else {
                $nama_mapel = mysqli_real_escape_string($connection, $_POST['nama_mapel']);
            }

            if (empty($error)) { 
                $add = "INSERT INTO subjects (nama_mapel) VALUES ('$nama_mapel')"; 
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

            if (empty($_POST['nama_mapel'])) {
                $error[] = 'Nama Mapel tidak boleh kosong';
            } else {
                $nama_mapel = mysqli_real_escape_string($connection, $_POST['nama_mapel']);
            }

            if (empty($error)) { 
                $update = "UPDATE subjects SET nama_mapel='$nama_mapel' WHERE id_mapel='$id'"; 
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
            // Check if subject is used in other tables (example: employees)
            $check_query = "SELECT id FROM employees WHERE id_mapel='$id' LIMIT 1";
            $check_result = $connection->query($check_query);
            if ($check_result && $check_result->num_rows > 0) {
                echo 'Data digunakan, Data tidak dapat dihapus.!';
            } else {
                $delete_query = "DELETE FROM subjects WHERE id_mapel='$id'";
                if ($connection->query($delete_query) === true) {
                    echo 'success';
                } else {
                    echo 'Data tidak berhasil dihapus.!';
                }
            }
            break;
    }
}

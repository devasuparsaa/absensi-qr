<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php'); 

switch (@$_GET['action']){
case 'absent':

if (empty($_POST['qr_code'])) {
      $error[] = 'QR Code tidak boleh kosong';
    } else {
      $qr_code= trim(mysqli_real_escape_string($connection, $_POST['qr_code']));
}

if (empty($_POST['latitude'])) {
      $error[] = 'Lokasi tidak boleh kosong';
    } else {
      $latitude= mysqli_real_escape_string($connection, $_POST['latitude']);
}

if (empty($error)){  
    // Parse scanned QR code content to extract NIS
    $qr_parts = explode('-', $qr_code);
    $nis_scanned = $qr_parts[0]; // Assuming NIS is the first part

    $query_u="SELECT student.nis,student.qr_code,student.nama_siswa,student.shift_id,shift.shift_id,shift.time_in,shift.time_out FROM student,shift WHERE student.shift_id=shift.shift_id AND student.nis='$nis_scanned'";
    $result_user = $connection->query($query_u);
    if($result_user->num_rows > 0){
    $row_user = $result_user->fetch_assoc();
    
    $time_out     = strtotime(''.$row_user['time_out'].' - 60 minute');
    $time_out     = date('H:i:s', $time_out);

        // Cek data Absen Berdasarkan tanggal sekarang
        $query  ="SELECT nis,time_in,time_out FROM presence_student WHERE nis='$row_user[nis]' AND presence_date='$date'";
        $result = $connection->query($query);
        if($result->num_rows > 0){
          $row = $result->fetch_assoc();
          // Update Absensi Pulang
          if($time_out < $time){
              if($row['time_out']=='00:00:00'){
                  //Update Jam Pulang
                  $update ="UPDATE presence_student SET time_out='$time',latitude_longtitude_out='$latitude' WHERE nis='$row_user[nis]' AND presence_date='$date'";
                  if($connection->query($update) === false) { 
                      die($connection->error.__LINE__); 
                      echo'Sepetinya sitem kami sedang error!';
                  } else{
                      //Jam Pulang
                      echo'success/Selamat "'.$row_user['nama_siswa'].'" berhasil Absen Pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time.', Hati-hati dijalan saat pulang "'.$row_user['nama_siswa'].'".!';
                  }
              }
              else{
                echo'Sebelumnya "'.$row_user['nama_siswa'].'" sudah pernah Absen Pulang pada Tanggal '.tanggal_ind($date).' dan Jam '.$row['time_out'].'.!';
            }
          }else{
            echo'Absen pulang belum diperbolehkan "'.$row_user['nama_siswa'].'", Absen pulang aktif 30 menit sebelum jam pulang.!';
          }
        // Else Absen Mmasuk
        }else{
            $add ="INSERT INTO presence_student (nis,
                              presence_date,
                              time_in,
                              time_out,
                              present_id,
                              latitude_longtitude_in,
                              latitude_longtitude_out,
                              information) values('$row_user[nis]',
                              '$date',
                              '$time',
                              '00:00:00',
                              '1', /*hadir*/
                              '$latitude',
                              '',
                              '')";
                    
            if($connection->query($add) === false) { 
                die($connection->error.__LINE__); 
                echo'Sepertinya Sistem Kami sedang error!';
            } else{
                echo'success/Selamat Anda berhasil Absen Masuk pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time.', Semangat Belajar "'.$row_user['nama_siswa'].'" !';
            }
          }
      }
      else{
        // Jika user tidak ditemukan
        echo'Data tidak ditemukan';
      }
    }
    else{
      foreach ($error as $key => $values) {            
          echo"$values\n";
        }
}


break;
case 'data':
echo'
<table class="table table-hover" id="swdatatable">
    <thead>
        <tr>
            <th class="align-middle text-center" width="10">No</th>
            <th class="align-middle">Nama</th>
            <th class="align-middle">Absen Masuk</th>
            <th class="align-middle">Absen Pulang</th>
        </tr>
    </thead>
    <tbody>';
    $no=0;
	$query_absen ="SELECT presence_student.nis, presence_student.time_in, presence_student.time_out, student.nama_siswa FROM presence_student,student WHERE presence_student.nis=student.nis AND presence_student.presence_date='$date' ORDER BY presence_student.presence_id";
    $result_absen = $connection->query($query_absen);
    if($result_absen->num_rows > 0){
        while ($row_absen = $result_absen->fetch_assoc()) {
           $no++;
     echo'
        <tr>
            <td class="text-center">'.$no.'</td>
            <td>'.strip_tags($row_absen['nama_siswa']).'</td>
            <td><span class="label label-success">'.$row_absen['time_in'].'</td>
            <td><span class="label label-danger">'.$row_absen['time_out'].'</td>
        </tr>';
    }}
    echo'
    </tbody>
</table>';?>
<script type="text/javascript">
	 $('#swdatatable').dataTable({
        "iDisplayLength": 20,
        "aLengthMenu": [[20, 30, 50, -1], [20, 30, 50, "All"]]
    });
</script>
<?php
break;
}

}

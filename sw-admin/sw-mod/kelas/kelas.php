<?php 
if (empty($connection)) {
    header('location:../../');
} else {
    $gotoprocess = "sw-mod/$mod/proses.php";
    include_once 'sw-mod/sw-panel.php';
    echo '<div class="content-wrapper">';
    switch (@$_GET['op']) { 
        default:
            echo '
            <section class="content-header">
                <h1>Data<small> Kelas & Jurusan</small></h1>
                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
                    <li class="active">Data Kelas & Jurusan</li>
                </ol>
            </section>';
            echo '
            <section class="content">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Data Kelas & Jurusan</b></h3>
                                <div class="box-tools pull-right">';
                                  if ($level_user == 1) {
                                      echo '<button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modalAdd"><i class="fa fa-plus"></i> Tambah Baru</button>';
                                  } else {
                                      echo '<button type="button" class="btn btn-success btn-flat access-failed"><i class="fa fa-plus"></i> Tambah Baru</button>';
                                  }
                                  echo '
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="swdatatable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width:20px" class="text-center">No</th>
                                                <th class="text-center">ID</th>
                                                <th>Nama Kelas</th>
                                                <th>Nama Jurusan</th>
                                                <th style="width:100px" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                          $query = "SELECT k.id_kelas, k.nama_kelas, j.nama_jurusan FROM class k JOIN major j ON k.id_jurusan = j.id_jurusan";
                                          $result = $connection->query($query);
                                          if ($result->num_rows > 0) {
                                              $no = 0;
                                              while ($row = $result->fetch_assoc()) {
                                                  $no++;
                                                  echo '
                                                  <tr>
                                                      <td class="text-center">' . htmlspecialchars($no) . '</td>
                                                      <td class="text-center">' . htmlspecialchars($row['id_kelas']) . '</td>
                                                      <td>' . htmlspecialchars($row['nama_kelas']) . '</td>
                                                      <td>' . htmlspecialchars($row['nama_jurusan']) . '</td>
                                                      <td class="text-center">
                                                          <div class="btn-group">';
                                                  if ($level_user == 1) {
                                                      echo '
                                                              <a href="#modalEdit" class="btn btn-warning btn-sm enable-tooltip" title="Edit" data-toggle="modal" onclick="document.getElementById(\'txtid\').value=\'' . htmlspecialchars($row['id_kelas']) . '\';document.getElementById(\'txtname\').value=\'' . htmlspecialchars($row['nama_kelas']) . '\';document.getElementById(\'txtjurusan\').value=\'' . htmlspecialchars($row['nama_jurusan']) . '\';"><i class="fa fa-pencil-square-o"></i></a>
                                                              <button data-id="' . ($row['id_kelas']) . '" class="btn btn-sm btn-danger delete" title="Hapus"><i class="fa fa-trash-o"></i></button>';
                                                  } else {
                                                      echo '
                                                              <button type="button" class="btn btn-warning btn-sm access-failed enable-tooltip" title="Edit"><i class="fa fa-pencil-square-o"></i> Ubah</button>
                                                              <button type="button" class="btn btn-sm btn-danger access-failed" title="Hapus"><i class="fa fa-trash-o"></i></button>';
                                                  }
                                                  echo '
                                                          </div>
                                                      </td>
                                                  </tr>';
                                              }
                                          }
                                          echo '
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </section>

                <!-- Add -->
                <div class="modal fade" id="modalAdd" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Tambah Baru</h4>
                            </div>
                            <form class="form validate add-class">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Kelas</label>
                                        <input type="text" class="form-control" name="nama_kelas" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Jurusan</label>
                                        <select name="id_jurusan" class="form-control" required>';
                                          $jurusan_query = "SELECT id_jurusan, nama_jurusan FROM major ORDER BY nama_jurusan ASC";
                                          $jurusan_result = $connection->query($jurusan_query);
                                          while ($jurusan = $jurusan_result->fetch_assoc()) {
                                              echo '<option value="' . htmlspecialchars($jurusan['id_jurusan']) . '">' . htmlspecialchars($jurusan['nama_jurusan']) . '</option>';
                                          }
                                          echo '
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-check"></i> Simpan</button>
                                    <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Update Data</h4>
                            </div>
                            <form class="form update-class" method="post">
                                <input type="hidden" name="id" id="txtid" required value="" readonly>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Kelas</label>
                                        <input type="text" class="form-control" id="txtname" name="nama_kelas" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Jurusan</label>
                                        <select name="id_jurusan" id="txtjurusan" class="form-control" required>';
                                          $jurusan_query = "SELECT id_jurusan, nama_jurusan FROM major ORDER BY nama_jurusan ASC";
                                          $jurusan_result = $connection->query($jurusan_query);
                                          while ($jurusan = $jurusan_result->fetch_assoc()) {
                                              echo '<option value="' . htmlspecialchars($jurusan['id_jurusan']) . '">' . htmlspecialchars($jurusan['nama_jurusan']) . '</option>';
                                          }
                                          echo '
                                        </select>
                                    </div>
                                     <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-check"></i> Simpan</button>
                                    <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
            break;
    }
    echo '</div>';
}
?>

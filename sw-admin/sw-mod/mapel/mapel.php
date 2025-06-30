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
                <h1>Data<small> Matapelajaran</small></h1>
                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
                    <li class="active">Data Matapelajaran</li>
                </ol>
            </section>';
            echo '
            <section class="content">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Data Matapelajaran</b></h3>
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
                                                <th>Nama Mapel</th>
                                                <th class="text-center">Jumlah Guru Pengajar</th>
                                                <th style="width:100px" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            $query = "SELECT id_mapel, nama_mapel FROM subjects ORDER BY id_mapel DESC";
                                            $result = $connection->query($query);
                                            if ($result->num_rows > 0) {
                                                $no = 0;
                                                while ($row = $result->fetch_assoc()) {
                                                    $employees_count = "SELECT id FROM employees WHERE id_mapel='" . $row['id_mapel'] . "'";
                                                    $result_count = $connection->query($employees_count);
                                                    $no++;
                                                    echo '
                                                    <tr>
                                                        <td class="text-center">' . $no . '</td>
                                                        <td class="text-center">' . htmlspecialchars($row['id_mapel']) . '</td>
                                                        <td>' . htmlspecialchars($row['nama_mapel']) . '</td>
                                                        <td class="text-center"><span class="badge bg-yellow">' . $result_count->num_rows . '</span></td>
                                                        <td class="text-center">
                                                            <div class="btn-group">';
                                                    if ($level_user == 1) {
                                                        echo '
                                                        <a href="#modalEdit" class="btn btn-warning btn-sm enable-tooltip" title="Edit" data-toggle="modal" onclick="document.getElementById(\'txtid\').value=\'' . htmlspecialchars($row['id_mapel']) . '\'; document.getElementById(\'txtname\').value=\'' . htmlspecialchars($row['nama_mapel']) . '\';"><i class="fa fa-pencil-square-o"></i></a>
                                                        <button data-id="' . ($row['id_mapel']) . '" class="btn btn-sm btn-danger delete" title="Hapus"><i class="fa fa-trash-o"></i></button>';
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
                            <form class="form validate add-subject">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Mapel</label>
                                        <input type="text" class="form-control" name="nama_mapel" required>
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
                            <form class="form update-subject" method="post">
                                <input type="hidden" name="id" id="txtid" required value="" readonly>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Mapel</label>
                                        <input type="text" class="form-control" id="txtname" name="nama_mapel" required>
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

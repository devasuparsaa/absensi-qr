<?php
if (empty($connection)) {
  header('location:../../');
} else {
  include_once 'sw-mod/sw-panel.php';
  echo '
  <div class="content-wrapper">';
  switch (@$_GET['op']) {
    default:
      echo '
        <section class="content-header">
          <h1>Data<small> Siswa</small></h1>
            <ol class="breadcrumb">
              <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
              <li class="active">Data Siswa</li>
            </ol>
        </section>';
      echo '
        <section class="content">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Data Siswa</b></h3>
                  <div class="box-tools pull-right">';
              if ($level_user == 1) {
                echo '
                    <a href="#import" class="btn btn-warning" title="Import" data-toggle="modal"> Import</a>
                    <a href="' . $mod . '&op=add" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Tambah Baru</a>';
              } else {
                echo '<button type="button" class="btn btn-success btn-flat access-failed"><i class="fa fa-plus"></i> Tambah Baru</button>';
              }
              echo '
                  </div>
                </div>
            <div class="box-body">
              <div class="table-responsive">
                  <table id="sw-datatable" class="table table-bordered">
                    <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th class="text-center" width="70">QR Code</th>
                      <th>NIS</th>
                      <th>Nama</th>
                      <th>NISN</th>
                      <th>Kelas & Jurusan</th>
                      <th>Jenis Kelamin</th>
                      <th>No HP</th>
                      <th style="width:150px" class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div> 
        </section>';
      echo '
    <div id="import" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <form id="validate" class="import" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Import Data Pegawai</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Upload File</label>
                  <input type="file" class="upload form-control" name="files" accept=".csv">
                </div>
             
              <p><a href="../sw-content/sample-import.csv">Download Sample File</a></p>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>';

      // add data Siswa
      break;
    case 'add':
      echo '
        <section class="content-header">
          <h1>Tambah Data<small> Siswa</small></h1>
            <ol class="breadcrumb">
              <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
              <li><a href="./siswa"> Data Siswa</a></li>
              <li class="active">Tambah Siswa</li>
            </ol>
        </section>';
              echo '
        <section class="content">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title"><b>Tambah Data Siswa</b></h3>
                </div>

                <div class="box-body">
                  <form class="form-horizontal validate add-siswa">
                      <div class="box-body">

                        <div class="form-group">
                          <label class="col-sm-2 control-label">NIS</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" name="nis" required>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">Nama Siswa</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" name="nama_siswa" required>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">NISN</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" name="nisn" required>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Kelas & Jurusan</label>
                          <div class="col-sm-6">
                          <select class="form-control" name="id_kelas" required="">
                              <option value="">- Pilih -</option>';
                                $query = "SELECT c.id_kelas, c.nama_kelas, m.nama_jurusan FROM class c LEFT JOIN major m ON c.id_jurusan = m.id_jurusan ORDER BY c.nama_kelas ASC";
                                $result = $connection->query($query);
                                while ($row = $result->fetch_assoc()) {
                                  echo '<option value="' . $row['id_kelas'] . '">' . $row['nama_kelas'] . ' - ' . $row['nama_jurusan'] . '</option>';
                                }
                                echo '
                          </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">Jenis Kelamin</label>
                          <div class="col-sm-6">
                          <select class="form-control" name="jenis_kelamin" required="">
                              <option value="">- Pilih -</option>
                              <option value="Laki-laki">Laki-laki</option>
                              <option value="Perempuan">Perempuan</option>
                          </select>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Agama</label>
                          <div class="col-sm-6">
                            <select class="form-control" name="agama" required="">
                              <option value="">- Pilih -</option>
                              <option value="Hindu">Hindu</option>
                              <option value="Islam">Islam</option>
                              <option value="Kristan">Kristan</option>
                              <option value="Katolik">Katolik</option>
                          </select>                  
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">No HP</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" name="no_hp" required>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">Tahun Ajaran</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" name="tahun_ajaran" required>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">Sekolah</label>
                          <div class="col-sm-6">
                          <select class="form-control" name="building_id" id="building" required="">
                              <option value="">- Pilih -</option>';
                                $query = "SELECT building_id,name,address from building order by name ASC";
                                $result = $connection->query($query);
                                while ($row = $result->fetch_assoc()) {
                                  echo '<option value="' . $row['building_id'] . '">' . strip_tags($row['name']) . '</option>';
                                }
                                echo '
                          </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">Foto</label>
                          <div class="col-sm-6">
                            <div class="upload-media">
                                <img src="sw-assets/img/media.png" id="output" class="img-responsive" width="100">
                                <input type="file" class="upload-hidden" name="photo" onchange="loadFile(event)" accept="image/jpeg, image/jpg, image/gif">
                            </div>
                            <small>Kosongan jika tidak ingin mengupload foto</small>
                          </div>
                        </div>

                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2"></div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                        <a class="btn btn-danger" href="./' . $mod . '"><i class="fa fa-remove"></i> Batal</a>
                      </div>
                      <!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </section>';
      break;
    // end add data Siswa

    // Edit data Siswa
case 'edit':
    echo '
    <section class="content-header">
        <h1>Edit Data<small> Siswa</small></h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li><a href="./siswa"> Data Siswa</a></li>
            <li class="active">Edit Siswa</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="box box-solid">
                    <div class="box-header">
                      <h3 class="box-title"><b>Edit Data Siswa</b></h3>
                    </div>

                    <div class="box-body">';
                    if (!empty($_GET['nis'])) {
                        $nis = mysqli_real_escape_string($connection, epm_decode($_GET['nis']));
                        $query = "SELECT * FROM student WHERE nis='$nis' LIMIT 1";
                        $result = $connection->query($query);
                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo '
                            <div class="nav-tabs-custom">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <form class="form-horizontal validate update-siswa" method="post" enctype="multipart/form-data">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">NIS</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" name="siswa_nis" value="' . htmlspecialchars($row['nis']) . '" required>
                                                        <input type="hidden" name="nis" value="' . htmlspecialchars($row['nis']) . '" readonly required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Nama Siswa</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" name="nama_siswa" value="' . htmlspecialchars($row['nama_siswa']) . '" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">NISN</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" name="nisn" value="' . htmlspecialchars($row['nisn']) . '" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Kelas & Jurusan</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" name="id_kelas" required>
                                                            <option value="">- Pilih -</option>';
                                                            $query = "SELECT c.id_kelas, c.nama_kelas, m.nama_jurusan FROM class c LEFT JOIN major m ON c.id_jurusan = m.id_jurusan ORDER BY c.nama_kelas ASC";
                                                            $result = $connection->query($query);
                                                            while ($kelas = $result->fetch_assoc()) {
                                                                $selected = ($kelas['id_kelas'] == $row['id_kelas']) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($kelas['id_kelas']) . '" ' . $selected . '>' . htmlspecialchars($kelas['nama_kelas']) . ' - ' . htmlspecialchars($kelas['nama_jurusan']) . '</option>';
                                                            }
                                                            echo '
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Jenis Kelamin</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" name="jenis_kelamin" required>
                                                            <option value="">- Pilih -</option>
                                                            <option value="Laki-laki" ' . htmlspecialchars($row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '') . '>Laki-laki</option>
                                                            <option value="Perempuan" ' . htmlspecialchars($row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '') . '>Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Agama</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" name="agama" required>
                                                            <option value="">- Pilih -</option>
                                                            <option value="Hindu" ' . ($row['agama'] == 'Hindu' ? 'selected' : '') . '>Hindu</option>
                                                            <option value="Islam" ' . ($row['agama'] == 'Islam' ? 'selected' : '') . '>Islam</option>
                                                            <option value="Kristen" ' . ($row['agama'] == 'Kristen' ? 'selected' : '') . '>Kristen</option>
                                                            <option value="Katolik" ' . ($row['agama'] == 'Katolik' ? 'selected' : '') . '>Katolik</option>
                                                        </select>                  
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">No HP</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" name="no_hp" value="' . htmlspecialchars($row['no_hp']) . '" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Tahun Ajaran</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" name="tahun_ajaran" value="' . htmlspecialchars($row['tahun_ajaran']) . '" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Sekolah</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" name="building_id" id="building" required>
                                                            <option value="">- Pilih -</option>';
                                                            $query = "SELECT building_id, name FROM building ORDER BY name ASC";
                                                            $result = $connection->query($query);
                                                            while ($building = $result->fetch_assoc()) {
                                                                $selected = ($building['building_id'] == $row['building_id']) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($building['building_id']) . '" ' . $selected . '>' . htmlspecialchars($building['name']) . '</option>';
                                                            }
                                                            echo '
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Foto</label>
                                                    <div class="col-sm-6">
                                                        <div class="upload-media">';
                                                            if ($row['photo'] == NULL) {
                                                                echo '<img src="sw-assets/img/media.png" id="output" class="img-responsive" width="100">';
                                                            } else {
                                                                echo '<img src="../../../sw-content/siswa/' . htmlspecialchars($row['photo']) . '" id="output" class="img-responsive" width="100">';
                                                            }
                                                            echo '
                                                            <input type="file" class="upload-hidden" name="photo" onchange="loadFile(event)" accept="image/jpeg, image/jpg, image/gif">
                                                        </div>
                                                        <small>Kosongan jika tidak ingin mengubah foto</small>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- /.box-body -->
                                            <div class="box-footer">
                                                <div class="col-sm-2"></div>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                                                <a class="btn btn-danger" href="./' . $mod . '"><i class="fa fa-remove"></i> Batal</a>
                                            </div>
                                            <!-- /.box-footer -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- nav-tabs-custom -->';
                        } else {
                            echo '<section class="content">
                                <div class="error-page">
                                    <h2 class="headline text-yellow"> 404</h2>
                                    <div class="error-content">
                                        <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                                        <p>
                                            Saat ini data yang Anda cari tidak ditemukan<br>
                                            <a class="btn btn-primary" href="./">return to dashboard</a>
                                        </p>
                                    </div>
                                </div>
                            </section>';
                        }
                    }
                    echo '
                    </div>
                </div>
            </div> 
        </div>
    </section>';
    break;

  } ?>

  </div>
<?php } ?>
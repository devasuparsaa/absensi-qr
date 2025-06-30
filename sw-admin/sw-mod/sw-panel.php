<?php if(empty($connection)){
  header('location:./404');
} else {

echo'<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <div class="slimScrollDiv">
    <section class="sidebar">
      <!-- Sidebar user panel -->
    
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>';
      if($mod =='home'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./"><i class="fa fa-home"></i><span>Dashboard</span></a></li>';

      if($mod =='scan-absen' OR $mod == 'scan-absen-siswa'){echo'<li class="active treeview">'; }else{echo'<li class="treeview">';}
      echo'
      <a href="#">
        <i class="fa fa-qrcode"></i> <span>Scan Absen</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">';
        if($mod =='scan-absen'){echo'<li class="active">'; }else{echo'<li>';}
        echo'<a href="./scan-absen"><i class="fa fa-circle-o"></i> Scan Guru</a></li>';
        if($mod =='scan-absen-siswa'){echo'<li class="active">'; }else{echo'<li>';}
        echo'<a href="./scan-absen-siswa"><i class="fa fa-circle-o"></i> Scan Siswa</a></li>
      </ul>
      </li>';
      
      if($mod =='karyawan' OR $mod=='siswa' OR $mod=='kelas' OR $mod=='mapel' OR $mod=='jabatan' OR $mod=='shift' OR $mod=='lokasi' OR $mod=='libur' OR $mod=='thema-card'){echo'<li class="active treeview">'; }else{
      echo'<li class="treeview">';}
      echo'
          <a href="#">
            <i class="fa fa-database"></i> <span>Master Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">';
            if($mod =='karyawan'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./karyawan"><i class="fa fa-circle-o"></i> Data Guru</a></li>';
            if($mod =='siswa'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./siswa"><i class="fa fa-circle-o"></i> Data Siswa</a></li>';
            if($mod =='kelas'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./kelas"><i class="fa fa-circle-o"></i> Data Kelas & Jurusan</a></li>';
            if($mod =='mapel'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./mapel"><i class="fa fa-circle-o"></i> Data Matapelajaran</a></li>';
            if($mod =='jabatan'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./jabatan"><i class="fa fa-circle-o"></i> Data Jabatan</a></li>';
            if($mod =='shift'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="shift"><i class="fa fa-circle-o"></i> Data Jam Kerja</a></li>';
            if($mod =='lokasi'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./lokasi"><i class="fa fa-circle-o"></i> Data Lokasi</a></li>';
            if($mod =='libur'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./libur"><i class="fa fa-circle-o"></i>Libur Nasional</a></li>';
            if($mod =='thema-card'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./thema-card"><i class="fa fa-circle-o"></i> Tema ID Card</a></li>
          </ul>
        </li>';

      if($mod =='cuty'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./cuty"><i class="fa fa-calendar" aria-hidden="true"></i> <span>Data Permohonan Cuti</span></a></li>';

      if($mod =='izin'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./izin"><i class="fa fa-files-o" aria-hidden="true"></i> <span>Data Izin Guru</span></a></li>';

      if($mod =='izin-siswa'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./izin-siswa"><i class="fa fa-files-o" aria-hidden="true"></i> <span>Data Izin Siswa</span></a></li>';

      if($mod =='absensi'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./absensi"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Laporan Absensi Guru</span></a></li>';

      if($mod =='laporan-harian'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./laporan-harian"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Laporan Harian Guru</span></a></li>';

            if($mod =='absensi-siswa'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./absensi-siswa"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Laporan Absensi Siswa</span></a></li>';

      if($mod =='laporan-harian-siswa'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./laporan-harian-siswa"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Laporan Harian Siswa</span></a></li>';

      if($mod =='setting'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./setting"><i class="fa fa-cogs" aria-hidden="true"></i> <span>Pengaturan Web</span></a></li>';

      if($mod =='user'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./user"><i class="fa fa-user"></i> <span>Admin</span></a></li>';?>
      <li><a href="javascript:void();" onClick="location.href='./logout';"><i class="fa fa-sign-out text-red"></i>  <span>Keluar</span></a></li>
  <?php echo'
      </ul>
    </section>
  </div>
    <!-- /.sidebar -->
  </aside>';
  }?>
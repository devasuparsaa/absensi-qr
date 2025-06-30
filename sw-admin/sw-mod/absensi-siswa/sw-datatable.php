<?php 
session_start();
if (empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])) {
    header('location:../../login/');
    exit;
} else {
    require_once '../../../sw-library/sw-config.php';
    require_once '../../login/login_session.php';
    include('../../../sw-library/sw-function.php');

    $aColumns = ['nis', 'nama_siswa', 'nama_kelas', 'nama_jurusan', 'jumlah_absen'];
    $sIndexColumn = "nis";
    $sTable = "student";
    $gaSql['user'] = DB_USER;
    $gaSql['password'] = DB_PASSWD;
    $gaSql['db'] = DB_NAME;
    $gaSql['server'] = DB_HOST;

    $gaSql['link'] = new mysqli($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db']);

    // Ambil bulan dan tahun dari parameter GET
    $month = isset($_GET['month']) ? mysqli_real_escape_string($gaSql['link'], $_GET['month']) : date("m");
    $year = isset($_GET['year']) ? mysqli_real_escape_string($gaSql['link'], $_GET['year']) : date("Y");

    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayStart']) . ", " .
            mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayLength']);
    }

    $sOrder = "ORDER BY nis DESC";
    if (isset($_GET['iSortCol_0'])) {
        for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
            if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . " " . mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_' . $i]) . ", ";
            }
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
        $sWhere = "WHERE (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }

    // Query untuk mengambil data siswa dan jumlah absensi
    $sQuery = "SELECT student.nis, student.nama_siswa, class.nama_kelas, major.nama_jurusan,
                COUNT(presence_student.presence_id) AS jumlah_absen
                FROM student
                LEFT JOIN class ON student.id_kelas = class.id_kelas
                LEFT JOIN major ON class.id_jurusan = major.id_jurusan
                LEFT JOIN presence_student ON student.nis = presence_student.nis 
                AND MONTH(presence_student.presence_date) = '$month' 
                AND YEAR(presence_student.presence_date) = '$year'
                $sWhere
                GROUP BY student.nis
                $sOrder
                $sLimit";

    $rResult = mysqli_query($gaSql['link'], $sQuery);

    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    $sQuery = "SELECT COUNT(" . $sIndexColumn . ") FROM student";
    $rResultTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultTotal = mysqli_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

    $output = array(
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    $no = 0;
    while ($aRow = mysqli_fetch_array($rResult)) {
        $no++;
        $row = array();
        $row[] = '<div class="text-center">' . $no . '</div>';
        $row[] = strip_tags($aRow['nis']);
        $row[] = strip_tags($aRow['nama_siswa']);
        $row[] = strip_tags($aRow['nama_kelas'] . ' ' . $aRow['nama_jurusan']);
        $row[] = '<div class="text-center"><span class="label label-success">' . $aRow['jumlah_absen'] . '</span></div>';
        $row[] = '<div class="text-center">
                   <a href="./absensi-siswa&op=views&nis=' . epm_encode($aRow['nis']) . '" class="btn btn-warning btn-sm enable-tooltip" title="Detail"><i class="fa fa-eye" aria-hidden="true"></i> Detail</a>
                  </div>';

        $output['aaData'][] = $row;
    }
    echo json_encode($output);
}
?>

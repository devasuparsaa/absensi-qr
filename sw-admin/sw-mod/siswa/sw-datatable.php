<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else{
require_once '../../../sw-library/sw-config.php';
require_once '../../login/login_session.php';
include('../../../sw-library/sw-function.php');

    $aColumns = ['qr_code','nis','nama_siswa','nisn','building_id','id_kelas','jenis_kelamin','agama','no_hp','tahun_ajaran','photo'];
    $sIndexColumn = "nis";
    $sTable = "student";
    $gaSql['user'] = DB_USER;
    $gaSql['password'] = DB_PASSWD;
    $gaSql['db'] = DB_NAME;
    $gaSql['server'] = DB_HOST;

    $gaSql['link'] =  new mysqli($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db']);
    if ($gaSql['link']->connect_errno) {
        die('Could not connect: ' . $gaSql['link']->connect_error);
    }
    $connection = $gaSql['link'];
    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
    {
        $sLimit = "LIMIT ".mysqli_real_escape_string($connection, $_GET['iDisplayStart']).", ".
            mysqli_real_escape_string($connection, $_GET['iDisplayLength']);
    }

    $sOrder = "ORDER BY nis DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY nis DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($connection, $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY nis DESC")
        {
            $sOrder = "ORDER BY nis DESC";
        }
    }

    $sWhere = "";
    if (isset($_GET['sSearch']) && $_GET['sSearch'] != "")
    {
        $sWhere = "WHERE (";
        for ($i=0; $i<count($aColumns); $i++)
        {
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch'])."%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }

    for ($i=0 ; $i<count($aColumns); $i++)
    {
        if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
        {
            if ($sWhere == "")
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_'.$i])."%' ";
        }
    }

    $sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM $sTable
        $sWhere
        $sOrder
        $sLimit ";
    $rResult = mysqli_query($gaSql['link'], $sQuery);

    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    $sQuery = "SELECT COUNT(".$sIndexColumn.") FROM   $sTable";
    $rResultTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultTotal = mysqli_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

    $output = array( 
       // "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    $no = 0;
    while ($aRow = mysqli_fetch_array($rResult)){$no++;
      extract($aRow);
        $row = array();

        $query_kelas  ="SELECT nama_kelas FROM class WHERE id_kelas='$aRow[id_kelas]'";
        $result_kelas = mysqli_query($connection, $query_kelas);
        $row_kelas    = mysqli_fetch_assoc($result_kelas);
        $query_building  ="SELECT name FROM building WHERE building_id='$aRow[building_id]'";
        $result_building = mysqli_query($connection, $query_building);
        $row_building    = mysqli_fetch_assoc($result_building);

        if($level_user==1){
        $button ='
        <a href="siswa&op=edit&nis='.epm_encode($aRow['nis']).'" class="btn btn-warning btn-sm enable-tooltip" title="Edit"><i class="fa fa-pencil-square-o"></i></a>
        <button data-id="'.($aRow['nis']).'" class="btn btn-sm btn-danger delete" title="Hapus"><i class="fa fa-trash-o"></i></button>';
        }else{
        $button='
        <button type="button" class="btn btn-warning btn-sm access-failed enable-tooltip" title="Edit"><i class="fa fa-pencil-square-o"></i></button>
        <button type="button" class="btn btn-sm btn-danger access-failed" title="Hapus"><i class="fa fa-trash-o"></i></button>';
        }

        for ($i=1 ; $i<count($aColumns) ; $i++){
            
            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = '<div class="text-center">
                        <img src="../../../sw-content/siswa-code-qr/'.$aRow['qr_code'].'" class="img" style="width:50px;height:50px;">
                      </div>';

            $row[] = strip_tags($aRow['nis']);
            $row[] = strip_tags($aRow['nama_siswa']);
            $row[] = strip_tags($aRow['nisn']);
            $row[] = strip_tags($row_kelas['nama_kelas']);
            $row[] = strip_tags($aRow['jenis_kelamin']);
            $row[] = strip_tags($aRow['no_hp']);
            $row[] = '<div class="text-center">
                       '.$button.'
                      </div>';
        }
        $output['aaData'][] = $row;
        $no++;
    }
    echo json_encode($output);
  
}
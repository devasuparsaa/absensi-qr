$(document).ready(function() {
    // Inisialisasi DataTable
    $('#swdatatable').dataTable({
        "iDisplayLength": 31,
        "aLengthMenu": [[31, 50, 100, -1], [31, 50, 100, "All"]],
    });

    function loading() {
        $(".loading").show();
        $(".loading").delay(1500).fadeOut(500);
    }

    // Fungsi untuk memuat data siswa
    function loadData_Siswa() {
        $('#sw-datatable').dataTable({
            "bProcessing": true,
            "bServerSide": false,
            "bAutoWidth": true,
            "bSort": false,
            "bStateSave": true,
            "bDestroy": true,
            "iDisplayLength": 25,
            "aLengthMenu": [
                [25, 30, 50, -1],
                [25, 30, 50, "All"]
            ],
            "sAjaxSource": "sw-mod/absensi-siswa/sw-datatable.php?month=" + $('.month').val() + "&year=" + $('.year').val(),
            "aoColumns": [null, null, null, null, null, null],
        });
    }

    loadData_Siswa();

    // Fungsi untuk memuat data absensi
    function loadData() {
        var nis = $('.id').val(); // Changed from $('.nis').val() to $('.id').val() to match the input field class in absensi-siswa.php
        if (!nis) {
            alert('NIS tidak ditemukan. Silakan pilih siswa terlebih dahulu.');
            return;
        }
        $.ajax({
            url: 'sw-mod/absensi-siswa/proses.php?action=absensi-siswa&nis=' + nis,
            type: 'POST',
            data: {
                month: $('.month').val(),
                year: $('.year').val()
            },
            success: function(data) {
                $('.loaddata').html(data);
            }
        });
    }

    // Tombol clear
    $('.btn-clear').click(function(e) {
        loadData();
        $('.month').val('');
        $('.year').val('');
    });

    // Tombol sortir
    $('.btn-sortir').click(function(e) {
        var month = $('.month').val();
        var year = $('.year').val();
        loadData();
    });

    // Fungsi untuk mencetak
    $('.btn-print').click(function(e) {
        var nis = $('.nis').val();
        var month = $('.month').val();
        var year = $('.year').val();
        var type = $(this).attr("data-nis");

        var url = "./absensi-siswa/print?action=" + type + "&nis=" + nis;
        if (month) {
            url += "&from=" + month + "&to=" + year;
        }
        window.open(url, '_blank');
    });

    // Fungsi untuk mencetak semua
    $('.btn-print-all').click(function(e) {
        var siswa = $('.siswa').val();
        var month = $('.month').val();
        var year = $('.year').val();
        var type = $('.type').val();
        var url = "./absensi-siswa/print?action=allexcel&siswa=" + siswa + "&from=" + month + "&to=" + year;
        window.open(url, '_blank');
    });

    // // Modal untuk lokasi
    $(document).on('click', '.btn-modal', function() {
        $('#modal-location').modal();
        var latitude = $(this).attr("data-latitude");
        var longitude = $(this).attr("data-longitude");
        var name = $('.nama_siswa').html();
        $(".modal-title-name").html(name);
        document.getElementById("iframe-map").innerHTML = '<iframe src="sw-mod/absensi-siswa/map.php?latitude=' + latitude + '&longitude=' + longitude + '&name=' + name + '" frameborder="0" width="100%" height="400px" marginwidth="0" marginheight="0" scrolling="no">';
    });
});

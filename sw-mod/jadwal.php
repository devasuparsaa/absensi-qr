<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include 'sw-mod/sw-assests/css/stylejadwal.css' ;
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
        setcookie('COOKIES_MEMBER', '', 0, '/');
        setcookie('COOKIES_COOKIES', '', 0, '/');
        // Login tidak ditemukan
        setcookie("COOKIES_MEMBER", "", time()-$expired_cookie);
        setcookie("COOKIES_COOKIES", "", time()-$expired_cookie);
        session_destroy();
        header("location:./");
}else{
    echo'
        <!-- Upper Section -->
        <div class="upper-section bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-blue-800 mb-4 md:mb-0">Jadwal Pelajaran</h1>
                </div>
                <div id="classDescriptions" class="p-4 bg-blue-50 rounded">
                    <div id="X-MM-desc" class="p-4 bg-blue-50 rounded mb-4">
                        <h2 class="text-xl font-semibold mb-2 text-blue-700">Jadwal untuk Pengajar</h2>
                        <p class="text-gray-700">Diharapkan pengajar agar hadir didalam Kelas 15 Menit sebelum Jam dimulai.</p>
                    </div>
                </div>
        </div>

        <!-- Lower Section -->
        <div class="lower-section bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-blue-800" id="scheduleTitle">Jadwal Kelas</h2>
                <button id="addScheduleBtn" class=""></button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="w-1/6 p-3 border">Hari/Kelas</th>
                            <th class="w-1/6 hour-header p-3 border">Jam ke-1</th>
                            <th class="w-1/6 hour-header p-3 border">Jam ke-2</th>
                            <th class="w-1/6 hour-header p-3 border">Jam ke-3</th>
                            <th class="w-1/6 hour-header p-3 border">Jam ke-4</th>
                            <th class="w-1/6 hour-header p-3 border">Jam ke-5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="day-header p-3 border">Senin</td>
                            <td class="schedule-cell p-0 border" data-day="senin" data-hour="1"></td>
                            <td class="schedule-cell p-0 border" data-day="senin" data-hour="2"></td>
                            <td class="schedule-cell p-0 border" data-day="senin" data-hour="3"></td>
                            <td class="schedule-cell p-0 border" data-day="senin" data-hour="4"></td>
                            <td class="schedule-cell p-0 border" data-day="senin" data-hour="5"></td>
                        </tr>
                        <tr>
                            <td class="day-header p-3 border">Selasa</td>
                            <td class="schedule-cell p-0 border" data-day="selasa" data-hour="1"></td>
                            <td class="schedule-cell p-0 border" data-day="selasa" data-hour="2"></td>
                            <td class="schedule-cell p-0 border" data-day="selasa" data-hour="3"></td>
                            <td class="schedule-cell p-0 border" data-day="selasa" data-hour="4"></td>
                            <td class="schedule-cell p-0 border" data-day="selasa" data-hour="5"></td>
                        </tr>
                        <tr>
                            <td class="day-header p-3 border">Rabu</td>
                            <td class="schedule-cell p-0 border" data-day="rabu" data-hour="1"></td>
                            <td class="schedule-cell p-0 border" data-day="rabu" data-hour="2"></td>
                            <td class="schedule-cell p-0 border" data-day="rabu" data-hour="3"></td>
                            <td class="schedule-cell p-0 border" data-day="rabu" data-hour="4"></td>
                            <td class="schedule-cell p-0 border" data-day="rabu" data-hour="5"></td>
                        </tr>
                        <tr>
                            <td class="day-header p-3 border">Kamis</td>
                            <td class="schedule-cell p-0 border" data-day="kamis" data-hour="1"></td>
                            <td class="schedule-cell p-0 border" data-day="kamis" data-hour="2"></td>
                            <td class="schedule-cell p-0 border" data-day="kamis" data-hour="3"></td>
                            <td class="schedule-cell p-0 border" data-day="kamis" data-hour="4"></td>
                            <td class="schedule-cell p-0 border" data-day="kamis" data-hour="5"></td>
                        </tr>
                        <tr>
                            <td class="day-header p-3 border">Jumat</td>
                            <td class="schedule-cell p-0 border" data-day="jumat" data-hour="1"></td>
                            <td class="schedule-cell p-0 border" data-day="jumat" data-hour="2"></td>
                            <td class="schedule-cell p-0 border" data-day="jumat" data-hour="3"></td>
                            <td class="schedule-cell p-0 border" data-day="jumat" data-hour="4"></td>
                            <td class="schedule-cell p-0 border" data-day="jumat" data-hour="5"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Modal for Adding Schedule -->
    <div id="addScheduleModal" class="modal-overlay">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-blue-800">Tambah Jadwal</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="scheduleForm">
                <input type="hidden" id="selectedDay">
                <input type="hidden" id="selectedHour">
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <input type="text" id="subject" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="teachingTime" class="block text-sm font-medium text-gray-700 mb-1">Jam Mengajar</label>
                    <input type="time" id="teachingTime" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="classInput" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <input type="text" id="classInput" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kelas">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                    Simpan Jadwal
                </button>
            </form>
        </div>
    </div>';
    }
    include 'sw-mod/sw-assets/js/scriptjadwal.js';
  include_once 'sw-mod/sw-footer.php';
} ?>
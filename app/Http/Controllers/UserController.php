<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kalender_Sekolah;
use App\Models\Model_data_siswa\Jadwal_pelajaran;
use App\Models\Model_data_siswa\Kelas;
use App\Models\Model_data_siswa\semester;
use App\Models\Model_data_siswa\tahun_ajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request) {

        Log::info('Accessing index method of JadwalController', ['id_kelas' => $request->input('id_kelas')]);
        // Ambil tahun sekarang
        $tahunSekarang = date('Y');

        // Ambil semua record dari model Kalender_Sekolah
        $kalenderSekolah = Kalender_Sekolah::all();

        // Ambil data tahun ajaran yang sesuai dengan tahun sekarang atau lebih
        $tahunAjaran = tahun_ajaran::where('tahun_ajaran', '>=', $tahunSekarang)->get();

        // Ambil semua data semester
        $semester = semester::all();

        $clases = Kelas::all();
        $idKelas = $request->input('id_kelas');
        if ($idKelas) {
            $jadwal = Jadwal_pelajaran::where('id_kelas', $idKelas)->with('mapel', 'guru')->get();
            Log::info('Fetching schedule for class', ['id_kelas' => $idKelas]);
        } else {
            $jadwal = Jadwal_pelajaran::all();
            Log::info('Fetching all schedules');
        }
        // Pass data ke view
        return view('pages.user.dashboard', [
            'kalenderSekolah' => $kalenderSekolah,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $semester,
            'clases' => $clases,
            'jadwal' => $jadwal,
        ]);
    }
    public function show() {
     // Mendapatkan informasi akun pengguna yang sedang login
            $user = Auth::user();
        
            // Log informasi pengguna yang sedang login
            Log::info('Logged in user information:', ['user' => $user]);
        
            // Memeriksa jika $user tidak null
            if ($user) {
                $email = $user->email;
                $guruId = $user->id_user;
        
                // Log email dan id_user dari pengguna
                Log::info('User email:', ['email' => $email]);
                Log::info('User ID:', ['guru_id' => $guruId]);
        
                // Ambil data Guru berdasarkan email
                $guru = Guru::where('id', $guruId)->first();
        
                // Log data Guru yang ditemukan
                if ($guru) {
                    Log::info('Guru data found:', ['guru' => $guru]);
                } else {
                    Log::info('No Guru data found for email:', ['email' => $email]);
                }
        
                // Mengirimkan data akun dan data guru ke view
                return view('pages.user.show-akun', [
                    'user' => $user,
                    'guru' => $guru, // Mengirimkan data Guru ke view
                ]);
            } else {
                // Log jika pengguna tidak ditemukan
                Log::warning('User not found during account retrieval.');
        
                // Menangani kasus jika pengguna tidak ditemukan
                return redirect()->route('login')->withErrors('User not found.');
            }
        }

}

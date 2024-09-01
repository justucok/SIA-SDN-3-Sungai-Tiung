<?php

namespace App\Http\Controllers;

use App\Models\Model_data_siswa\Extrakulikuler;
use App\Models\Model_data_siswa\Siswa;
use App\Models\Model_raport\raport_ekstrakulikuler_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EkskulController extends Controller
{
    public function index() {
        $ekskul = Extrakulikuler::all();
        return view('pages.admin.ekskul',[
            'ekskul' => $ekskul,
        ]);
    }
    public function create()
    {
        return view('pages.admin.add-ekskul');
    }
    public function store(Request $request)
    {
        $request->validate([
            'ekstrakulikuler' => 'required|string|max:255',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        // Log validated data
        Log::info('Validated data for storing mata pelajaran:', $request->all());

        try {
            $ekskul = Extrakulikuler::create([
                'ekstrakulikuler' => $request->input('ekstrakulikuler'),
                'hari' => $request->input('hari'),
                'jam_mulai' => $request->input('jam_mulai'),
                'jam_selesai' => $request->input('jam_selesai'),
            ]);

            // Log success message
            Log::info('Mata pelajaran berhasil ditambahkan:', $ekskul->toArray());

            return redirect(route('index.ekskul'))->with('success_create', 'Data Ekstrakulikuer  berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error message
            Log::error('Gagal menambahkan data mata pelajaran: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error_create', 'Gagal menambahkan data Ekstrakulikuer. Silakan coba lagi.');
        }
    }

    public function show(Extrakulikuler $ekskul)
    {
        return view("pages.admin.edit-ekskul", ['ekskul' => $ekskul]);
    }

      
    public function siswaShow($id)
{
    // Ambil data ekstrakurikuler berdasarkan ID
    $namekskul = Extrakulikuler::findOrFail($id);
    
    // Ambil data ekstrakurikuler siswa
    $ekskul = raport_ekstrakulikuler_siswa::where('id_ekstrakulikuler', $id)
        ->with('siswa', 'ekstrakulikuler')
        ->get();
    
    // Filter siswa berdasarkan id_kelas dari ekstrakurikuler
    $uniqueEkskulArray = $ekskul->filter(function ($item) {
        return $item->siswa->id_kelas_now == $item->id_kelas; // Memastikan id_kelas siswa sesuai
    })->unique(function ($item) {
        return $item->siswa->id; // Menggunakan id_siswa untuk mengecek duplikat
    })->values(); // Mengonversi koleksi yang unik menjadi array

    return view("pages.admin.show-ekskul-siswa", compact('uniqueEkskulArray', 'namekskul', 'id'));
}

    
    
    public function delete($id)
    {
        try {
            // Temukan guru berdasarkan ID, jika tidak ada, akan memunculkan error 404
            $ekskul = Extrakulikuler::findOrFail($id);
    
            // Log ID guru yang akan dihapus
            Log::info('Menghapus Ekskul dengan ID: ' . $id);
    
            // Cek apakah guru ini merupakan wali kelas
            $raport = raport_ekstrakulikuler_siswa::where('id_ekstrakulikuler', $ekskul->id)->get();
    
            if ($raport->isNotEmpty()) {
                foreach ($raport as $rpt) {
                    Log::info('Menghapus relasi kelas dengan ID Ekskul: ' . $rpt->id . ' yang memiliki id_ekskul: ' . $ekskul->id);
                   
                    $rpt->id_mapel = null;
                    $rpt->save();
                }
            }
    
           
            $ekskul->delete();
            
             
             Log::info('Berhasil menghapus mapel dengan ID: ' . $id);
    
            return redirect(route('index.ekskul'))->with('success_delete', 'mapel berhasil dihapus!');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error menghapus mapel dengan ID: ' . $id . '. Pesan error: ' . $e->getMessage());
    
            return redirect(route('index.ekskul'))->with('error_delete', 'Terjadi kesalahan saat menghapus guru. Silakan coba lagi.');
        }   
    }
}

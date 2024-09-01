<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Model_data_siswa\Jadwal_pelajaran;
use App\Models\Model_data_siswa\Kelas;
use App\Models\Model_data_siswa\Mata_pelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\Input;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Accessing index method of JadwalController', ['id_kelas' => $request->input('id_kelas')]);

        $clases = Kelas::all();
        $idKelas = $request->input('id_kelas');
        if ($idKelas) {
            $jadwal = Jadwal_pelajaran::where('id_kelas', $idKelas)->with('mapel', 'guru')->get();
            Log::info('Fetching schedule for class', ['id_kelas' => $idKelas]);
        } else {
            $jadwal = Jadwal_pelajaran::all();
            Log::info('Fetching all schedules');
        }
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $sortedJadwal = $jadwal->sortBy(function($item) use ($days) {
            return array_search($item->hari, $days);
        });

        return view('pages.admin.jadwal-pelajaran', compact('sortedJadwal', 'clases', 'idKelas'));
    }

    public function create(Request $request)
    {
        Log::info('Accessing create method of JadwalController', ['id_kelas' => $request->input('id_kelas')]);

        $id_kelas = $request->input('id_kelas');
        $mapel = Mata_pelajaran::all();
        $gurus = Guru::all();

        Log::info('Fetched data for creating schedule', ['id_kelas' => $id_kelas]);

        return view('pages.admin.add-jadwal-pelajaran', compact('mapel', 'gurus', 'id_kelas'));
    }

    public function store(Request $request)
    {
        Log::info('Data yang divalidasi: ', $request->all());

        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'id_mapel' => 'required|exists:mata_pelajarans,id',
            'id_guru' => 'required|exists:tbl_guru,id',
            'hari' => 'required|string|in:' . implode(',', Jadwal_pelajaran::$enumHari),
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        // Cek apakah sudah ada jadwal yang sama di database
        $existingJadwal = Jadwal_pelajaran::where([
            ['id_kelas', '=', $validated['id_kelas']],
            ['id_mapel', '=', $validated['id_mapel']],
            ['hari', '=', $validated['hari']],
            ['jam_mulai', '=', $validated['jam_mulai']],
            ['jam_selesai', '=', $validated['jam_selesai']],
        ])->first();

        // Cek apakah ada jadwal dengan rentang waktu yang tumpang tindih
        $overlappingJadwal = Jadwal_pelajaran::where('id_kelas', $validated['id_kelas'])
            ->where('hari', $validated['hari'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($query) use ($validated) {
                        $query->where('jam_mulai', '<=', $validated['jam_mulai'])
                            ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })
            ->first();

        if ($existingJadwal) {
            Log::warning('Jadwal yang sama sudah ada di database.', [
                'id_kelas' => $validated['id_kelas'],
                'id_mapel' => $validated['id_mapel'],
                'hari' => $validated['hari'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
            ]);

            return redirect()->back()->withErrors('Jadwal dengan detail yang sama sudah ada. Silakan periksa kembali.')->withInput();
        } elseif ($overlappingJadwal) {
            Log::warning('Rentang waktu yang tumpang tindih sudah ada di database.', [
                'id_kelas' => $validated['id_kelas'],
                'hari' => $validated['hari'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
            ]);

            return redirect()->back()->withErrors('Jadwal baru tumpang tindih dengan jadwal yang ada. Silakan periksa kembali.')->withInput();
        }

        // Jika tidak ada jadwal yang sama atau tumpang tindih, buat jadwal baru
        $jadwal = Jadwal_pelajaran::create([
            'id_kelas' => $validated['id_kelas'],
            'id_mapel' => $validated['id_mapel'],
            'id_guru' => $validated['id_guru'],
            'hari' => $validated['hari'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
        ]);

        Log::info('Jadwal baru berhasil dibuat: ', $jadwal->toArray());

        return redirect()->route('index.jadwal', ['id_kelas' => $validated['id_kelas']])
            ->with('success', 'Jadwal Pelajaran berhasil ditambahkan');
    }

    public function destroy($id)
{
    // Temukan jadwal berdasarkan ID
    $jadwal = Jadwal_pelajaran::find($id);

    $kelas = $jadwal->id_kelas;

    if (!$jadwal) {
        return redirect()->back()->withErrors('Jadwal tidak ditemukan.');
    }

    // Hapus jadwal
    $jadwal->delete();

    // Redirect dengan pesan sukses
    return redirect()->route('index.jadwal',  ['id_kelas' => $kelas])
        ->with('success_delete', 'Jadwal Pelajaran berhasil dihapus.');
}

}

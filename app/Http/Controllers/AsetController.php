<?php

namespace App\Http\Controllers;

use App\Models\Model_laporan\Inventaris_barang;
use App\Models\SaldoKeuangan;
use App\Models\StandartHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsetController extends Controller
{
    public function index()
    {
        $search = request()->input('search'); 
    
        // Start building the query with the relationship loaded
        $asetQuery = Inventaris_barang::with('barang');
    
        // Apply search filters if the search input is provided
        if ($search) {
            $asetQuery->where(function ($query) use ($search) {
                // Search on columns in the Inventaris_barang table
                $query->where('dana_pembelian', 'like', '%' . $search . '%')
                      ->orWhere('jumlah', 'like', '%' . $search . '%')
                      ->orWhere('tgl_pembelian', 'like', '%' . $search . '%')
                      ->orWhereHas('barang', function ($query) use ($search) {
                          // Search on columns in the related barang table
                          $query->where('nama_barang', 'like', '%' . $search . '%')
                                ->orWhere('kode', 'like', '%' . $search . '%');
                      });
            });
        }
    
        // Execute the query and get the results
        $inventaris = $asetQuery->get();
    
        // Log the retrieved data
        Log::info('Request data received for index method', ['inventaris' => $inventaris]);
    
        // Return the view with the retrieved data
        return view('pages.admin.inventaris', [
            'inventaris' => $inventaris,
        ]);
    }
    
    public function indekSatuanHarga(Request $request)
    {
        $search = $request->input('search');
    
        // Build query for searching or retrieving all data
        $query = StandartHarga::query();
    
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nama_barang', 'LIKE', "%{$search}%")
                      ->orWhere('kode', 'LIKE', "%{$search}%")
                      ->orWhere('harga_satuan', 'LIKE', "%{$search}%");
            });
        }
    
        $harga = $query->get();
    
        // Return view with data
        return view('pages.admin.satuan-harga', compact('harga'));
    }
    
    public function createharga(Request $request)
    {
        Log::info('Request data received for createharga:', $request->all());


        $validatedData = $request->validate([
            'kode_barang' => 'required|string|max:255',
            'nama_barang' =>'required|string|max:255',
            'harga_satuan' => 'required|numeric',
        ]);

        Log::info('Validated data:', $validatedData);




        $jumlahBeli = $request->input('jumlah_beli') ?? 0;

        Log::info('Jumlah beli:', ['jumlah_beli' => $jumlahBeli]);


        $totalHarga = $request->input('harga_satuan') * $jumlahBeli;


        Log::info('Total harga calculated:', ['total_harga' => $totalHarga]);


        $standartHarga = StandartHarga::create([
            'kode' => $request->input('kode_barang'),
            'nama_barang' => $request->input('nama_barang'),
            'harga_satuan' => $request->input('harga_satuan'),
            'jumlah_beli' => $jumlahBeli,
            'total_harga' => $totalHarga,
        ]);

        Log::info('StandartHarga record created:', $standartHarga->toArray());

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success_create', 'Data berhasil ditambahkan');
    }


    public function create()
    {
        $barangs = StandartHarga::all();

        return view('pages.admin.add-aset', [
            'barangs' => $barangs,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the input from the request
        $validated = $request->validate([
            'tgl_pembelian' => 'required|date',
            'dana_pembelian' => 'required|string|in:' . implode(',', Inventaris_barang::$enumDana),
            'kode_barang' => 'required|exists:standart_hargas,id',
            'jumlah' => 'required|integer|max:11',
            'kondisi' => 'required|string|in:' . implode(',', Inventaris_barang::$enumKondisi),
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
        ]);
    
        Log::info('Validated data:', $validated);
    
        try {
            // Fetch StandartHarga based on kode_barang
            $harga = StandartHarga::find($validated['kode_barang']);
    
            if ($harga) {
                // Calculate total price
                $hargaSatuan = $harga->harga_satuan;
                $jumlahnow = $harga->jumlah_beli;
                $totalHarga = $harga->total_harga;
                $totalHargaitem = $hargaSatuan * $validated['jumlah'];
    
                // Update jumlah_beli and total_harga in StandartHarga
                $jumlahupdate = $jumlahnow + $validated['jumlah'];
                $harga->jumlah_beli = $jumlahupdate;
    
                $totalHargaupdate = $totalHarga + $totalHargaitem;
                $harga->total_harga = $totalHargaupdate;
                $harga->save();
    
                Log::info('Updated StandartHarga:', $harga->toArray());
            } else {
                Log::warning('StandartHarga not found for kode_barang:', ['kode_barang' => $validated['kode_barang']]);
                throw new \Exception('StandartHarga not found.');
            }
    
            // Create or update Inventaris_barang
            $aset = Inventaris_barang::create([
                'tgl_pembelian' => $validated['tgl_pembelian'],
                'dana_pembelian' => $validated['dana_pembelian'],
                'id_barang' => $validated['kode_barang'],
                'jumlah' => $validated['jumlah'],
                'kondisi' => $validated['kondisi'],
                'lokasi' => $validated['lokasi'],
                'total_biaya' => $totalHargaitem ?? null, 
                'keterangan' => $validated['keterangan'],
            ]);
    
            // Log the newly created asset
            Log::info('Aset created:', $aset->toArray());
    
            // Handle SaldoKeuangan
            $saldo = SaldoKeuangan::first();
            if (!$saldo) {
                // Create a new saldo if not found
                $saldo = SaldoKeuangan::create([
                    'Saldo_semua' => 0,
                    'Saldo_bos' => 0,
                    'Saldo_lain' => 0,
                ]);
            }
    
            // Update saldo based on transaction type and funds
            if ($validated['dana_pembelian'] == 'Dana Bos') {
                $saldo->Saldo_bos -= $totalHargaitem;
            } else {
                $saldo->Saldo_lain -= $totalHargaitem;
            }
    
            $saldo->Saldo_semua -= $totalHargaitem;
            $saldo->save();
    
            // Redirect with success message
            return redirect(route('index.inventaris'))->with('success_create', 'Aset berhasil ditambahkan!!');
    
        } catch (\Exception $e) {
            // Log error details
            Log::error('Failed to store Inventaris_barang:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
    
            // Redirect with error message
            return redirect()->route('index.inventaris')->with('error_create', 'Terjadi kesalahan saat menambahkan aset. Silakan coba lagi.');
        }
    }
    


    public function show_aset()
    {
        return view("pages.admin.edit-aset");
    }
    public function edit($id)
    {
        $aset = Inventaris_barang::findOrFail($id);
        $barangs = StandartHarga::all();

        return view('pages.admin.edit-aset', compact('aset', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data dari request
        $validated = $request->validate([
            'tgl_pembelian' => 'required|date',
            'dana_pembelian' => 'required|string|in:' . implode(',', Inventaris_barang::$enumDana),
            'kode_barang' => 'required|exists:standart_hargas,id',
            'jumlah' => 'required|integer|max:11',
            'kondisi' => 'required|string|in:' . implode(',', Inventaris_barang::$enumKondisi),
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
        ]);

        // Log data yang tervalidasi
        Log::info('Validated data:', $validated);

        // Temukan aset berdasarkan ID
        $aset = Inventaris_barang::findOrFail($id);
        $originalData = $aset->getOriginal(); // Data sebelum diupdate

        // Cek apakah kode_barang berubah
        $isKodeBarangChanged = $originalData['id_barang'] !== $validated['kode_barang'];

        if ($isKodeBarangChanged) {
            // Update StandartHarga untuk kode_barang lama
            $oldBarang = StandartHarga::find($originalData['id_barang']);
            if ($oldBarang) {
                $hargaSatuanOld = $oldBarang->harga_satuan;
                $totalHargaOld = $oldBarang->total_harga;
                $jumlahOld = $oldBarang->jumlah_beli;

                $totalHargaOldUpdate = $totalHargaOld - ($hargaSatuanOld * $originalData['jumlah']);
                $jumlahOldUpdate = $jumlahOld - $originalData['jumlah'];

                $oldBarang->update([
                    'jumlah_beli' => $jumlahOldUpdate,
                    'total_harga' => $totalHargaOldUpdate,
                ]);

                $saldo = SaldoKeuangan::first();
                
                if (!$saldo) {
                    // Jika tidak ada saldo, buat baru
                    $saldo = SaldoKeuangan::create([
                        'Saldo_semua' => 0,
                        'Saldo_bos' => 0,
                        'Saldo_lain' => 0,
                    ]);
                }
         
                // Update saldo berdasarkan jenis transaksi dan dana
                if ($validated['dana_pembelian'] == 'Dana Bos') {
                    $saldo->Saldo_bos += $totalHargaOldUpdate;
                } else {
                    $saldo->Saldo_lain += $totalHargaOldUpdate;
                }
            
                $saldo->Saldo_semua += $totalHargaOldUpdate;
         
                $saldo->save();

                Log::info('Updated StandartHarga for old kode_barang:', $oldBarang->toArray());
            }

            // Update StandartHarga untuk kode_barang baru
            $newBarang = StandartHarga::find($validated['kode_barang']);
            if ($newBarang) {
                $hargaSatuanNew = $newBarang->harga_satuan;
                $totalHargaNew = $newBarang->total_harga;
                $jumlahNew = $newBarang->jumlah_beli;

                $totalHargaNewUpdate = $totalHargaNew + ($hargaSatuanNew * $validated['jumlah']);
                $jumlahNewUpdate = $jumlahNew + $validated['jumlah'];

                $newBarang->update([
                    'jumlah_beli' => $jumlahNewUpdate,
                    'total_harga' => $totalHargaNewUpdate,
                ]);

                $saldo = SaldoKeuangan::first();
                if (!$saldo) {
                    // Jika tidak ada saldo, buat baru
                    $saldo = SaldoKeuangan::create([
                        'Saldo_semua' => 0,
                        'Saldo_bos' => 0,
                        'Saldo_lain' => 0,
                    ]);
                }
         
                // Update saldo berdasarkan jenis transaksi dan dana
                if ($validated['dana_pembelian'] == 'Dana Bos') {
                    $saldo->Saldo_bos -= $totalHargaNewUpdate;
                } else {
                    $saldo->Saldo_lain -= $totalHargaNewUpdate;
                }
            
                $saldo->Saldo_semua -= $totalHargaNewUpdate;
         
                $saldo->save();

                Log::info('Updated StandartHarga for new kode_barang:', $newBarang->toArray());
            } else {
                Log::warning('StandartHarga not found for new kode_barang:', ['kode_barang' => $validated['kode_barang']]);
            }
        } else {
            // Jika kode_barang tidak berubah, hanya update jumlah dan total harga
            $harga = StandartHarga::find($validated['kode_barang']);
            if ($harga) {
                $hargaSatuan = $harga->harga_satuan;
                $totalHargaOld = $harga->total_harga;
                $jumlahOld = $harga->jumlah_beli;

                $totalHargaUpdate = $totalHargaOld - ($hargaSatuan * $originalData['jumlah']) + ($hargaSatuan * $validated['jumlah']);
                $jumlahUpdate = $jumlahOld - $originalData['jumlah'] + $validated['jumlah'];

                $harga->update([
                    'jumlah_beli' => $jumlahUpdate,
                    'total_harga' => $totalHargaUpdate,
                ]);

                Log::info('Updated StandartHarga for unchanged kode_barang:', $harga->toArray());
            } else {
                Log::warning('StandartHarga not found for kode_barang:', ['kode_barang' => $validated['kode_barang']]);
            }
        }
        

        
        // Update data aset
        $aset->update($validated);

        // Log perubahan data
        Log::info('Aset updated', [
            'original' => $originalData,
            'changed' => $aset->getChanges(),
            'updated_by' => auth()->user()->id,
        ]);

        return redirect()->route('index.inventaris')->with('success_update', 'Item updated successfully.');
    }



    public function destroy($id)
    {
        // Temukan aset berdasarkan ID
        $aset = Inventaris_barang::findOrFail($id);

        // Temukan StandartHarga terkait dengan kode_barang
        $barang = StandartHarga::find($aset->id_barang);

        if ($barang) {
            // Hitung total harga yang akan dikurangi
            $hargaSatuan = $barang->harga_satuan;
            $totalHargaDihapus = $hargaSatuan * $aset->jumlah;
            $jumlahBeliDihapus = $barang->jumlah_beli - $aset->jumlah;
            $totalHargaBaru = $barang->total_harga - $totalHargaDihapus;

            // Perbarui StandartHarga
            $barang->update([
                'jumlah_beli' => $jumlahBeliDihapus,
                'total_harga' => $totalHargaBaru,
            ]);

            Log::info('Updated StandartHarga after deletion:', $barang->toArray());
        } else {
            Log::warning('StandartHarga not found for kode_barang:', ['kode_barang' => $aset->id_barang]);
        }

        // Hapus aset
        $aset->delete();

        // Log penghapusan
        Log::info('Aset deleted:', ['id' => $id]);

        return redirect(route('index.inventaris'))->with('success_delete', 'Aset berhasil dihapus!!');
    }
}

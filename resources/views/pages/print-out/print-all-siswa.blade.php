@extends('component.print-header')

@section('content')
    <style>
        @media print {
            .no-print {
                display: none;
            }
            /* Tambahkan gaya CSS lain jika diperlukan */
        }
    </style>

    <div class="flex flex-col justify-center">
        <h2 class="mb-4 text-2xl font-extrabold tracking-tight leading-none text-gray-900">
            LAPORAN DATA SISWA
        </h2>
        <!-- table 1 -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">NISN</th>
                        <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-3">Jenis Kelamin</th>
                        <th scope="col" class="px-6 py-3">Tempat Lahir</th>
                        <th scope="col" class="px-6 py-3">Tanggal Lahir</th>
                        <th scope="col" class="px-6 py-3">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allSiswa as $item)
                        <tr class="odd:bg-white even:bg-gray-50 border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $item->nisn }}
                            </th>
                            <td class="px-6 py-4">{{ $item->nama_lengkap }}</td>
                            <td class="px-6 py-4">{{ $item->jenis_kelamin }}</td>
                            <td class="px-6 py-4">{{ $item->tempat_lahir }}</td>
                            <td class="px-6 py-4">{{ $item->tanggal_lahir }}</td>
                            <td class="px-6 py-4">{{ $item->id_kelas_now }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

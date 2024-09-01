@extends('component.print-header')

@section('content')
<style>
    @media print {
        .no-print {
            display: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        thead {
            display: table-header-group;
        }
    }
</style>

<h2>JADWAL PELAJARAN <br>KELAS  {{$id_kelas}}<br></h2>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-10">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">hari</th>
                    <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                    <th scope="col" class="px-6 py-3">Jam</th>
                    <th scope="col" class="px-6 py-3">Guru Pengajar</th>
            
                </tr>
            </thead>
            <tbody>
                @foreach ($sortedJadwal as $item)
                    <tr class="border-b">
                        <td class="px-6 py-4">{{ $item->hari }}</td>
                        <td class="px-6 py-4">{{ $item->mapel->nama_pelajaran }}</td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4">{{ $item->guru->nama }}</td>
                        
                    </tr>
                @endforeach
            
            </tbody>
        </table>
    </div>
</div>
@endsection

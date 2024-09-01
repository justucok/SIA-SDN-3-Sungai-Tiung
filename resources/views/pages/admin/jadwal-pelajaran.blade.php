@extends('component.layout')

@section('content')
    <div class="flex flex-col justify-center" id="print-area">
        <div class="mx-auto max-w-screen-xl p-4 sm:px-6 lg:px-8">
            <header class="text-center">
                <h2 class="text-4xl font-extrabold text-gray-900 md:text-5xl lg:text-6xl">Jadwal Pelajaran</h2>
            </header>
        </div>

        <form id="filter-form" method="GET" action="{{ route('index.jadwal') }}">
            @csrf
            <div class="mb-8">
                <div>
                    <label for="id_kelas" class="block mb-2 text-sm font-medium text-gray-900">Pilih Kelas</label>
                    <select id="id_kelas" name="id_kelas"
                        class="w-full text-gray-900 bg-gray-50 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm px-5 py-2.5"
                        onchange="submitForm()">
                        <option value="">--PILIH KELAS--</option>
                        @foreach ($clases as $kls)
                            <option value="{{ $kls->id }}"
                                {{ request()->input('id_kelas') == $kls->id ? 'selected' : '' }}>
                                {{ $kls->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Hidden input to pass the selected IDs -->
                <input type="hidden" name="filter_action" value="1">
        </form>

        @if (!request()->input('id_kelas'))
            <div class="text-center text-gray-600 mt-8">
                <p>Pilih filter kelas terlebih dahulu.</p>
            </div>
        @elseif ($sortedJadwal->isEmpty())
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8 my-8 print:hidden">
                <div class="rounded-lg bg-gray-200">
                    <a class="group flex items-center justify-between gap-4 rounded-lg border border-blue-700 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 px-5 py-3 transition-colors"
                        href="{{ route('create.jadwal', ['id_kelas' => request()->input('id_kelas')]) }}">
                        <span class="font-medium text-white transition-colors group-active:text-blue-600">
                            Tambahkan Jadwal Pelajaran
                        </span>
                        <span
                            class="shrink-0 rounded-full border border-current bg-white p-2 text-blue-700 group-active:text-blue-600">
                            <svg class="size-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </a>
                </div>
                @if (session('success'))
                    <div id="success-message" class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <div class="text-center text-gray-600 mt-8">
                <p>Jadwal kelas kosong.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8 my-8 print:hidden">
                <div class="rounded-lg bg-gray-200">
                    <a class="group flex items-center justify-between gap-4 rounded-lg border border-blue-700 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 px-5 py-3 transition-colors"
                        href="{{ route('create.jadwal', ['id_kelas' => request()->input('id_kelas')]) }}">
                        <span class="font-medium text-white transition-colors group-active:text-blue-600">
                            Tambahkan Jadwal Pelajaran
                        </span>
                        <span
                            class="shrink-0 rounded-full border border-current bg-white p-2 text-blue-700 group-active:text-blue-600">
                            <svg class="size-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <!-- table 1 -->
            @if (session('success_delete'))
                <div id="success-message" class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success_delete') }}
                </div>
            @endif
            @if (session('success'))
                <div id="success-message" class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-10">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Hari</th>
                            <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3">Jam</th>
                            <th scope="col" class="px-6 py-3">Guru Pengajar</th>
                            <th scope="col" class="px-6 py-3 print:hidden">Opsi</th>
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
                                <td class="px-6 py-4 print:hidden">
                                    <form action="{{ route('delete.jadwal', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>

                            <td class="px-6 py-4 print:hidden">
                                <button type="button" id="download-btn"
                                    onclick="printPage('{{ route('download.data.jadwal', ['id_kelas' => $idKelas]) }}')"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                                    Print
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script>
        function submitForm() {
            document.getElementById('filter-form').submit();
        }

        function printPage(url) {
            var printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
@endsection

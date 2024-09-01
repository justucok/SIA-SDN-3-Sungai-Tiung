@extends('component.layout')

@section('content')
    <div class="flex flex-col justify-center">
        <div class="mx-auto max-w-screen-xl p-4 sm:px-6 lg:px-8">
            <header class="text-center">
                <h2 class="text-4xl font-extrabold text-gray-900 md:text-5xl lg:text-6xl">
                    Daftar Siswa Ekstrakulikuler <br>
                    {{ $uniqueEkskulArray->isNotEmpty() ? $uniqueEkskulArray->first()->ekstrakulikuler->ekstrakulikuler : 'Tidak ada data' }}
                </h2>
            </header>

            <!-- Conditional Content -->
            @if($uniqueEkskulArray->isEmpty())
                <div class="mt-4 text-center text-gray-500">
                    <p>Belum ada siswa yang mengikuti ekstrakurikuler ini.</p>
                </div>
            @else
                <!-- Table -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nama Siswa</th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3">Jenis Kelamin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($uniqueEkskulArray as $index => $item)
                                <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} border-b">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4">{{ $item->siswa->nama_lengkap }}</td>
                                    <td class="px-6 py-4">{{ $item->siswa->id_kelas_now }}</td>
                                    <td class="px-6 py-4">{{ $item->siswa->jenis_kelamin }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right">
                                    <button type="button" id="download-btn"
                                        onclick="printPage('{{ route('download.data.ekskul.siswa', ['id' => $id]) }}')"
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
    </div>

    <script>
        function printPage(url) {
            var printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
@endsection

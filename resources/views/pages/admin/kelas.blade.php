@extends('component.layout')

@section('content')
    <div class="flex flex-col justify-center" id='print-out'>
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
            Data Siswa {{ $kelas->nama_kelas }}
        </h1>
        <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl">
            Wali kelas : {{ $kelas->walikelas ? $kelas->walikelas->nama : 'belum ada walikelas' }}
        </p>

        <!-- table 1 -->
        @if (session('success_delete'))
            <div id="success-message" class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success_delete') }}
            </div>
        @endif
        @if (session('error_delete'))
            <div class="alert alert-danger">
                {{ session('error_delete') }}
            </div>
        @endif
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">NISN</th>
                        <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-3">Jenis Kelamin</th>
                        <th scope="col" class="px-6 py-3">Tempat Lahir</th>
                        <th scope="col" class="px-6 py-3">Tanggal Lahir</th>
                        <th scope="col" class="px-6 py-3 ">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $item)
                        <tr class="odd:bg-white even:bg-gray-50 border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $item->nisn }}
                            </th>
                            <td class="px-6 py-4">{{ $item->nama_lengkap }}</td>
                            <td class="px-6 py-4">{{ $item->jenis_kelamin }}</td>
                            <td class="px-6 py-4">{{ $item->tempat_lahir }}</td>
                            <td class="px-6 py-4">{{ $item->tanggal_lahir }}</td>
                            <td class="px-6 py-4 ">
                                @include('component.admin.btn-group-siswa')
                            </td>
                        </tr>
                    @endforeach

                    <div class="mt-3 mb-3 ">
                        {{ $siswa->links() }}
                    </div>
                    <tr>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        </th>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4 ">
                            <button type="button" id="download-btn"
                                onclick="printPage('{{ route('download.data.siswa', ['id' => $kelas->id]) }}')"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                                Print
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
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

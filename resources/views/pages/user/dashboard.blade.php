@extends('component.layout')

@section('content')
    <!-- content -->

    <div class="flex flex-col justify-center">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
            Kalender Akademik
        </h1>



        @include('component.dashboard-content')

        <h1 class="mt-8 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
            Jadwal pelajaran
        </h1>
        <div class="flex flex-col justify-center " id="print-area">
            <div class="mx-auto max-w-screen-xl p-4 sm:px-6 lg:px-8">

            </div>

            <form id="filter-form" method="GET" action="{{ route('index.user') }}">
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
                                    {{ request()->input('id_kelas') == $kls->id ? 'selected' : ($kls->id == 1 ? 'selected' : '') }}>
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
                    <p class="text-red-500">Pilih filter kelas terlebih dahulu <br>untuk melihat jadwal pelajaran.</p>
                </div>
            @elseif ($jadwal->isEmpty())
                <div class="text-center text-gray-600 mt-8">
                    <p>Jadwal kelas Masih kosong.</p>
                </div>
            @else
                <!-- table 1 -->
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
                            @foreach ($jadwal as $item)
                                <tr class=" border-b">

                                    <td class="px-6 py-4">{{ $item->hari }}</td>
                                    <td class="px-6 py-4">{{ $item->mapel->nama_pelajaran }}</td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                    </td>

                                    <td class="px-6 py-4">{{ $item->guru->nama }}</td>

                                </tr>
                            @endforeach
                            <tr>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                </th>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>


                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <script>
        function submitForm() {
            document.getElementById('filter-form').submit();
        }
    </script>
    <!-- end content -->
@endsection

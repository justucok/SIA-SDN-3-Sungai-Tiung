@extends('component.layout')

@section('content')
    <div class="flex flex-col justify-center">
        <h1 class="mb-10 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
            Input Nilai Project
        </h1>
        
        <form id="filter-form" method="GET" action="{{ route('show.project.byFilter') }}">
            @csrf
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="id_kelas" class="block mb-2 text-sm font-medium text-gray-900">Pilih Kelas</label>
                    <select id="id_kelas" name="id_kelas"
                        class=" text-center w-full text-gray-900 bg-gray-50 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm px-5 py-2.5"
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
                <div>
                    <label for="id_tahun_ajar" class="block mb-2 text-sm font-medium text-gray-900">Pilih Tahun
                        Ajaran</label>
                    <select id="id_tahun_ajar" name="id_tahun_ajar"
                        class=" text-center w-full text-gray-900 bg-gray-50 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm px-5 py-2.5"
                        onchange="submitForm()">
                        <option value="">--PILIH TAHUN AJARAN--</option>
                        @foreach ($tahunajarans as $TA)
                            <option value="{{ $TA->id }}"
                                {{ request()->input('id_tahun_ajar') == $TA->id ? 'selected' : '' }}>
                                {{ $TA->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="id_siswa" class="block mb-2 text-sm font-medium text-gray-900">Pilih Siswa</label>
                    <select id="id_siswa" name="id_siswa"
                        class=" text-center w-full text-gray-900 bg-gray-50 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm px-5 py-2.5"
                        onchange="submitForm()">
                        <option value="">--PILIH NAMA SISWA--</option>
                        @foreach ($siswas as $siswa)
                            <option value="{{ $siswa->id }}"
                                {{ request()->input('id_siswa') == $siswa->id ? 'selected' : '' }}>
                                {{ $siswa->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="id_semester" class="block mb-2 text-sm font-medium text-gray-900">Pilih Semester</label>
                    <select id="id_semester" name="id_semester"
                        class=" text-center w-full text-gray-900 bg-gray-50 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm px-5 py-2.5"
                        onchange="submitForm()">
                        <option value="">--PILIH SEMESTER--</option>
                        @foreach ($semesters as $smt)
                            <option value="{{ $smt->id }}"
                                {{ request()->input('id_semester') == $smt->id ? 'selected' : '' }}>
                                {{ $smt->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="id_project" class="block mb-2 text-sm font-medium text-gray-900">Pilih Judul Projek
                    siswa</label>
                <select id="id_project" name="id_project"
                    class="text-center w-full text-gray-900 bg-gray-50 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm px-5 py-2.5"
                    onchange="submitForm()">
                    <option value="">--- PILIH JUDUL ---</option>
                    @foreach ($projects as $judul)
                        <option value="{{ $judul->id }}"
                            {{ request()->input('id_project') == $judul->id ? 'selected' : '' }}>
                            {{ $judul->judul }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Hidden input to pass the selected IDs -->
            <input type="hidden" name="filter_action" value="1">
        </form>
        {{-- 
        @if ($idKelas && $idTahunAjar && $idSiswa && $idSemester && $idProject)
            @if ($nilai->isEmpty())
                <!-- Display the "Tambah" button if no data is found -->
                <div class="flex justify-center mt-6">
                    <tr>
                        <p>Data projek pada siswa dengan filter yang anda masukkan belum ada, mohon tambahkan Terlebih
                            dahulu Judul Proyek yang di pilih sesuai siswa yang ada pada pilihan diatas</p>
                    </tr>
                    <tr>
                        <button id="open-modal" type="button"
                            class="mt-4 mb-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Tambah Projek Siswa 
                        </button>
                    </tr>
                    @include('component.user.modal-add-rapot-mbkm', ['projects' => $projects])
                    @include('component.modal-warning')
                </div>
            @else --}}


        @if (request()->input('id_kelas') &&
                request()->input('id_tahun_ajar') &&
                request()->input('id_siswa') &&
                request()->input('id_semester') &&
                request()->input('id_project'))
            @if ($projects->isNotEmpty())
                <h1
                    class="text-center mt-8 text-4xl font-medium tracking-tight leading-none text-gray-900 md:text-5xl lg:text-3xl">
                    PROJEK
                </h1>
                <h1
                    class="text-center text-4xl font-medium tracking-tight leading-none text-gray-900 md:text-5xl lg:text-3xl">
                    "{{ $projects->firstWhere('id', request()->input('id_project'))->judul }}"
                </h1>


                <p class="text-center mt-10 text-gray-700 font-semibold text-2xl">Keterangan Tingkat Pencapaian Siswa</p>
                <table class="w-full mt-5 mb-20 text-sm text-left rtl:text-right text-gray-500 ">
                    <thead class="text-center text-xs text-gray-700 uppercase bg-gray-200">
                        <tr >
                            <th scope="col" class="px-6 py-3">BB</th>
                            <th scope="col" class="px-6 py-3">MB</th>
                            <th scope="col" class="px-6 py-3">BSH</th>
                            <th scope="col" class="px-6 py-3">BB</th>
    
                        </tr>
                    </thead>
                    <tbody class=" text-center text-gray-700">
                        <tr class="bg-white border dark:bg-white">
                            <td class="px-6 py-4">Belum Berkembang</td>
                            <td class="px-6 py-4">Mulai Berkembang</td>
                            <td class="px-6 py-4">Berkembang sesuai harapan</td>
                            <td class="px-6 py-4">Sangat Berkembang</td>
                            
                        </tr>
                        <tr class="bg-white border dark:bg-white">
                            <td class="px-6 py-4">Siswa masih membutuhkan bimbingan dalam mengembangkan kemampuan	</td>
                            <td class="px-6 py-4">Siswa mulai mengembangkan kemampuan namun masih belum ajek	</td>
                            <td class="px-6 py-4">Siswa telah mengembangkan kemampuan hingga berada dalam tahap ajek	</td>
                            <td class="px-6 py-4">Siswa mengembangkan kemampuannya melampaui harapan	</td>
                        </tr>
                    </tbody>
                </table>

                <table class="w-full mt-2 text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-center text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Capaian</th>
                            <th scope="col" class="px-6 py-3">Nilai</th>
                            <th scope="col" class="px-6 py-3">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomor = 1;
                        @endphp
                        @foreach ($fases as $item)
                            <tr class="bg-white border dark:bg-white">
                                <td class="px-6 py-4">{{ $item->element }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $nilaiRapor = $nilai->firstWhere('id_capaian', $item->id);
                                    @endphp
                                    @if ($nilaiRapor)
                                        {{ $nilaiRapor->nilai_Mbkm->nilai }}
                                    @else
                                        Belum ada nilai
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($nilaiRapor)
                                        <button id="edit-modal" type="button"
                                            class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                            onclick="openEditModal(
                                                  '{{ $item->id }}',
                                                '{{ $nilaiRapor->id }}',
                                                '{{ $nilaiRapor->id_nilai }}',
                                                '{{ $nilaiRapor->id_siswa }}',
                                                '{{ $nilaiRapor->id_kelas }}',
                                                '{{ $nilaiRapor->id_semester }}',
                                                '{{ $nilaiRapor->id_tahun_ajar }}',
                                                '{{ $nilaiRapor->id_project }}',
                                                '{{ $nilaiRapor->id_capaian }}',
                                                '{{ $nilaiRapor->nilai_Mbkm->nilai }}',
                                            )">
                                            Edit
                                        </button>
                                        @include('component.user.modal-edit-nilai-mbkm', [
                                            'predikat' => $predikat,
                                        ])
                                    @else
                                        <button id="tambah-modal" type="button"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                            onclick="openAddModal('{{ $item->id }}')">
                                            Add Nilai
                                        </button>
                                        @include('component.user.modal-input-nilai-mbkm', [
                                            'predikat' => $predikat,
                                        ])
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center mt-4">Tidak ada data yang ditemukan dengan filter yang dipilih.</p>
            @endif
        @else
            <p class="text-center mt-4">Pilih semua filter untuk menampilkan data.</p>
        @endif
    </div>
    {{-- @endif --}}
    {{-- @else
            <!-- Display a message if no filters are selected -->
            <div class="flex justify-center mt-6">
                <p class="text-gray-500">Pilih filter terlebih dahulu untuk menampilkan data.</p>
            </div>
        @endif --}}
    </div>
@endsection

<script>
    function submitForm() {
        document.getElementById('filter-form').submit();
    }
    // document.getElementById('open-modal').addEventListener('click', function() {
    //     const idKelas = document.getElementById('id_kelas').value;
    //     const idTahunAjar = document.getElementById('id_tahun_ajar').value;
    //     const idSiswa = document.getElementById('id_siswa').value;
    //     const idSemester = document.getElementById('id_semester').value;
    //     const idProject = this.getAttribute('data-project');

    //     if (idKelas && idTahunAjar && idSiswa && idSemester) {
    //         document.getElementById('modal-nama_siswa').value = document.getElementById('id_siswa').options[
    //             document.getElementById('id_siswa').selectedIndex].text;
    //         document.getElementById('modal-kelas').value = document.getElementById('id_kelas').options[document
    //             .getElementById('id_kelas').selectedIndex].text;
    //         document.getElementById('modal-semester').value = document.getElementById('id_semester').options[
    //             document.getElementById('id_semester').selectedIndex].text;

    //         document.getElementById('modal').classList.remove('hidden');
    //     } else {
    //         document.getElementById('alert-modal').classList.remove('hidden');
    //     }
    // });
    // document.getElementById('close-alert-modal').addEventListener('click', function() {
    //     document.getElementById('alert-modal').classList.add('hidden');
    // });

    // document.getElementById('close-modal').addEventListener('click', function() {
    //     document.getElementById('modal').classList.add('hidden');
    // });

    // // Event listener untuk tombol "Tambah nilai sesuai subjek"
    // document.getElementById('open-modal-nilai').addEventListener('click', function() {
    //     const idKelas = document.getElementById('id_kelas').value;
    //     const idTahunAjar = document.getElementById('id_tahun_ajar').value;
    //     const idSiswa = document.getElementById('id_siswa').value;
    //     const idSemester = document.getElementById('id_semester').value;
    //     const projectId = this.getAttribute('data-project-id');


    //     if (idKelas && idTahunAjar && idSiswa && idSemester) {
    //         // Menetapkan nilai untuk modal
    //         document.getElementById('modal-nama_siswa').value = document.getElementById('id_siswa').options[
    //             document.getElementById('id_siswa').selectedIndex].text;
    //         document.getElementById('modal-kelas').value = document.getElementById('id_kelas').options[
    //             document.getElementById('id_kelas').selectedIndex].text;
    //         document.getElementById('modal-semester').value = document.getElementById('id_semester').options[
    //             document.getElementById('id_semester').selectedIndex].text;
    //         document.getElementById('modal-id-project').value = projectId;




    //         const modal = document.getElementById('modal-input-nilai-mbkm');
    //         const instance = bootstrap.Modal.getOrCreateInstance(modal);
    //         const namaJudul = this.getAttribute('data-judul');

    //         instance.show();

    //     } else {
    //         const alertModal = document.getElementById('alert-modal');
    //         alertModal.classList.remove('hidden');
    //     }
    // });

    // // Event listener untuk menutup alert modal
    // document.getElementById('close-alert-modal').addEventListener('click', function() {
    //     document.getElementById('alert-modal').classList.add('hidden');
    // });

    // // Event listener untuk menutup input nilai modal
    // document.getElementById('close-modal').addEventListener('click', function() {
    //     document.getElementById('modal-input-nilai-mbkm').classList.add('hidden');
    // });


    function openAddModal(capaianId) {
        const idKelas = document.getElementById('id_kelas').value;
        const idTahunAjar = document.getElementById('id_tahun_ajar').value;
        const idSiswa = document.getElementById('id_siswa').value;
        const idSemester = document.getElementById('id_semester').value;
        const idProject = document.getElementById('id_project').value; // Pastikan id_project sudah didefinisikan

        const modal = document.getElementById('modal-add-nilai');


        document.getElementById('modal-capaian-id').value = capaianId;
        document.getElementById('modal-id-kelas').value = idKelas;
        document.getElementById('modal-id-tahun-ajar').value = idTahunAjar;
        document.getElementById('modal-id-siswa').value = idSiswa;
        document.getElementById('modal-id-semester').value = idSemester;
        document.getElementById('modal-id-project').value = idProject;

        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-add-nilai').classList.add('hidden');
    }


    function openEditModal(capaianId,Id, nilaiId, idSiswa, idKelas, idSemester, idTahunAjar, idProject, idCapaian, nilai) {
        // Set values to hidden input fields in the modal
        document.getElementById('capaianId').value = capaianId;
        document.getElementById('nilaiId').value = nilaiId;
        document.getElementById('id').value = Id;
        document.getElementById('id_siswa').value = idSiswa;
        document.getElementById('id_kelas').value = idKelas;
        document.getElementById('id_semester').value = idSemester;
        document.getElementById('id_tahun_ajar').value = idTahunAjar;
        document.getElementById('id_project').value = idProject;
        document.getElementById('id_capaian').value = idCapaian;
        document.getElementById('nilai').value = nilai;
        // Show the modal
        document.getElementById('edit-modal').classList.add('block');
        document.getElementById('edit-modal').classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.querySelector('.modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>

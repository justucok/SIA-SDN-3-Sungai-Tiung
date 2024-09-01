@extends('component.layout')

@section('content')
    <div class="flex flex-col justify-center">
        <div class="mx-auto max-w-screen-xl p-4 sm:px-6 lg:px-8">
            <header class="text-center">
                <h2 class="text-4xl font-extrabold text-gray-900 md:text-5xl lg:text-6xl"> Jadwal Ekstrakulikuler</h2>
            </header>
            
        </div>
        <!-- table 1 -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Ekstrakulikuler</th>
                        <th scope="col" class="px-6 py-3">Hari</th>
                        <th scope="col" class="px-6 py-3">Jam</th>
            
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ekskul as $index => $item)
                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} border-b">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4">{{ $item->ekstrakulikuler }}</td>
                        <td class="px-6 py-4">{{ $item->hari }}</td>
                        <td class="px-6 py-4">{{ $item->jam_mulai }} - {{$item->jam_selesai}}</td>
                        
                    </tr>
                    @endforeach
               
                </tbody>
            </table>
        </div>

    </div>
@endsection

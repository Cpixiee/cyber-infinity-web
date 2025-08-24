@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-900">
    @include('layouts.sidebar')

    <!-- Main content -->
    <div class="pl-64">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-white">Tambah Workshop Baru</h1>
                <a href="{{ route('workshops.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                    Kembali
                </a>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <form action="{{ route('workshops.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">Nama Workshop</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300">Deskripsi</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-300">Lokasi</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-300">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration_hours" class="block text-sm font-medium text-gray-300">Durasi (jam)</label>
                            <input type="number" name="duration_hours" id="duration_hours" value="{{ old('duration_hours') }}" min="1" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('duration_hours')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-800">
                            <label for="is_active" class="ml-2 block text-sm text-gray-300">Workshop Aktif</label>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-md hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                            Buat Workshop
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

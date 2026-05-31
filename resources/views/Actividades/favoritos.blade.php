@extends('layouts.pag')

@section('title', 'Favoritos')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Tus favoritos</h1>

    <div class="grid grid-cols-4 gap-6">
        @forelse ($favoritos as $actividad)
           <a href="{{ route('contenido.mios.show', $actividad->contenido->id) }}"
           class="bg-white rounded-lg shadow p-2 block hover:shadow-lg transition">

            <img src="{{ $actividad->contenido->detalles['imagen'] ?? '/img/no-image.png' }}" class="w-full h-full">

            <h3 class="mt-2 text-black">{{ $actividad->contenido->titulo }}</h3>
        </a>
        @empty
            <p class="text-body">No tienes contenido favorito </3</p>
        @endforelse
    </div>
@endsection

@extends('layouts.pag')

@section('title', $contenido->titulo)

@section('content')
<x-flecha-atras/>

<div class="max-w-3xl mx-auto mt-10 p-6 bg-tarjeta border border-slate-700 rounded-xl shadow-lg">

    {{-- Título --}}
    <h1 class="text-3xl font-bold mb-6 text-center">{{ $contenido->titulo }}</h1>

    {{-- Imagen si existe --}}
    @if($contenido->imagen)
        <div class="flex justify-center mb-6">
            <img src="{{ asset('storage/' . $contenido->imagen) }}"
                 alt="{{ $contenido->titulo }}"
                 class="w-64 h-auto rounded-lg shadow">
        </div>
    @endif

    {{-- Descripción --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Descripción</h2>
        <p class="text-slate-300 leading-relaxed">
            {{ $contenido->descripcion ?? 'Sin descripción disponible.' }}
        </p>
    </div>

    {{-- Tipo --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Tipo</h2>
        <p class="text-slate-300">
            {{ $contenido->tipo }}
        </p>
    </div>

    {{-- Acciones --}}
    <div class="flex justify-between items-center mt-8">

        {{-- Botón Editar --}}
        <a href="{{ route('contenido.edit', $contenido->id) }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">
            Editar
        </a>

        {{-- Botón Eliminar --}}
        <form action="{{ route('contenido.destroy', $contenido->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit"
                onclick="return confirm('¿Seguro que quieres eliminar este contenido?')"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow">
                Eliminar
            </button>
        </form>

    </div>

</div>
@endsection

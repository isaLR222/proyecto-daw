@extends('layouts.pag')

@section('title', 'Inicio')

@section('content')
    <x-flecha-atras />

    <div class="flex justify-between items-center mb-4 mt-4">
        <h1 class="text-3xl font-bold">{{ $lista->nombre }}</h1>
        <div class="flex items-center space-x-4">

            <a href="{{ route('listas.edit', $lista->id) }}" class="text-white font-semibold">
                Editar
            </a>
            <form action="{{ route('listas.destroy', $lista->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')

                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold"
                    onclick="return confirm('¿Seguro que quieres eliminar esta lista?')">
                    Eliminar
                </button>
            </form>

        </div>
    </div>

@endsection

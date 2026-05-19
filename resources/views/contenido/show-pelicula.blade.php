@extends('layouts.pag')

@section('title', $pelicula['titulo'])

@section('content')
<x-flecha-atras/>
   <div class="flex gap-8">

    <!-- Izqioerda -->
    <div class="flex flex-col items-start">

        <img src="{{ $pelicula['imagen'] }}" class="w-64 rounded shadow">

        <!-- Selector de estado y Corazón alineados -->
        <div class="mt-4 w-full flex items-center gap-2">
            <div class="relative w-32"> <!-- Ajustado a w-32 para que coincida con el ancho del select -->
                <select
                    class="w-32 appearance-none bg-gray-800 text-white px-4 py-2 pr-10 rounded-lg shadow cursor-pointer focus:outline-none focus:ring-2 focus:ring-colorInputs"
                    name="estado"
                    id="estado">
                    <option value="no-visto">No visto</option>
                    <option value="visto">Visto</option>
                    <option value="viendo">Viendo</option>
                </select>
            </div>
            
            <!-- El corazón ahora se renderiza justo al lado derecho -->
            <x-corazon-vacio/>
        </div>
    </div>
     <!-- Derecha -->
    <div>
        <h1 class="text-4xl font-bold mb-4 text-neutral-50">{{ $pelicula['titulo'] }}</h1>

        <p class="mb-4">{{ $pelicula['descripcion'] }}</p>

        <p class="text-sm text-neutral-50 mb-2">
            <strong>Géneros:</strong> {{ implode(', ', $pelicula['generos']) }}
        </p>

        <p class="text-sm text-neutral-50">
            <strong>Fecha:</strong> {{ $pelicula['fecha'] }}
        </p>
        <br><br>
        <hr>
        <p><strong>Reseña:</strong></p>

        <div class="inline-flex gap-1"><x-estrella-vacia/><x-estrella-vacia/><x-estrella-vacia/><x-estrella-vacia/><x-estrella-vacia/></div>
        <textarea name="" id="" class="w-full bg-purple-200 text-black  focus:ring-colorInputs" placeholder="Añade tu comentario"></textarea>
        <hr>
    </div>

</div>

@endsection

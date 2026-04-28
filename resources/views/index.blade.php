@extends('layouts.pag')

@section('title', 'Inicio')

@section('content')
    <h1 class="text-3xl font-bold">Descubre</h1>

    <div class="p-4">
        <div class="inline-flex border border-gray-300 rounded-full overflow-hidden text-sm bg-colorInputs backdrop-blur-sm">
            <label class="px-4 py-2 cursor-pointer flex items-center gap-2 peer-checked:bg-tarjeta peer-checked:text-white">
                <input type="radio" name="opciones" value="Películas" class="hidden peer">
                Películas
            </label>

            <label
                class="px-4 py-2 cursor-pointer flex items-center gap-2 peer-checked:bg-tarjeta peer-checked:text-white border-l border-gray-300">
                <input type="radio" name="opciones" value="Libros" class="hidden peer">
                Libros
            </label>

            <label
                class="px-4 py-2 cursor-pointer flex items-center gap-2 peer-checked:bg-tarjeta peer-checked:text-white border-l border-gray-300">
                <input type="radio" name="opciones" value="Mios" class="hidden peer">
                Mios
            </label>
        </div>

    </div>

    

@endsection

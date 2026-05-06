@extends('layouts.pag')

@section('title', 'Inicio')

@section('content')
<x-flecha-atras/>
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-tarjeta border border-slate-700 rounded-xl shadow-lg">

    <form action="{{ route('listas.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <x-input-label for="nombre" :value="__('Nombre')" />
            <x-text-input id="nombre"
                class="block mt-1 w-full bg-slate-800"
                type="text"
                name="nombre"
                required
                autofocus />
        </div>
        <div class="mb-4">
            <x-input-label for="descripcion" :value="__('Descripción')" />
            <textarea id="descripcion"
                name="descripcion"
                rows="4"
                class="block mt-1 w-full bg-slate-800 text-white rounded-md focus:border-colorInputs focus:ring-colorInputs"></textarea>
        </div>

        <button type="submit"
            class="px-4 py-2 bg-colorInputs text-white font-semibold rounded-lg shadow hover:bg-[#C30B4E]">
            Crear lista
        </button>

    </form>

</div>
@endsection

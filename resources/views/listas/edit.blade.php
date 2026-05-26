@extends('layouts.pag')

@section('title', 'Editar lista')

@section('content')
<x-flecha-atras/>

<div class="max-w-2xl mx-auto mt-10 p-6 bg-tarjeta border border-slate-700 rounded-xl shadow-lg">

    <h1 class="text-3xl font-bold mb-6">Editar lista</h1>
    <form action="{{ route('listas.update', $lista->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <x-input-label for="nombre" :value="__('Nombre')" />
            <x-text-input id="nombre"
                class="block mt-1 w-full bg-slate-800"
                type="text"
                name="nombre"
                value="{{ old('nombre', $lista->nombre) }}"
                required
                autofocus />
        </div>

        <div class="mb-4">
            <x-input-label for="descripcion" :value="__('Descripción')" />
            <textarea id="descripcion"
                name="descripcion"
                rows="4"
                class="block mt-1 w-full bg-slate-800 text-white rounded-md focus:border-colorInputs focus:ring-colorInputs">{{ old('descripcion', $lista->descripcion) }}</textarea>
        </div>

        <button type="submit"
            class="px-4 py-2 bg-colorInputs text-white font-semibold rounded-lg shadow hover:bg-[#C30B4E]">
            Actualizar lista
        </button>

    </form>

</div>
@endsection

@extends('layouts.pag')

@section('title', $libro['titulo'])

@section('content')
<x-flecha-atras/>

<div class="flex gap-8">

    <!-- izquierda -->
    <div id="estCorLibro" class="flex flex-col items-start">

        <img src="{{ $libro['imagen'] }}" class="w-64 rounded shadow">

        <!-- select y corazon -->
        <div class="mt-4 w-full flex items-center gap-2">
            <select v-model="estado"
                class="w-32 bg-gray-800 text-white px-4 py-2 rounded-lg shadow cursor-pointer">
                <option value="no-visto">No leído</option>
                <option value="visto">Leído</option>
                <option value="viendo">Leyendo</option>
            </select>

            <button @click="toggleFavorito" class="hover:scale-110 transition">
                <span v-if="favorito"><x-corazon-relleno/></span>
                <span v-else><x-corazon-vacio/></span>
            </button>
        </div>
    </div>

    <!-- derecha -->
    <div>
        <h1 class="text-4xl font-bold text-neutral-50">{{ $libro['titulo'] }}</h1>
        <p class="mb-4">{{ $libro['descripcion'] }}</p>

        <hr class="my-4">

        <!-- estrellas -->
        <div id="estrellitasLibro" class="inline-flex gap-1">
            <span v-for="n in 5" :key="n"
                @mouseover="hover = n"
                @mouseleave="hover = rating"
                @click="rating = n"
                class="cursor-pointer text-3xl transition">
                <span v-if="n <= hover"><x-estrella-rellena/></span>
                <span v-else><x-estrella-vacia/></span>
            </span>
        </div>

        <!-- comentarios -->
        <div id="comentariosLibro" class="mt-4">
            <textarea v-model="nuevoComentario"
                @keyup.enter="publicarComentario"
                class="w-full bg-purple-200 text-black focus:ring-colorInputs"
                placeholder="Añade tu comentario"></textarea>

            <hr class="my-4">

            <div v-for="(comentario, index) in comentarios" :key="index"
                class="mb-3 p-3 bg-white/10 rounded text-neutral-50">
                @{{ comentario }}
            </div>
        </div>
    </div>
</div>

<script>
const { createApp } = Vue;

// Favorito + estado
createApp({
    data() {
        return {
            favorito: false,
            estado: 'no-visto'
        }
    },
    methods: {
        toggleFavorito() {
            this.favorito = !this.favorito;
        }
    }
}).mount('#estCorLibro');

// Estrellas
createApp({
    data() {
        return {
            rating: 0,
            hover: 0
        }
    }
}).mount('#estrellitasLibro');

// Comentarios
createApp({
    data() {
        return {
            nuevoComentario: "",
            comentarios: []
        }
    },
    methods: {
        publicarComentario() {
            if (this.nuevoComentario.trim() === "") return;
            this.comentarios.push(this.nuevoComentario);
            this.nuevoComentario = "";
        }
    }
}).mount('#comentariosLibro');
</script>

@endsection

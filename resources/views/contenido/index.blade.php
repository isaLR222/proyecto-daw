@extends('layouts.pag')

@section('title', 'Inicio')

@section('content')
    <h1 class="text-3xl font-bold">Descubre nuevo contenido de interes para ti,  {{ auth()->user()->name }}!</h1>

    <div id="contenido" class="p-4 flex flex-col gap-8">
        <div class="flex gap-8 items-center">

            <!-- Radios -->
            <div
                class="inline-flex border border-gray-300 rounded-full overflow-hidden text-sm bg-colorInputs backdrop-blur-sm">
                <label v-for="op in opciones" :key="op" class="px-4 py-2 cursor-pointer"
                    :class="filtro === op ? 'bg-[#C30B4E] text-white' : ''">
                    <input type="radio" name="opciones" class="hidden" :value="op" v-model="filtro">
                    @{{ op }}
                </label>
            </div>
            <!-- Buscador -->
            <input id="buscador" type="text" v-model="busqueda"
    class="flex-1 max-w-xl px-4 py-2 rounded-full bg-white shadow-sm border text-black"
    placeholder="Encuentra lo que buscas">

<select name="genero" v-model="generoSeleccionado"
    class="px-4 py-2 rounded-full bg-white text-black">
    <option value="">Género</option>
    <option v-for="g in generosFiltrados" :key="g" :value="g">@{{ g }}</option>
</select>


        </div>

        <!-- contenido -->
        <div class="grid grid-cols-4 gap-6">
            <a v-for="p in resultados" :key="p.id" :href="rutaShow(p)"
                class="bg-white rounded-lg shadow p-2 block hover:shadow-lg transition">
                <img :src="p.imagen" class="w-full h-full">
                <h3 class="mt-2 text-black">@{{ p.titulo }}</h3>
            </a>
        </div>
    </div>
<script>
    const { createApp } = Vue;

createApp({
    data() {
        return {
            filtro: 'Películas',
            opciones: ["Películas", "Libros"],

            resultados: [],
            resultadosOriginales: [],

            busqueda: "",
            generoSeleccionado: "",

            generosTMDB: [
                "Acción", "Aventura", "Animación", "Comedia", "Crimen", "Documental",
                "Drama", "Familia", "Fantasía", "Historia", "Horror", "Música",
                "Misterio", "Romance", "Ciencia ficción", "Thriller", "Guerra", "Western"
            ],

            generosBooks: [
                "Ficción", "Fantasía", "Ciencia ficción", "Misterio", "Thriller",
                "Romance", "Juvenil", "Biografía", "Autoayuda", "Negocios",
                "Tecnología", "Poesía"
            ]
        }
    },

    computed: {
        generosFiltrados() {
            return this.filtro === "Películas"
                ? this.generosTMDB
                : this.generosBooks;
        }
    },

    methods: {
        async cargarPeliculas() {
            const res = await fetch("/api/peliculas");
            let datos = await res.json();

            
            datos = datos.map(p => ({
                ...p,
                genero: Array.isArray(p.genero) ? p.genero[0] : p.genero
            }));

            this.resultadosOriginales = datos;
            this.aplicarFiltros();
        },

        async cargarLibros() {
            const res = await fetch("/api/libros");
            let datos = await res.json();

            datos = datos.map(l => ({
                ...l,
                genero: Array.isArray(l.genero) ? l.genero[0] : l.genero
            }));

            this.resultadosOriginales = datos;
            this.aplicarFiltros();
        },

        aplicarFiltros() {
            let datos = [...this.resultadosOriginales];

            // Filtro del buscador
            if (this.busqueda.trim() !== "") {
                const b = this.busqueda.toLowerCase();
                datos = datos.filter(p => p.titulo.toLowerCase().includes(b));
            }

            // Filtro por género
            if (this.generoSeleccionado !== "") {
                datos = datos.filter(p => p.genero === this.generoSeleccionado);
            }

            this.resultados = datos;
        },

        rutaShow(item) {
            return this.filtro === "Películas"
                ? `/contenido/pelicula/${item.id}`
                : `/contenido/libro/${item.id}`;
        }
    },

    watch: {
        filtro(nuevo) {
            this.busqueda = "";
            this.generoSeleccionado = "";

            if (nuevo === "Películas") this.cargarPeliculas();
            if (nuevo === "Libros") this.cargarLibros();
        },

        busqueda() {
            this.aplicarFiltros();
        },

        generoSeleccionado() {
            this.aplicarFiltros();
        }
    },

    mounted() {
        this.cargarPeliculas();
    }

}).mount('#contenido');
</script>

@endsection

@extends('layouts.pag')

@section('title', 'Inicio')

@section('content')
    <h1 class="text-3xl font-bold">Descubre</h1>

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
            <input id="buscador" type="text"
                class="flex-1 max-w-xl px-4 py-2 rounded-full bg-white shadow-sm border text-black"
                placeholder="Encuentra lo que buscas">

            <!-- Select género -->
            <select name="genero" class="px-4 py-2 rounded-full bg-white text-black">
                <option disabled selected>Género</option>
                <option v-for="g in generosFiltrados" :key="g" :value="g">@{{ g }}
                </option>
            </select>
        </div>

        <!-- contenido -->
        <div class="grid grid-cols-4 gap-6">
            <a v-for="p in resultados" :key="p.id" :href="rutaShow(p)"
                class="bg-white rounded-lg shadow p-2 block hover:shadow-lg transition">
                <img :src="p.imagen" class="w-full h-full ">
                <h3 class="mt-2  text-black">@{{ p.titulo }}</h3>
            </a>
        </div>
    </div>

    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    filtro: 'Películas',
                    opciones: ["Películas", "Libros", "Mios"],

                    resultados: [],

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
                    if (this.filtro === "Películas") return this.generosTMDB;
                    if (this.filtro === "Libros") return this.generosBooks;
                    if (this.filtro === "Mios") return [...this.generosTMDB, ...this.generosBooks];
                    return [];
                }
            },

            methods: {
                async cargarPeliculas() {
                    const res = await fetch("/api/peliculas");
                    this.resultados = await res.json();
                },

                async cargarLibros() {
                    const res = await fetch("/api/libros");
                    this.resultados = await res.json();
                },

                async cargarMios() {
    this.resultados = @json($mios ?? []);
},

                rutaShow(item) {
                    if (this.filtro === "Películas") {
                        return `/contenido/pelicula/${item.id}`;
                    }

                    if (this.filtro === "Libros") {
                        return `/contenido/libro/${item.id}`;
                    }

                    if (this.filtro === "Mios") {
                        return `/contenido/mio/${item.id}`;
                    }
                }
            },


            watch: {
                filtro(nuevo) {
                    if (nuevo === "Películas") this.cargarPeliculas();
                    if (nuevo === "Libros") this.cargarLibros();
                    if (nuevo === "Mios") this.cargarMios();
                }
            },

            mounted() {
                this.cargarPeliculas();
            }

        }).mount('#contenido');
    </script>

@endsection

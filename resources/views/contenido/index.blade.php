@extends('layouts.pag')

@section('title', 'Inicio')

@section('content')
    <h1 class="text-3xl font-bold">Descubre</h1>

    <div id="contenido" class="p-4 flex gap-8">

        <!-- Radios -->
        <div class="inline-flex border border-gray-300 rounded-full overflow-hidden text-sm bg-colorInputs backdrop-blur-sm">
            <label v-for="op in opciones" :key="op" class="px-4 py-2 cursor-pointer"
                :class="filtro === op ? 'bg-[#C30B4E] text-white' : ''">
                <input type="radio" name="opciones" class="hidden" :value="op" v-model="filtro">
                @{{ op }}
            </label>
        </div>

        <!-- Buscador -->
        <input id="buscador" type="text" class="flex-1 max-w-xl px-4 py-2 rounded-full bg-white shadow-sm border text-black"
            placeholder="Encuentra lo que buscas">

        <!-- selects -->
        <select name="genero" class="px-4 py-2 rounded-full bg-white text-black">
            <option disabled selected>Género</option>
            <option v-for="g in generosFiltrados" :key="g" :value="g">@{{ g }}
            </option>
        </select>
    </div>
    <div class="grid grid-cols-4 gap-6">


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
            }
            
        }).mount('#contenido');
    </script>



@endsection

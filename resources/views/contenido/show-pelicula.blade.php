@extends('layouts.pag')

@section('title', $pelicula['titulo'])

@section('content')
    <x-flecha-atras />
    <div id="contenido-app" class="flex gap-8">

        <!-- Izqioerda -->
        <div id="estCor" class="flex flex-col items-start">

            <img src="{{ $pelicula['imagen'] }}" class="w-64 rounded shadow">

            <div class="mt-4 w-full flex items-center gap-2">
                <div class="relative w-32">
                    <select
                        class="w-32 appearance-none bg-gray-800 text-white px-4 py-2 pr-10 rounded-lg shadow cursor-pointer focus:outline-none focus:ring-2 focus:ring-colorInputs"
                        name="estado" id="estado" @change="cambiarEstado">
                        <option value="no_visto">No visto</option>
                        <option value="visto">Visto</option>
                        <option value="viendo">Viendo</option>
                    </select>
                </div>
                <button @click="toggleFavorito" class="hover:scale-110">
                    <span v-if="favorito"><x-corazon-relleno /></span>
                    <span v-else><x-corazon-vacio /></span>
                </button>

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

            <div id="estrellitas" class="inline-flex gap-1">
                <span v-for="n in 5" :key="n" @mouseover="hover = n" @mouseleave="hover = rating"
                    @click="setRating(n)" class="cursor-pointer text-3xl transition">
                    <span v-if="n <= hover"><x-estrella-rellena /></span>
                    <span v-else><x-estrella-vacia /></span>
                </span>
            </div>
            <div id="comentarios">
                <textarea v-model="nuevoComentario" @keyup.enter="publicarComentario"
                    class="w-full bg-purple-200 text-black focus:ring-colorInputs" placeholder="Añade tu comentario"></textarea>
                <hr class="my-4">

                <div v-for="(comentario, index) in comentarios" :key="index"
                    class="mb-3 p-3 bg-white/10 rounded text-neutral-50">
                    @{{ comentario }}
                </div>
            </div>
        </div>
    </div>
    <script>
        const PELICULA_DATA = {
    tipo: "pelicula",
    api_id: @json($pelicula['id']),
    titulo: @json($pelicula['titulo']),
    descripcion: @json($pelicula['descripcion']),
    imagen: @json($pelicula['imagen']),
    fecha: @json($pelicula['fecha']),
};


        const ACTIVIDAD_INICIAL = @json($actividad ?? []);
    </script>

    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    estado: ACTIVIDAD_INICIAL.estado ?? "no_visto",
                    favorito: ACTIVIDAD_INICIAL.favorito ?? false,
                    rating: ACTIVIDAD_INICIAL.valoracion ?? 0,
                    hover: 0,
                    nuevoComentario: "",
                    comentarios: ACTIVIDAD_INICIAL.comentario ? [ACTIVIDAD_INICIAL.comentario] : []
                }
            },
            methods: {
                guardarActividad(extra = {}) {
                    const payload = {
                        ...PELICULA_DATA,
                        estado: this.estado,
                        valoracion: this.rating,
                        comentario: extra.comentario ?? this.comentarios[0] ?? null,
                        favorito: this.favorito
                    };

                    fetch("{{ route('actividad.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(payload)
                    });
                },

                cambiarEstado(e) {
                    this.estado = e.target.value;
                    this.guardarActividad();
                },

                toggleFavorito() {
                    this.favorito = !this.favorito;
                    this.guardarActividad();
                },

                setRating(n) {
                    this.rating = n;
                    this.guardarActividad();
                },

                publicarComentario() {
                    if (this.nuevoComentario.trim() === "") return;
                    this.comentarios.push(this.nuevoComentario);
                    this.guardarActividad({
                        comentario: this.nuevoComentario
                    });

                    this.nuevoComentario = "";
                }
            }
        }).mount('#contenido-app');
    </script>


@endsection

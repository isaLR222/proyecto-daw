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
    <div id="buscadorVue" class="relative mb-4">

        <div class="flex items-center gap-2">
            <p class="text-sm whitespace-nowrap">Añadir contenido:</p>

            <input type="text" v-model="search" @focus="open = true" @blur="cerrar" @keyup.enter="añadir"
                class="bg-gray-800 text-white ring-colorInputs rounded-md text-sm px-2 py-1 w-50"
                placeholder="Buscar contenido..." autocomplete="off">
            <button @click="añadir" class="px-3 py-1 bg-colorInputs text-white rounded-md hover:bg-[#C30B4E] text-sm">
                Añadir
            </button>
        </div>
        {{-- Desplegable --}}
        <div v-if="open && filtered.length > 0"
            class="absolute left-[110px] mt-1 w-40 bg-gray-900 border border-gray-700 rounded-md shadow-lg max-h-40 overflow-y-auto">
            <div v-for="item in filtered" :key="item.id" @mousedown="seleccionar(item)"
                class="px-3 py-2 text-sm text-white hover:bg-gray-700 cursor-pointer">
                @{{ item.titulo }}
            </div>
        </div>
    </div>

    {{-- contenido --}}
    <div class="grid grid-cols-4 gap-6">
        @forelse ($contenido as $c)
            <a href="{{ route('contenido.mios.show', $c->id) }}"
                class="bg-white rounded-lg shadow p-2 block hover:shadow-lg transition">

                <img src="{{ $c->detalles['imagen'] ?? '/img/no-image.png' }}" class="w-full h-70 object-cover rounded-md">

                <h3 class="mt-2 text-black">{{ $c->titulo }}</h3>
            </a>
        @empty
            <p class="text-gray-500">No tienes contenido en esta lista.</p>
        @endforelse
    </div>

    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    open: false,
                    search: '',
                    contenidos: @json($contenidos),
                    seleccionados: @json($contenido),
                }
            },
            computed: {
                filtered() {
                    return this.contenidos.filter(c =>
                        c.titulo.toLowerCase().includes(this.search.toLowerCase())
                    );
                }
            },
            methods: {
                seleccionar(item) {
                    this.search = item.titulo;
                    this.open = false;
                },
                cerrar() {
                    setTimeout(() => this.open = false, 100);
                },
                // Añadir contenido 
                añadir() {
                    const item = this.contenidos.find(
                        c => c.titulo.toLowerCase() === this.search.toLowerCase()
                    );

                    if (!item) return;

                    fetch(`/listas/{{ $lista->id }}/add`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                contenido_id: item.id
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.contenido) {
                                this.seleccionados.push(data.contenido);
                            }
                        });

                    this.search = '';
                }
            }
        }).mount('#buscadorVue');
    </script>

@endsection

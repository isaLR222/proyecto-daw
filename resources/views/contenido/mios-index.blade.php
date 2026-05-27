@extends('layouts.pag')

@section('title', 'Mis contenidos')

@section('content')
<h1 class="text-3xl font-bold mb-6">Mi contenido</h1>

<div class="grid grid-cols-4 gap-6">
    @forelse ($contenidos as $c)
        <a href="{{ route('contenido.mios.show', $c->id) }}"
           class="bg-white rounded-lg shadow p-2 block hover:shadow-lg transition">

            <img src="{{ $c->detalles['imagen'] ?? '/img/no-image.png' }}" class="w-full h-full">

            <h3 class="mt-2 text-black">{{ $c->titulo }}</h3>
        </a>
    @empty
        <p class="text-gray-500">No tienes contenido guardado.</p>
    @endforelse
</div>
@endsection


<script>
createApp({
    data() {
        return { contenidos: [] }
    },
    async mounted() {
        const res = await fetch('/api/mis-contenidos')
        this.contenidos = await res.json()
    }
}).mount('#mis-contenidos')
</script>


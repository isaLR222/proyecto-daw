@extends('layouts.pag')

@section('title', 'Inicio')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold">Tus listas</h1> <a href="{{ route('listas.create') }}"
   class="inline-block px-4 py-2 bg-colorInputs  text-white font-semibold rounded-lg shadow hover:bg-[#C30B4E]">
   Crear lista
</a>
</div>
    <div class="grid grid-cols-4 gap-6">
       <!-- prueba para ver como queda --> 
        <a href="#"
            class="bg-neutral-primary-soft block max-w-sm p-4 border border-default rounded-base shadow-xs hover:bg-neutral-secondary-medium">
            <h5 class="mb-3 text-2xl  tracking-tight text-heading leading-8">Favoritos <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
</svg>
</h5>
                <hr>
            <p class="text-body">Lo que mas te ha gustado</p> 
        </a>
        @foreach($listas as $lista)
         <a href="{{Route('listas.show',['lista'=>$lista->id])}}"
            class="bg-neutral-primary-soft block max-w-sm p-4 border border-default rounded-base shadow-xs hover:bg-neutral-secondary-medium">
            <h5 class="mb-3 text-2xl  tracking-tight text-heading leading-8">{{$lista->nombre}} </h5>
                <hr>
            <p class="text-body">{{$lista->descripcion}}</p> 
        </a>
        @endforeach
        
    </div>
@endsection

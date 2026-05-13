<header class="w-full bg-tarjeta shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Proyecto</h1>

        <nav class="flex gap-6 items-center">
            <a href="/" class="hover:underline">Inicio</a>
            <a href="/listas" class="hover:underline">Listas</a>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
</svg>

                </button>

                <!-- Menú -->
                <div 
                    x-show="open"
                    @click.outside="open = false"
                    class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2 z-50"
                >
                    <a href="{{ route('profile.edit') }}"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Editar perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-red-700 hover:bg-gray-100">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</header>

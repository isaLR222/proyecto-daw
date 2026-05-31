# Aplicación web de gestión de libros y películas
Aplicación web diseñada como un diario personal de visualización y lectura, donde el usuario puede explorar un catálogo de películas y libros, valorarlos y organizarlos en listas personalizadas.

# De qué se trata
La aplicación permite:
- Consultar información de películas y libros obtenida desde APIs externas.
- Llevar un registro personal de contenido visto o leído.
- Crear listas personalizadas y gestionar favoritos.
- Añadir valoraciones mediante estrellas y comentarios.

# Funcionalidades
- Visualización de contenido: imagen, descripción, género, fecha, etc.
- Sistema de valoraciones:
- Estrellas
- Comentarios
- Añadir a favoritos
- Estados: visto, viendo, no visto 

# Gestión de listas:
- Crear
- Editar
- Eliminar
- Búsqueda y filtrado por nombre.

# Integración con APIs externas:
TMDB (películas)
OpenLibrary (libros)

# Tecnologías utilizadas
PHP 8.2.12
Laravel 12
XAMPP v3.3.0
MySQL
Vue.js

# Cómo descargar el proyecto
1️. Clonar el repositorio
git clone https://github.com/isaLR222/proyecto-daw.git

2️. Instalar dependencias
composer install
npm install

3️. Migrar la base de datos
php artisan migrate

4. Ejecutar el proyecto
npm run dev
php artisan serve

# Archivo .env
Es necesario añadir una API Key de TMDB para cargar las películas.
Pasos para obtenerla:
1. Acceder a la web de TMDB e iniciar sesión.
2. Ir a tu perfil → Configuración → API.
3. Solicitar una API Key y completar el formulario.
4. En tu archivo .env, añadir: TMDB_KEY=tu_api_key


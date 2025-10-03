# Gestor de Tareas - Prueba Técnica

Este proyecto es un gestor de tareas desarrollado con **Laravel (backend)** y **Vue 3 + Vite + Bootstrap (frontend)**.

## Requisitos

- Node.js >= 18
- npm >= 9
- PHP >= 8.1
- Composer
- MySQL

---

## Instalación y ejecución

### 1. Clona el repositorio

```bash
git clone https://github.com/rcerquera/Gestor-Tareas-PTecnica.git
cd Gestor-Tareas-PTecnica
```

---

### 2. Backend (Laravel)

#### a. Instala dependencias

```bash
cd backend
composer install
```

#### b. Copia y configura el archivo de entorno

```bash
cp .env.example .env
```

- Configura tus credenciales de base de datos en `.env`:

  ```
  DB_CONNECTION=mysql
  DB_HOST=tu_host
  DB_DATABASE=gestor_tareas
  DB_USERNAME=tu_usuario
  DB_PASSWORD=tu_password
  ```

#### c. Genera la clave de la aplicación

```bash
php artisan key:generate
```

#### d. Ejecuta las migraciones

```bash
php artisan migrate 
```

#### e. Inicia el servidor de desarrollo

```bash
php artisan serve
```

- El backend estará disponible en: `http://127.0.0.1:8000`

---

### 3. Frontend (Vue 3 + Vite)

#### a. Instala dependencias

```bash
cd ../fronted
npm install
```

#### b. Configura la variable de entorno para la URL del backend

Crea un archivo `.env` en la carpeta `fronted` con el siguiente contenido:

```
VITE_API_BASE_URL=http://127.0.0.1:8000
```

> **Nota:** Si el backend corre en otro puerto o dominio, actualiza la URL.

#### c. Inicia el servidor de desarrollo

```bash
npm run dev
```

- El frontend estará disponible en: `http://localhost:5173` (o el puerto que indique Vite).

---

## Uso

- Accede al frontend en tu navegador.
- Puedes crear tareas, asociar palabras clave, filtrar y cambiar estados.
- La comunicación entre frontend y backend se realiza mediante la variable de entorno `VITE_API_BASE_URL`.

---

## Personalización

- Para cambiar la URL del backend, edita el archivo `.env` en `fronted`.
- Los estilos están basados en Bootstrap 5 y Bootstrap Icons.

---

## Troubleshooting

- Si tienes problemas de CORS, asegúrate de configurar correctamente los headers en Laravel.
- Si cambias la URL del backend, reinicia el servidor de Vite.

---

---

**Desarrollado por: Roberto Cerquera**
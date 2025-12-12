# Proyecto Backend - Instrucciones de ejecución

Este repositorio contiene un backend Laravel para manejar el ABM (Altas/Bajas/Modificaciones) de candidaturas, listas, mesas y telegramas, además de cálculos de bancas y funciones de importación en CSV/JSON.

**Contenido**
- **API REST** para Candidatos, Listas, Mesas, Telegramas y Provincias
- **Import** por CSV/JSON a través del endpoint `/api/import`
- **Validación de dominio**: La lógica de consistencia de un telegrama (no exceder electores y evitar duplicados por `id_mesa` + `lista`) se valida en `app/Models/Telegramas.php`.
- Arquitectura: Controller → Service → Repository → DAO → Model

**Requisitos**
- PHP >= 8.x
- Composer
- Node.js y npm
- Base de datos MySQL / MariaDB (u otra soportada por Laravel)
- Opcional: Docker y Laravel Sail

**Instalación (Local)**
1. Clonar el repositorio:

```powershell
git clone <repo-url>
cd laravel-backend
```

2. Instalar dependencias de PHP:

```powershell
composer install
```

3. Generar `.env` y la clave de la aplicación:

```powershell
copy .env.example .env
php artisan key:generate
```

4. Configurar la conexión a la base de datos en `.env` (`DB_*`).

5. Ejecutar migraciones y seeders (población inicial):

```powershell
php artisan migrate
```

Si quieres un reset total (pierde datos actuales):

```powershell
php artisan migrate:fresh
```

6. Instalar dependencias de front-end y compilar activos (si necesario):

```powershell
npm install
npm run dev
```

**Iniciar servidor (local)**

```powershell
php artisan serve
```

Acceder a la app: `http://127.0.0.1:8000`

Si usas Docker / Sail:

```bash
# En WSL o Linux
./vendor/bin/sail up -d
./vendor/bin/sail exec app php artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

**Importar CSV o JSON**

- Endpoint: `POST /api/import` (form-data con key `archivo`).
- Ejemplo CURL (CSV):

```bash
curl -X POST -F "archivo=@/ruta/a/archivo.csv" http://127.0.0.1:8000/api/import
```

- Ejemplo CURL (JSON):

```bash
curl -X POST -F "archivo=@/ruta/a/archivo.json" http://127.0.0.1:8000/api/import
```

Importante: `ImportService` ahora valida consistencia de telegramas y, si existe un `telegrama` con la misma `id_mesa` y `lista`, lo actualizará en lugar de crear un duplicado. Los intentos que violen reglas de dominio (sobrepasar `electores`, duplicados exactos, etc.) serán reportados por fila en la respuesta JSON.

**API - Rutas principales**

- Candidatos: `/api/candidatos` (GET/POST/PUT/DELETE)
- Listas: `/api/listas` (GET/POST/PUT/DELETE)
- Mesas: `/api/mesas` (GET/POST/PUT/DELETE)
- Telegramas: `/api/telegramas` (GET/POST/PUT/DELETE)
- Provincias: `/api/provincias` (ABM)
- Import: `/api/import` (POST - CSV/JSON file)
- Resultados y cálculo de bancas disponibles en `/api/resultados*` y `/api/calculoBancas`

Nota: Las rutas exactas y controladores están en `routes/api.php`.

**Tests**

Ejecutar los tests:

```powershell
php artisan test
```

O si prefieres PHPUnit directamente:

```powershell
vendor/bin/phpunit
```

**Notas importantes**

- El `ImportService` realiza validación por fila y devolverá un listado de errores con la fila y la razón en la estructura JSON (`errors`).
- El modelo `Telegramas::validarConsistencia` valida que los votos no excedan los `electores` de la `mesa` y previene duplicados de `id_mesa` + `lista`. Además, el import ahora actualiza los telegramas cuando ya existen.
- Asegúrate de configurar correctamente `.env` y las credenciales de la BD antes de ejecutar migraciones o importaciones.

**Cómo contribuir**

- Crear una rama para su feature/bugfix.
- Ejecutar tests antes de abrir MR/PR.
- Documentar cambios relevantes en el código y tests.

**Contacto**

- Si necesitas ayuda para ejecutar el proyecto, describe los pasos que realizaste y cualquier error y lo revisamos.


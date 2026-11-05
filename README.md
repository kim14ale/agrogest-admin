# AgroGest · Administración MVC

Sistema web para gestionar el **engorde de ganado bovino** (terneros, toretes, novillos y toros) de una hacienda.
Este repositorio contiene la **Administración** que alimenta al **Core** del proyecto: el *Motor de Rendimiento del Engorde*.

> **Core aprobado:** a partir de los pesajes, la alimentación y los tratamientos, el sistema calcula la ganancia diaria de peso (regresión lineal), la ración ajustada, la dosis exacta de medicamentos, el índice de carga sanitaria, el Índice de Eficiencia del Engorde (IEE) y el punto óptimo de venta.
> La administración registra los datos con los que trabajan esas fórmulas.

**Demo:** `https://<tu-servicio>.onrender.com` (ver [Despliegue](#despliegue))

| Usuario | Correo | Contraseña |
|---|---|---|
| Administradora | `admin@agrogest.com` | `Admin2026*` |
| Veterinario (sin acceso al admin) | `vet@agrogest.com` | `Vet2026*` |

---

## Tecnologías

- **Laravel 12** (PHP 8.2+) con arquitectura **MVC**
- **SQLite** por defecto (también funciona con **MySQL**, cambiando el `.env`)
- Vistas **Blade** + **Bootstrap 5** (incluido en `public/css`, no depende de un CDN)
- **Docker** para el despliegue

## Módulos de la administración

| Módulo | Qué guarda | Cómo alimenta al Core |
|---|---|---|
| Razas | Razas de carne | Comparar rendimiento por raza |
| Etapas de engorde | Rango de peso, consumo diario, GDP esperada, CA objetivo | Ración ajustada, componente de crecimiento del IEE |
| Alimentos | Materia seca, costo por kg | Ración en kg de alimento, costo diario |
| Lotes | Corrales, capacidad, alimento asignado | Reparto del alimento por corral |
| Medicamentos | Dosis por kg, dosis máxima, intervalo mínimo, días de retiro | Dosis exacta, bloqueo de venta, ICS |
| Animales | Arete, raza, lote, peso y costo de ingreso, peso objetivo | Punto de partida de todas las fórmulas |
| Pesajes | Peso y fecha de cada animal | **GDP por regresión lineal** (ya se muestra en la lista de animales) |
| Parámetros del Core | Precio en pie, umbral del ICS, λ, margen objetivo, cambio máximo diario de peso | Constantes de las fórmulas |

---

## 🚨 Validación en el back-end de datos sensibles

Todas las validaciones están en **Form Requests** (`app/Http/Requests`). Se ejecutan **en el servidor, antes de grabar en la base de datos**.
Los formularios tienen `novalidate`, así que el navegador no bloquea nada: lo que se ve en pantalla es la respuesta del servidor.

### Dato sensible principal: el **peso** · `app/Http/Requests/WeighingRequest.php`

Todo el Core depende del pesaje. Un error de digitación (por ejemplo `3560` en lugar de `356`) dañaría la GDP, la ración, la dosis, el IEE y la recomendación de venta. Por eso el servidor verifica:

1. El peso está entre 30 y 1 200 kg.
2. **Coherencia biológica:** comparado con el pesaje anterior, el animal no puede subir ni bajar más de `max_daily_change_kg` kg por día (3 kg por defecto, configurable).
   *Ejemplo:* NOV-015 pesaba 356 kg el 20/10; registrar 500 kg el 04/11 se rechaza (9,6 kg/día).
3. La fecha no es futura ni anterior al ingreso del animal.
4. Un solo pesaje por animal y por día.
5. El animal pertenece al lote elegido y sigue en engorde (evita que alguien manipule el formulario).

### Otros datos sensibles validados en el servidor

| Dato | Archivo | Regla |
|---|---|---|
| Arete del animal | `AnimalRequest.php` | Formato `AAA-000`, se pasa a mayúsculas y debe ser único |
| Capacidad del lote | `AnimalRequest.php`, `LotRequest.php` | No se puede asignar un animal a un lote lleno |
| Peso de ingreso | `AnimalRequest.php` | Debe existir una etapa de engorde para ese peso; el peso objetivo debe ser mayor |
| Días de retiro | `MedicineRequest.php` | Antibióticos, antiinflamatorios y antiparasitarios: mínimo 1 día |
| Dosis | `MedicineRequest.php` | Dosis por kg > 0 y dosis máxima ≥ dosis por kg |
| Etapas | `FeedingStageRequest.php` | Los rangos de peso no pueden cruzarse |
| Claves foráneas | Todos | `exists:` comprueba que la raza, el lote o el alimento existan |

---

## Dropdowns para las relaciones (sin escribir claves foráneas)

- Ninguna clave foránea se escribe a mano: raza, lote, alimento y tipo de medicamento se eligen en un `<select>` (componente `resources/views/components/select.blade.php`).
- **Dropdown en cascada Lote → Animal** al registrar un pesaje (igual que país → provincia → ciudad):
  1. Se elige el **lote**.
  2. JavaScript llama a `GET /admin/lots/{lote}/animals` (`WeighingController@animalsByLot`), que responde en JSON.
  3. El dropdown de **animales** se llena solo con los animales activos de ese lote.
  4. El servidor vuelve a comprobar que el animal pertenezca al lote antes de guardar.

---

## Estructura MVC

```
app/
├── Models/                      → Modelos Eloquent (M): Animal, Weighing, Medicine, Lot, Feed, FeedingStage, Breed, ...
├── Http/
│   ├── Controllers/Admin/       → Controladores (C): un CRUD por módulo
│   ├── Requests/                → 🚨 Validaciones del back-end
│   └── Middleware/EnsureAdmin   → Solo el rol administrador entra al admin
├── Services/PerformanceService  → Core: GDP por regresión lineal
└── Casts/PlainDate              → Guarda las fechas como Y-m-d en MySQL y SQLite
resources/views/                 → Vistas Blade (V)
database/migrations/             → Las 13 tablas del diagrama entidad-relación
database/seeders/                → Datos de ejemplo del Documento de Análisis (NOV-015)
routes/web.php                   → Rutas
```

---

## Instalación local

Requisitos: PHP 8.2 o superior, Composer y Git. En Windows lo más simple es instalar **[Laragon](https://laragon.org)**, que ya incluye PHP y Composer.

```bash
git clone https://github.com/<tu-usuario>/agrogest-admin.git
cd agrogest-admin
composer install
cp .env.example .env          # en Windows (cmd): copy .env.example .env
php artisan key:generate
php artisan migrate --seed    # si pregunta por crear database.sqlite, responder "yes"
php artisan serve
```

Abrir `http://127.0.0.1:8000` e ingresar con `admin@agrogest.com` / `Admin2026*`.

### Usar MySQL en lugar de SQLite

En el `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agrogest
DB_USERNAME=root
DB_PASSWORD=
```

Crear la base `agrogest` y ejecutar `php artisan migrate:fresh --seed`.

---

## Despliegue

El proyecto incluye `Dockerfile`, `docker/start.sh` y `render.yaml`.

**Render (gratis):**

1. Crear una cuenta en [render.com](https://render.com) con GitHub.
2. **New → Blueprint** y elegir este repositorio. Render lee `render.yaml`.
3. Esperar a que termine el build (5 a 10 minutos) y abrir la URL `https://agrogest-admin-xxxx.onrender.com`.

Al arrancar, `docker/start.sh` crea la base SQLite, ejecuta las migraciones y carga los datos de ejemplo si la base está vacía.

> En el plan gratuito de Render el disco no es permanente: al reiniciarse el servicio se vuelven a cargar los datos de ejemplo. Para datos permanentes se usa una base MySQL o PostgreSQL externa configurando `DB_CONNECTION` y las variables `DB_*`.

---

## Autora

Proyecto universitario · Ingeniería Web · 2026

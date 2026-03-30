# Sales ETL & Analytics System

Sistema de análisis de ventas retail con procesamiento masivo de archivos CSV y generación de reportes agregados para análisis de información.

---

# 1. Instalación y ejecución

## Requisitos del sistema

Para ejecutar el proyecto en un entorno local se requiere:

- PHP 8.2 o superior
- Composer
- Node.js y NPM
- Base de datos MySQL o PostgreSQL
- Servidor web local

---

## Clonar el repositorio

```bash
git clone https://github.com/usuario/sales-etl-analytics.git
cd sales-etl-analytics
```

## Instalación de dependencias
```bash
composer install
```

```bash
npm install
```

## Configuración de variables de entorno

### Configurar la conexión a la base de datos en el archivo .env, ejemplo:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sales_etl
DB_USERNAME=root
DB_PASSWORD=
```
### Generar clave de la aplicación
```bash
php artisan key:generate
```

## Ejecutar migraciones de base de datos
```bash
php artisan migrate
```

## Configuración de colas (procesamiento segundo plano)
En el archivo .env configurar:

```bash
QUEUE_CONNECTION=database
```

Iniciar el "worker"
```bash
php artisan queue:work
```

## Inicializar proyecto
Iniciar servidor de desarrollo

```bash
php artisan serve
```

Compilar recursos frontend:
```bash
npm run dev
```

# 2. Decisiones técnicas
- El sistema implementa un flujo ETL para el procesamiento de archivos CSV de gran tamaño.
- Se implementó el uso de batch inserts para así reducir el número de consultas a la base de datos.
- No se carga todo el archivo en memoria, esto reduce considerablemente el uso de recursos manteniendo así una buena experiencia de usuario, gracias al uso de Jobs de Laravel.
- Dado que se pueden procesar miles de registros, se implementó una limpieza periódica de memoria cada 1000 registros con gc_collect_cycles.
- Separación de responsabilidades clara: Controller (recepción de la solicitud HTTP); Service (lógica de negocio); Jobs (procesamiento ASÍNCRONO de archivos);  Models (interacción con la base de datos).

## Propuesta de escalabilidad
Ya que el sistema utiliza Jobs de Laravel, se puede escalar horizontalmente el procesamiento con el uso de "workers" ejecutándose en paralelo, dando así la facilidad de procesar varios archivos a la vez y reducir el tiempo de respuesta. En el caso de que haya la necesidad de procesar archivos de millones de registros, el procesamiento puede dividirse en "chunks", para que varios Jobs procesen el archivo de forma paralela.

En entornos productivos podría implementarse un sistemas de colas más robusto como Redis, permitiendo así un mejor manejo de volúmenes concurrentes.

# Documentación de API

La API fue diseñada siguiendo principios REST y cuenta con **versionamiento de endpoints** utilizando el prefijo `/api/v1`.

La estructura de las respuestas se implementaron mediante API Resources de Laravel para mantener una estructura limpia y estandarizada de los datos.

Se garantiza la integridad de los datos mediante relaciones en cascada para el caso de eliminación.

---

## Endpoints disponibles

### Importar archivo CSV

Permite cargar un archivo CSV.

**Endpoint**

POST /api/v1/imports

**Descripción**

Recibe un archivo CSV y lo almacena en el sistema para su posterior procesamiento.

---

### Listar importaciones

Permite visualizar todas las importaciones realizadas.

**Endpoint**

GET /api/v1/imports

---

### Obtener importaciones con conteo de errores

Devuelve las importaciones registradas con el número de errores que contenga el archivo.

**Endpoint**

GET /api/v1/imports

---

### Visualizar errores de una importación

Permite consultar los errores detectados durante el procesamiento de un archivo específico.

**Endpoint**

GET /api/v1/imports/{id}/errors

**Ejemplo**

GET http://127.0.0.1:8000/api/v1/imports/2/errors

---

### Eliminar una importación

Permite eliminar una importación específica.

**Endpoint**

DELETE /api/v1/imports/{id}

**Ejemplo**

DELETE http://127.0.0.1:8000/api/v1/imports/3

---

### Obtener resumen de reportes

Devuelve información agregada basada en los registros procesados de una importación.

**Endpoint**

GET /api/v1/reports/summary

**Parámetros**

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| import_id | integer | Identificador de la importación |

**Ejemplo**

GET http://127.0.0.1:8000/api/v1/reports/summary?import_id=1

**Información devuelta**

- ingresos totales
- top 5 productos por ingresos
- distribución por categoría
- distribución geográfica




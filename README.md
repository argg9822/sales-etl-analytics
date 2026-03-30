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


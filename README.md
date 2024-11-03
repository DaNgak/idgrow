<!-- ![Logo]() -->

<h1 align="center">Mutation Product App</h1>

## Table of Contents

1. [Tech Stack](#tech-stack)
2. [Description](#description)
3. [Environment Variables](#environment-variables)
4. [Authors](#authors)

## Tech Stack

## **Backend:**

-   Laravel 10
-   MySQL (Database)

## Description

Project ini adalah aplikasi untuk melakukan manajemen data untuk data user, data produk dan juga data mutasi yang sederhana untuk monitoring data.

---

## Used For ?

Project ini ditujukan.

-   Untuk melakukan monitoring data produk

---

## Environment Variables

Untuk menjalankan apps ini harus menyertakan .env yang bisa didapat dari .env.example

<!-- -   ### Backend
    -   `FRONTEND_HOST`
-   ### Frontend
    -   `VITE_PUBLIC_API_BACKEND` -->

---

## Folder Structure

    .                       # root project
    └── ...                 # laravel app

## Installation

Clone the project

```bash
  git clone ...
```

Go to the project directory

```bash
  cd ...
```

---

## Run Locally

### Backend Locally

Install dependencies pada backend app

-   Install dependencies using composer
```bash
  composer install
```

-   Copy .env.example to .env
```bash
  cp .env.example .env
```

-   Generate application key
```bash
  php artisan key:generate
```

-   Set env variables
```bash
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT={local_port_mysql}
  DB_DATABASE=idgrow_product_app
  DB_USERNAME={local_username_mysql}
  DB_PASSWORD={local_password_mysql}
```

-   Run Migration and Seeder
```bash
  php artisan migrate
  php artisan db:seed
```

-   Run Application
```bash
  php artisan serve
```

Open app in browser using url `http://localhost:8000`

### Notes
Untuk data dummy bisa diakses melalui route `http://localhost:8000/seed-dummy/{token}` dimana token adalah `idgrow-test`, untuk url lengkap nya seperti berikut
`http://localhost:8000/seed-dummy/idgrow-test`, dengan menambahkan data dummy pada database table user, product, dan mutasi. <i>Seeding dummy ini bersifat truncate, jadi cukup jalankan sekali saja saat melakukan testing karena jika dijalankan lagi maka data sebelumnya akan terhapus</i>.

### Backend Docker using docker-compose

Build app dependencies

```bash
  docker-compose build
```

Run app using dockker

```bash
  docker-compose up -d
```

Open app in browser using url `http://localhost:8080`

## API Documentation

API Documentation yang ada pada project ini menggunakan postman dengan yang bisa diakses melalui url yang dapat klik [disini](https://documenter.getpostman.com/view/30452531/2sAY4xAhGz)

## Authors

-   [@BengakDev](https://github.com/DaNgak)

Made with ❤️ by BengakDev

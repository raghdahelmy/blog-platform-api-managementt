# Blog API

This is a RESTful Blog API built with Laravel. It allows users to register, login, create and manage blog posts, assign roles, write comments, and filter/search posts. The project is designed for learning purposes and showcases JWT authentication, role-based access, API Resources, and basic caching/testing.

---

## Features

* User registration & login (JWT Auth)
* Role-based access control (Admin / Author)
* CRUD operations for blog posts
* Commenting system
* Filtering & search (by title, category, author, date)
* API Resources for clean responses
* Custom API Response Trait
* Postman collection included

---

## 🚀 Setup Instructions

### 1. Clone the repo:

```bash
git clone https://github.com/raghdahelmy/blog-platform-api-managementt.git
cd blog-platform-api-managementt
```

### 2. Install dependencies:

```bash
composer install
```

### 3. Set environment:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure `.env`:

Set your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=root
DB_PASSWORD=
```

Add JWT secret:

```bash
php artisan jwt:secret
```

### 5. Run migrations and seed roles:

```bash
php artisan migrate
```

Then in Tinker or a seeder:

```php
use Spatie\Permission\Models\Role;
Role::create(['name' => 'admin']);
Role::create(['name' => 'author']);
```

---

## 🛠️ API Endpoints

### Auth:

* `POST /api/register`
* `POST /api/login`
* `POST /api/logout`
* `GET  /api/profile`

### Posts:

* `GET    /api/posts`
* `POST   /api/posts` *(author/admin only)*
* `GET    /api/posts/{id}`
* `PUT    /api/posts/{id}` *(author of post or admin)*
* `DELETE /api/posts/{id}` *(author of post or admin)*

### Comments:

* `POST /api/posts/{id}/comments`

### Filters:

* ?category=Technology
* ?author=1
* ?search=Laravel
* ?from=2025-07-01\&to=2025-07-31

---

## 🧪 Testing

Basic feature test is available for creating a post:

```bash
php artisan test --filter=PostApiTest
```

> 💡 I'm still learning automated testing. Included a basic example for practice.

---

## ⚡ Caching

Implemented caching for the `GET /api/posts` endpoint using Laravel's `Cache::remember`.

> 🧠 Still learning caching concepts — I added it for optimization and practice.

---

## 📂 Postman Collection

File: `Blog-API-Postman-Collection.json` Place it inside the project root directory (`blog-platform-api-managementt/`). You can import it into Postman and test all available endpoints.

---

## 🧑‍💻 Built With

* Laravel 12
* JWT Auth (tymon/jwt-auth)
* Spatie Laravel Permission
* MySQL (SQL-based DB)

---

## 📬 Contact

For questions or feedback, feel free to contact me:

* GitHub: [github.com/raghdahelmy](https://github.com/raghdahelmy)
* Email: [raghda.helmy82@gmail.com](mailto:raghda.helmy82@gmail.com)

---

## ✅ Notes

* Roles are seeded manually via Tinker
* Ensure `.env` is configured before running migrations
* All endpoints require `Authorization: Bearer <token>` except register/login

---
Update README with full setup instructions

## 🏁 Final Notes

This project was developed as part of a technical task to demonstrate understanding of Laravel API structure, authentication, and clean code. Thank you for reviewing 💙

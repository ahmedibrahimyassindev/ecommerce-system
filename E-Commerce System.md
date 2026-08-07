# E-Commerce System

A full-stack E-Commerce application built with **Laravel** for the backend REST API and **Vue.js** for the frontend.

The system provides a complete online shopping experience including authentication, product management, categories, shopping cart, orders, payments, and an admin dashboard.

## Technologies

### Backend

- PHP
- Laravel
- Laravel Sanctum
- MySQL
- RESTful API
- Eloquent ORM
- Form Request Validation
- API Resources
- Database Migrations
- Seeders and Factories

### Frontend

- Vue.js
- Vue Router
- Pinia
- Axios
- JavaScript
- HTML5
- CSS3
- Bootstrap / Tailwind CSS

## Features

### Authentication

- User Registration
- User Login
- User Logout
- Laravel Sanctum Authentication
- Protected API Routes
- User Profile Management

### Products

- Create Product
- Update Product
- Delete Product
- View Product
- List Products
- Product Images
- Product Price
- Product Stock
- Product Status
- Product Search
- Product Filtering
- Product Pagination

### Categories

- Create Category
- Update Category
- Delete Category
- View Categories
- Assign Products to Categories

### Shopping Cart

- Add Product to Cart
- Update Product Quantity
- Remove Product from Cart
- View Cart
- Calculate Cart Total

### Orders

- Create Order
- View User Orders
- View Order Details
- Update Order Status
- Cancel Order

Supported order statuses:

- Pending
- Processing
- Shipped
- Delivered
- Cancelled

### Checkout

The checkout process allows customers to:

1. Review cart items
2. Add shipping information
3. Select payment method
4. Confirm the order
5. Complete payment

### Payments

The project can support multiple payment methods such as:

- Cash on Delivery
- Credit / Debit Card
- Online Payment Gateway

Payment gateway integration can be added depending on project requirements.

### Admin Dashboard

The admin dashboard provides management functionality for:

- Users
- Products
- Categories
- Orders
- Payments
- Inventory

Dashboard statistics can include:

- Total Users
- Total Products
- Total Orders
- Pending Orders
- Completed Orders
- Total Revenue

## Project Structure

The application is separated into two main parts:

```text
ecommerce-system/
│
├── backend/
│   └── Laravel API
│
└── frontend/
    └── Vue.js Application
```

## Backend Installation

Clone the repository:

```bash
git clone <repository-url>
```

Go to the backend directory:

```bash
cd backend
```

Install PHP dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate Laravel application key:

```bash
php artisan key:generate
```

Configure your database inside `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

Run database migrations:

```bash
php artisan migrate
```

Run seeders if available:

```bash
php artisan db:seed
```

Or run migrations with seeders:

```bash
php artisan migrate --seed
```

Create the storage symbolic link:

```bash
php artisan storage:link
```

Start Laravel development server:

```bash
php artisan serve
```

The backend API will normally be available at:

```text
http://127.0.0.1:8000
```

## Frontend Installation

Go to the frontend directory:

```bash
cd frontend
```

Install dependencies:

```bash
npm install
```

Configure the backend API URL in the frontend environment file:

```env
VITE_API_URL=http://127.0.0.1:8000/api
```

Start the Vue development server:

```bash
npm run dev
```

The frontend will normally be available at:

```text
http://localhost:5173
```

## API Authentication

Laravel Sanctum is used for API authentication.

Example request header:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
Content-Type: application/json
```

## Example API Endpoints

### Authentication

```text
POST   /api/register
POST   /api/login
POST   /api/logout
GET    /api/profile
```

### Products

```text
GET    /api/products
GET    /api/products/{id}
POST   /api/products
PUT    /api/products/{id}
DELETE /api/products/{id}
```

### Categories

```text
GET    /api/categories
GET    /api/categories/{id}
POST   /api/categories
PUT    /api/categories/{id}
DELETE /api/categories/{id}
```

### Cart

```text
GET    /api/cart
POST   /api/cart
PUT    /api/cart/{id}
DELETE /api/cart/{id}
```

### Orders

```text
GET    /api/orders
GET    /api/orders/{id}
POST   /api/orders
PUT    /api/orders/{id}
```

## Database Relationships

Main relationships include:

```text
User
 └── hasMany Orders

Category
 └── hasMany Products

Product
 ├── belongsTo Category
 └── hasMany OrderItems

Order
 ├── belongsTo User
 └── hasMany OrderItems

OrderItem
 ├── belongsTo Order
 └── belongsTo Product
```

## Backend Best Practices

The Laravel backend follows common development practices including:

- RESTful API architecture
- Service-based business logic
- Form Request validation
- API Resources
- Eloquent relationships
- Authentication using Sanctum
- Pagination
- Exception handling
- Proper HTTP status codes
- Database transactions
- Seeders and factories
- Clean and maintainable code

## Frontend Architecture

The Vue.js frontend uses:

- Components for reusable UI
- Vue Router for navigation
- Pinia for state management
- Axios for API communication
- Authentication guards
- Centralized API configuration
- Reusable product and cart components

Example structure:

```text
src/
├── assets/
├── components/
├── views/
├── router/
├── stores/
├── services/
├── layouts/
├── App.vue
└── main.js
```

## Build for Production

Build the Vue.js frontend:

```bash
npm run build
```

Optimize Laravel:

```bash
php artisan optimize
```

You can also cache Laravel configuration and routes:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Security

The project should follow standard security practices including:

- Password hashing
- Laravel Sanctum authentication
- Request validation
- Authorization policies
- CSRF protection where applicable
- SQL injection protection through Eloquent
- Secure file uploads
- Role-based access control
- Environment variables for sensitive configuration

## Future Improvements

Possible future features include:

- Wishlist
- Product Reviews and Ratings
- Coupons and Discount Codes
- Multiple Product Images
- Product Variants
- Inventory Management
- Email Notifications
- Push Notifications
- Order Tracking
- Invoice Generation
- Multiple Payment Gateways
- Multiple Languages
- Multiple Currencies
- Docker Support
- CI/CD Pipeline

## License

This project is available for educational and development purposes.
# ITElect Laravel Backend API

This is a pure backend Laravel API for managing products and inventory transactions. It has no user interface and is intended to be tested using Postman.

## Features

- Product CRUD
- Stock-in transaction
- Stock-out transaction
- Transaction history
- JSON API responses only

## Requirements

Install or prepare these before running the project:

- PHP 8.2 or higher
- Composer
- XAMPP or another local PHP environment
- Postman

## Step 1: Open the Project Folder

Open a terminal or PowerShell, then go to the project folder:

```bash
cd C:\xampp\htdocs\ITElect
```

## Step 2: Install Laravel Dependencies

Run Composer install:

```bash
composer install
```

This downloads the required Laravel packages into the `vendor` folder.

## Step 3: Create the Environment File

If `.env` does not exist, copy the example file:

```bash
copy .env.example .env
```

If `.env` already exists, you can skip this step.

## Step 4: Generate the Application Key

Run:

```bash
php artisan key:generate
```

This sets the Laravel `APP_KEY` value inside `.env`.

## Step 5: Configure the Database

This project uses SQLite by default.

In `.env`, make sure this line exists:

```env
DB_CONNECTION=sqlite
```

Then make sure this file exists:

```text
database/database.sqlite
```

If the file does not exist, create it manually inside the `database` folder.

## Step 6: Run Database Migrations

Run:

```bash
php artisan migrate
```

This creates the required database tables:

- `products`
- `inventory_transactions`

## Step 7: Start the Laravel Server

Run:

```bash
php artisan serve
```

The API will usually run at:

```text
http://127.0.0.1:8000
```

Keep this terminal open while testing in Postman.

## Step 8: Test the API in Postman

Use this base URL:

```text
http://127.0.0.1:8000/api
```

Set request headers:

```text
Accept: application/json
Content-Type: application/json
```

## API Endpoints

### Get All Products

```http
GET /api/products
```

Full Postman URL:

```text
http://127.0.0.1:8000/api/products
```

### Create Product

```http
POST /api/products
```

Body:

```json
{
  "name": "Keyboard",
  "description": "Mechanical keyboard",
  "price": 1500,
  "quantity": 10
}
```

### Get One Product

```http
GET /api/products/{id}
```

Example:

```text
http://127.0.0.1:8000/api/products/1
```

### Update Product

```http
PUT /api/products/{id}
```

Example:

```text
http://127.0.0.1:8000/api/products/1
```

Body:

```json
{
  "name": "Keyboard",
  "description": "Updated mechanical keyboard",
  "price": 1750,
  "quantity": 15
}
```

You may also use `PATCH` if you only want to update selected fields.

Example:

```json
{
  "price": 1800
}
```

### Delete Product

```http
DELETE /api/products/{id}
```

Example:

```text
http://127.0.0.1:8000/api/products/1
```

## Transaction Endpoints

There are two transaction endpoints.

### Transaction 1: Stock In

Use this when adding stock to a product.

```http
POST /api/products/{id}/stock-in
```

Example:

```text
http://127.0.0.1:8000/api/products/1/stock-in
```

Body:

```json
{
  "quantity": 5,
  "unit_price": 1500,
  "remarks": "Supplier delivery"
}
```

This increases the product quantity and creates an inventory transaction record.

### Transaction 2: Stock Out

Use this when removing stock from a product.

```http
POST /api/products/{id}/stock-out
```

Example:

```text
http://127.0.0.1:8000/api/products/1/stock-out
```

Body:

```json
{
  "quantity": 3,
  "unit_price": 1500,
  "remarks": "Customer order"
}
```

This decreases the product quantity and creates an inventory transaction record.

If the requested quantity is greater than the available stock, the API returns a validation error.

### Get Transaction History

```http
GET /api/transactions
```

Full Postman URL:

```text
http://127.0.0.1:8000/api/transactions
```

## Recommended Postman Testing Order

1. Create a product using `POST /api/products`.
2. Get all products using `GET /api/products`.
3. Copy the product `id` from the response.
4. Add stock using `POST /api/products/{id}/stock-in`.
5. Remove stock using `POST /api/products/{id}/stock-out`.
6. Check transaction records using `GET /api/transactions`.
7. Update the product using `PUT` or `PATCH /api/products/{id}`.
8. Delete the product using `DELETE /api/products/{id}`.

## HTTP Status Codes and Error Handling

The API returns JSON responses for errors.

### Success Responses

- `200 OK`: Request was successful.
- `201 Created`: New product or transaction was created.

### Client Error Responses

- `400 Bad Request`: Invalid JSON request body.
- `404 Not Found`: Product or API resource does not exist.
- `405 Method Not Allowed`: The HTTP method is not allowed for the endpoint.
- `422 Unprocessable Content`: Validation error, such as missing required fields or insufficient stock.

Example `400` response:

```json
{
  "message": "Invalid JSON request body."
}
```

Example `404` response:

```json
{
  "message": "Resource not found."
}
```

Example `422` response:

```json
{
  "message": "The name field is required. (and 1 more error)",
  "errors": {
    "name": [
      "The name field is required."
    ],
    "price": [
      "The price field is required."
    ]
  }
}
```

### Server Error Responses

- `500 Internal Server Error`: Unexpected server failure.

Example `500` response:

```json
{
  "message": "Server error. Please try again later."
}
```

## Run Tests

To verify that the backend works, run:

```bash
php artisan test
```

Expected result:

```text
Tests: 5 passed
```

## Common Problems

### Server Not Running

If Postman cannot connect, make sure this command is still running:

```bash
php artisan serve
```

### Database Error

If tables are missing, run:

```bash
php artisan migrate
```

### Port Already in Use

If port `8000` is already being used, run the server on a different port:

```bash
php artisan serve --port=8001
```

Then use this base URL in Postman:

```text
http://127.0.0.1:8001/api
```

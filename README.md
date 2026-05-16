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
- XAMPP (includes Apache and MySQL)
- MySQL (itelect database)
- Postman

## After Cloning on School PC

After cloning this project from GitHub on the school PC, the `vendor` folder and `.env` file will usually be missing. This is normal because those files are not usually pushed to GitHub.

### Step 1: Clone the Repository

Open PowerShell or Command Prompt, then run:

```bash
git clone YOUR_REPOSITORY_URL
```

Go inside the project folder:

```bash
cd ITElect
```

If you cloned it inside XAMPP, the folder may be:

```bash
cd C:\xampp\htdocs\ITElect
```

### Step 2: Install Dependencies

Run:

```bash
composer install
```

This recreates the missing `vendor` folder.

### Step 3: Create `.env`

Run:

```bash
copy .env.example .env
```

If it says the file already exists, you can continue.

### Step 4: Generate App Key

Run:

```bash
php artisan key:generate
```

### Step 5: Create MySQL Database

1. Open **XAMPP Control Panel** and click **Start** for both Apache and MySQL.
2. Click **Admin** next to MySQL to open **phpMyAdmin**.
3. Create a new database named `itelect`.
    - **Using the UI:** Click "New" on the left, type `itelect`, and click "Create".
    - **Using SQL:** Click the "SQL" tab and run: `CREATE DATABASE itelect;`

### Step 6: Run Migrations

Run:

```bash
php artisan migrate
```

This creates the database tables.

### Step 7: Start the API Server

Run:

```bash
php artisan serve
```

Use this URL in Postman:

```text
http://127.0.0.1:8000/api
```

Example endpoint:

```text
http://127.0.0.1:8000/api/products
```

### Quick School PC Setup Commands

Use this if you want the short version:

```bash
git clone YOUR_REPOSITORY_URL
cd ITElect
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

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

This project uses MySQL by default.

1. Open **XAMPP Control Panel** and ensure MySQL is **Started**.
2. Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`).
3. Create an empty database named `itelect`.
4. In your `.env` file, ensure these lines match your MySQL setup:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itelect
DB_USERNAME=root
DB_PASSWORD=
```

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

## Running This Project on Another PC

When you move this Laravel project to another computer, expect that some generated files may be missing or different. This is normal.

### What to Expect

- The `vendor` folder may be missing.
- The `.env` file may be missing.
- The Laravel app key may be empty.
- The MySQL server may not be running.
- The `itelect` database may not have been created yet.
- The database tables may not exist yet.
- The other PC may not have PHP, Composer, or required PHP extensions installed.
- Port `8000` may already be used by another program.

### Full Setup Fix for Another PC

After copying the project folder to the other PC, run these commands from inside the project folder:

```bash
cd C:\xampp\htdocs\ITElect
composer install
copy .env.example .env
php artisan key:generate
```

Ensure MySQL is running and you have created the `itelect` database.

Then run migrations:

```bash
php artisan migrate
```

Finally, start the server:

```bash
php artisan serve
```

Use this in Postman:

```text
http://127.0.0.1:8000/api
```

### If `copy .env.example .env` Says the File Already Exists

That is okay. It means the `.env` file is already there. Continue with:

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

### If `composer` Is Not Recognized

Composer is not installed or is not added to the system PATH.

Fix:

1. Install Composer from `https://getcomposer.org/download/`.
2. Close and reopen the terminal.
3. Run:

```bash
composer --version
```

If it shows a Composer version, run:

```bash
composer install
```

### If `php` Is Not Recognized

PHP is not installed or XAMPP PHP is not added to the system PATH.

Fix:

1. Install XAMPP.
2. Add this folder to the system PATH:

```text
C:\xampp\php
```

3. Close and reopen the terminal.
4. Run:

```bash
php --version
```

If it shows PHP 8.2 or higher, continue the setup.

### If MySQL Has an Error

If you see an error about MySQL, `pdo_mysql`, or database driver not found, ensure the MySQL extension is enabled in PHP.

Fix:

1. Open this file:

```text
C:\xampp\php\php.ini
```

2. Find this line:

```ini
;extension=pdo_mysql
```

3. Remove the semicolon `;` from the line:

```ini
extension=pdo_mysql
```

4. Save the file.
5. Restart your Apache/MySQL server if using XAMPP.
6. Run:

```bash
php artisan migrate
```

### If Database Is Missing

Open **phpMyAdmin** and create a database named `itelect`.

**SQL Query:**
```sql
CREATE DATABASE itelect;
```

Then run migrations:
```bash
php artisan migrate
```

### If Tables Are Missing

Run:

```bash
php artisan migrate
```

If you want to reset the database and remove existing data, run:

```bash
php artisan migrate:fresh
```

### If Port 8000 Is Already Used

Run Laravel on another port:

```bash
php artisan serve --port=8001
```

Then use this base URL in Postman:

```text
http://127.0.0.1:8001/api
```

### If You Get `500 Internal Server Error`

Run these commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate
```

Then start the server again:

```bash
php artisan serve
```

If the error continues, check the Laravel log file:

```text
storage/logs/laravel.log
```

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
Tests: 9 passed
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

# Setup Guide

## Step 1. Setup Environment
Prepare the following environment
* PHP >= 7.3
* MySQL >= 5.6

Install dependencies via Composer
```
composer install
```

## Step 2. Update Environment Variables
Copy the .env file from template
```
cp .env.example .env
```

Update the following variables
* APP_KEY=`base64:qwu7xXJ6uAyV2oktMJ7gFH0ODDNUqB/klr4CXEUeVb0=`
* DB_HOST=`{Your own value}`
* DB_PORT=`{Your own value}`
* DB_DATABASE=`{Your own value}`
* DB_USERNAME=`{Your own value}`
* DB_PASSWORD=`{Your own value}`
* STRIPE_PUBLISHABLE_KEY=`pk_test_51GrNTzFfD3q8v0N2ObYGTbMd9HFtdaISoVeFqzF8Kd132ktispYHBsXIMe676SrLTKbsXUU2Fd3PdThiB3SzXd7P00tlYxoVjs`
* STRIPE_SECRET_KEY=`sk_test_51GrNTzFfD3q8v0N20rGlies6mMbRlNdOVV8VWjL93V7X3T96JNob5CVLhr5TpfeNnjoCRInxNvlZtreW2KgkSWhF00zTm49bBK`

## Step 3. Run Database Migrations
```
php artisan migrate
```

## Step 4. Run Local Development Server
```
php artisan serve
```

Visit `localhost:8000`

# Data Structure
Tables: `customers`, `payments`

Such information are stored in Stripe as well. For simplicity, only necessary copies of data (such as customer email for order searching) are stored in database.

## Customers
* id: int
* stripe_ref: string
* name<sup>#</sup>: string
* email: string
* phone<sup>#</sup>: string
* created_at: timestamp
* updated_at: timestamp

<sup>#</sup>Name and phone are updated every time a customer creates a new order using the same email.

## Payments
* id: int
* stripe_ref: string
* customer_id: int, FK to `customers.id`
* created_at: timestamp
* updated_at: timestamp

# Order Service API

The Order Service API is a part of an e-commerce system built with Laravel, designed for order management and discount algorithms. This project is containerized using Docker and integrates with a MySQL database.

## Technologies

- **Laravel 11** - PHP Framework
- **Docker** - Containerized Application
- **MySQL** - Database
- **PHP 8.x** - Backend Server
- **Docker Compose** - Management of Multi-Container Applications

## Getting Started

Follow the instructions below to set up the project:

### Requirements

- Docker & Docker Compose
- PHP 8.x or higher
- MySQL 8.x

### Installation

1. **Clone the Project Repository**

   Clone the project files from GitHub:
   ```bash
   git clone https://github.com/username/order-service.git
   cd order-service
   ```

2. **Start with Docker Compose**

   The project can be easily run with Docker Compose. Use the following command to start all required containers:
   ```bash
   docker-compose up -d --build
   ```

   This command will:
   - Start the container for the Laravel application with PHP and web server.
   - Start the MySQL container.
   - Initialize the MySQL database as `orderservice`.

3. **Configure the .env File**

   You may need to update the `.env` file with your database connection information. Example configuration:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql_db
   DB_PORT=3306
   DB_DATABASE=orderservice
   DB_USERNAME=root
   DB_PASSWORD=orderservice
   ```

4. **Run Database Migrations**

   To create the necessary database tables, run the following command:
   ```bash
   docker-compose exec app php artisan migrate
   ```

   This will create the required database schema.

5. **Seed Test Data**

   If you wish to populate the database with sample data, you can run the following command:
   ```bash
   docker-compose exec app php artisan db:seed
   ```

   This will insert sample records into the database for testing.

6. **Access the Application**

   Once the containers are up and running, you can access the Laravel application through your browser at:
   ```bash
   http://localhost:8000
   ```

   You can also use the API by making requests to:
   ```bash
   http://localhost:8000/api
   ```

## Swagger Documentation

This API is documented using Swagger, which provides an interactive interface to explore and test the API endpoints.

1. **Access Swagger UI**

   After starting the application, you can view the Swagger documentation at the following URL:
   ```bash
   http://localhost:8000/api/documentation
   ```

   The Swagger UI will allow you to:
   - View all available API endpoints.
   - See detailed information about each endpoint, including parameters, responses, and possible errors.
   - Test API endpoints directly from the UI.

2. **Swagger Setup**

   The Swagger setup is integrated using the [Laravel Swagger](https://github.com/DarkaOnLine/L5-Swagger) package. If you want to modify or add more documentation for specific API routes, you can do so by editing the annotations in the controllers. For example:
   ```php
   /**
    * @OA\Get(
    *     path="/api/orders",
    *     summary="Get all orders",
    *     @OA\Response(
    *         response="200",
    *         description="List of orders"
    *     )
    * )
    */
   ```

   Once changes are made, you can regenerate the Swagger documentation by running:
   ```bash
   docker-compose exec app php artisan l5-swagger:generate
   ```

## Features

- **Order Management**: Manage customer orders with discounts applied.
- **Discount Algorithm**: Supports various discount strategies (e.g., "BUY_5_GET_1").
- **Dockerized Environment**: The entire application runs in isolated containers for easier setup and management.
- **MySQL Database**: Stores orders and discount information.
- **Swagger Documentation**: Interactive API documentation for easy exploration and testing.

### Customizing the Discount Logic

The discount logic is implemented using a custom service provider. You can modify or add new discount strategies in the `app/Services/DiscountService.php` file.

### Contributing

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature-name`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature-name`).
5. Create a new Pull Request.

## License

This project is open-source and available under the [MIT License](LICENSE).

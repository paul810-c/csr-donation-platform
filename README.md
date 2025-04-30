# CSR Donation Platform

A Corporate Social Responsibility (CSR) donation platform built with Laravel 12, designed for internal use within organizations.
It allows employees to create and manage donation campaigns.
---

# Project Setup and Usage

---

## Running the Project

1. Clone repo:
   ```bash
    git clone https://github.com/paul810-c/csr-donation-platform.git
    cd csr-donation-platform
   ```

2. Copy and configure the `.env` file:
   ```bash
   cp .env.example .env
   ```

3. Build and Start the Docker Container:
   ```bash
   docker-compose up --build
   ```

4. Connect to the Container Shell:
   ```bash
   docker exec -it csr_app sh
   ```

5. Install Dependencies:
   ```bash
   composer install
   ```

6. Generate the application key:
   ```bash
   php artisan key:generate
   ```
   
7. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
   
---

## API Documentation

http://localhost:8080/api/documentation

---

## Running the Tests

   ```bash
   ./vendor/bin/pest
   ```
- **Feature tests:** API endpoint coverage

- **Unit tests:** Handlers, domain logic

---

## Code Quality Tools

This project uses **PHPStan** for static analysis and Laravel Pint

- **Run PHPStan**:
  ```bash
  ./vendor/bin/phpstan analyse
  ```
 - **--memory-limit=512M might be needed**


- **Laravel Pint**:
  ```bash
  ./vendor/bin/pint
  ```
  
## Project Architecture

The project is organized into several layers to keep things clean, modular and easy to extend.

- **Domain Layer**  
  This is the business logic.
  It contains entities (like `Campaign`, `Donation`), value objects (like `Money`), enums, and repository interfaces. 
  No Laravel stuff here

- **Application Layer**  
  Contains "use case" handlers.
  These classes coordinate actions like creating a campaign.

- **Infrastructure Layer**  
  This is the technical side: how data is actually saved, event listeners etc.
  It connects the pure Domain logic to real databases, queues etc.

- **Presentation Layer (HTTP)**  
  Controllers and routes
  This is where external requests come into the system, like an entrypoint 

---


## Todos / Improvements

- [ ] Setup up supervisord
- [ ] Refactor docker entrypoint
- [ ] Create Makefile for tasks
- [ ] Implement payment
- [ ] Fix the Async email sending

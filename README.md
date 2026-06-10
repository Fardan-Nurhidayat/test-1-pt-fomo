# Test Assesment PT FOMO Inovasi Teknologi

Name : Fardan Nurhidayat

Email : fardannurhidayat12@gmail.com

Link Repository : https://github.com/Fardan-Nurhidayat/test-1-pt-fomo

API Docs :

- Link : "https://opd5rlqrg8.apidog.io/"
- Password : "YWRkuRv6"

### Instalation Guide

1. Clone the repository
2. Install dependencies using `npm install`
3. Create a `.env` file in the root directory and add the following environment variables:
    ```
    DB_HOST=your_database_host
    DB_USER=your_database_user
    DB_PASSWORD=your_database_password
    DB_NAME=your_database_name
    ```
4. Run the database migrations using `php artisan migrate`
5. Start the server using `php artisan serve`
6. Seed the database with initial data using `php artisan db:seed`
7. You can now access the API at `http://localhost:8000/api`
8. Run a pest for testing using `vendor/bin/pest tests/Race/`
   For handling the race condition, I have implemented a locking database table to ensure that only one process can access the critical section of code that updates the stock quantity at a time. When a request to purchase an item is made, the system will acquire a lock on the corresponding stock record before proceeding with the update. This ensures that if multiple requests are made simultaneously, they will be processed sequentially, preventing any race conditions and ensuring data integrity.

# Local Setup Guide

Follow these steps to set up your Laravel project locally:

1. **Copy .env from .env.example**:

    ```sh
    cp .env.example .env
    ```

2. **Run Docker**:

    ```sh
     docker-compose up -d --build
    ```

3. **Open docker workspace**:

    ```sh
     docker exec -it aimm_php bash
    ```

4. **Install Composer Dependencies**:

    ```sh
     composer install --ignore-platform-reqs
    ```

5. **Generate an App Encryption Key**:

    ```sh
     php artisan key:generate
    ```

6. **Generate an JWT_SECRET Key**:

    ```sh
     php artisan jwt:secret
    ```

7. **Run Migrations**:

    ```sh
     php artisan migrate
    ```

8. **Seed the Database**:

    ```sh
     php artisan db:seed
    ```

# White Usagi API

## Description

The White Usagi API serves as the backend for managing a collection of anime-related data. It provides a structured and efficient way to access and manipulate information about anime titles, their associated genres, the studios that produce them, individual episodes, and user accounts. This API is designed to be consumed by frontend applications to create rich and interactive user experiences for anime enthusiasts. It also includes user authentication and profile management functionalities.

## Installation

To get the White Usagi API running on your local machine or server, please follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/Danny-LM/White-Usagi-API.git
    cd White-Usagi-API
    ```

2.  **Install Composer dependencies:**
    Ensure you have [Composer](https://getcomposer.org/) installed globally on your system. Navigate to the project directory in your terminal and run:
    ```bash
    composer install
    ```
    This command will download and install all the necessary PHP packages and libraries, including the Laravel framework.

3.  **Copy the environment file:**
    Laravel uses an `.env` file to manage environment-specific configuration. Copy the `.env.example` file to `.env`:
    ```bash
    cp .env.example .env
    ```

4.  **Create a database:**
    You will need to create a database for the White Usagi API using your preferred database management tool (I used MySQL if you prefer to use the same).

5.  **Configure the `.env` file:**
    Open the `.env` file in your text editor and configure your database connection details. **Ensure you replace the placeholder values with your actual database credentials (database name, username, password, host, port).** You may also need to adjust other environment variables as needed.

    ```ini
    APP_NAME='White Usagi'
    APP_ENV=local
    APP_KEY=your-secret-key-here
    APP_DEBUG=true
    APP_URL=http://localhost

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password

    MAIL_MAILER=your-mail-mailer-here
    MAIL_HOST=your-mail-host-here
    MAIL_PORT=your-mail-port-here
    MAIL_USERNAME=your-mail-username-here
    MAIL_PASSWORD=your-mail-password-here
    MAIL_ENCRYPTION=your-mail-encryption-here
    MAIL_FROM_ADDRESS=your-mail-address-here
    MAIL_FROM_NAME="${APP_NAME}"
    ```
    *(Remember to generate a new application key after installation using `php artisan key:generate`)*

6.  **Generate application key:**
    Run the following Artisan command to generate a unique application key for your Laravel instance:
    ```bash
    php artisan key:generate
    ```

7.  **Run database migrations:**
    To set up the database schema in the database you created, execute the migrations:
    ```bash
    php artisan migrate
    ```

8.  **Run database seeders:**
    To populate the database with initial data (e.g., some sample genres or studios), you should run the database seeders:
    ```bash
    php artisan db:seed
    ```

9.  **Serve the application:**
    Start the Laravel development server using the Artisan command:
    ```bash
    php artisan serve
    ```
    This will typically make your API accessible at `http://127.0.0.1:8000`.

## Technologies

The White Usagi API is built using the following primary technologies:

* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg" height="30" alt="laravel logo" align="center" />  **Laravel** Version 10.48.29 and the library and the library **Laravel Sanctum:** Version 3.3.3
* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" height="30" alt="php logo" align="center" /> **PHP** Version 8.1.10
* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/composer/composer-original.svg" height="30" alt="composer logo" align="center" />  **Composer** Version 2.4.1
* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg" height="30" alt="mysql logo" align="center" />  **MySQL** Version 8.0.30

## Instructions for Use

The White Usagi API follows RESTful principles for its endpoints. Here's a general overview of how to interact with the API. Please refer to the specific API documentation (which you might generate later using tools like Swagger/OpenAPI) for detailed information on request parameters, response formats, and authentication (if implemented).

**Base URL:** `http://127.0.0.1:8000/api` *(This might vary depending on your configuration)*

**Authentication Endpoints:**
* `POST /api/register`: Register a new user. Requires `name`, `email`, `password`, and `password_confirmation`. Returns an `access_token`.
* `POST /api/login`: Log in an existing user. Requires `email` and `password`. Returns an `access_token`.
* `POST /api/logout` (protected): Log out the currently authenticated user (requires a valid `access_token`).
* `POST /api/logout/all` (protected): Log out the currently authenticated user from all devices (requires a valid `access_token`).
* `POST /api/forgot-password`: Send a password reset link to the provided email. Requires `email`.
* `POST /api/reset-password`: Reset the user's password using a token received via email. Requires `token`, `email`, `password`, and `password_confirmation`.

**User Profile Management Endpoints (Protected - Requires a valid `access_token` in the `Authorization` header as `Bearer <token>`):**

* `GET /api/user`: Get the details of the currently authenticated user.
* `PUT /api/user/profile`: Update the authenticated user's profile information (e.g., `name`). Requires the new information in the request body.
* `PUT /api/user/profile/email`: Update the authenticated user's email address. Requires the new `email` in the request body. An email verification process might be triggered.
* `PUT /api/user/profile/password`: Update the authenticated user's password. Requires `current_password`, `password`, and `password_confirmation` in the request body.

**Other Available Endpoints (Examples):**

* **Anime:**
    * `GET /api/animes`: Retrieve a list of all anime.
    * `GET /api/animes/{id}`: Retrieve details for a specific anime.
    * `POST /api/animes`: Create a new anime.
    * `PUT /api/animes/{id}`: Update an existing anime.
    * `DELETE /api/animes/{id}`: Delete a specific anime.
* **Genres:**
    * `GET /api/genres`: Retrieve a list of all genres.
    * `GET /api/genres/{id}`: Retrieve details for a specific genre.
    * `POST /api/genres`: Create a new genre.
    * `PUT /api/genres/{id}`: Update an existing genre.
    * `DELETE /api/genres/{id}`: Delete a specific genre.
* **Studios:**
    * `GET /api/studios`: Retrieve a list of all studios.
    * `GET /api/studios/{id}`: Retrieve details for a specific studio.
    * `POST /api/studios`: Create a new studio.
    * `PUT /api/studios/{id}`: Update an existing studio.
    * `DELETE /api/studios/{id}`: Delete a specific studio.
* **Episodes:**
    * `GET /api/animes/{anime_id}/episodes`: Retrieve a list of episodes for a specific anime.
    * `GET /api/episodes/{id}`: Retrieve details for a specific episode.
    * `POST /api/animes/{anime_id}/episodes`: Create a new episode for an anime.
    * `PUT /api/episodes/{id}`: Update an existing episode.
    * `DELETE /api/episodes/{id}`: Delete a specific episode.

**Request Methods:**

The API utilizes standard HTTP methods:

* `GET`: To retrieve data.
* `POST`: To create new resources.
* `PUT` or `PATCH`: To update existing resources.
* `DELETE`: To remove resources.

**Response Format:**

The API typically returns responses in JSON format. The structure of the JSON response will vary depending on the endpoint and the data being requested or manipulated.

**Authentication:**

This API uses **Laravel Sanctum** for authentication. To access protected routes, you need to obtain an `access_token` by registering a new user via the `/api/register` endpoint or logging in an existing user via the `/api/login` endpoint. Once you have an `access_token`, you must include it in the `Authorization` header of your subsequent requests to protected routes. The header should be formatted as `Bearer <your_access_token>`.

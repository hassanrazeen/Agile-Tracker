# Agile Tracker

## 🚀 Project Overview

This Laravel-based RESTful API provides comprehensive project management functionality, including:
- User Authentication
- Project Management
- Timesheet Tracking
- Dynamic Attribute Management (Entity-Attribute-Value Model)

## 📋 Table of Contents
- [Setup Instructions](#-setup-instructions)
- [Authentication](#-authentication)
- [API Endpoints](#-api-endpoints)
  - [Projects](#projects)
  - [Timesheets](#timesheets)
  - [Attributes](#attributes)
  - [Attribute Values](#attribute-values)
- [Filtering](#-filtering)
- [Testing](#-testing)

## 📦 Requirements
- Laravel Herd
- PHP 8.1+
- PostgreSQL
- Composer
- Laravel Passport

## 🛠 Setup Instructions

### 1. Clone the Repository in Herd Folder
```bash
# Navigate to Herd directory
cd ~/Herd

# Clone the repository
git clone https://your-repository-url.git
cd Agile-Tracker
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Update Database Credentials:
   - Open `.env` file and update database settings:
   ```ini
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

3. Optional: Update PostgreSQL Configuration
   - Open `config/database.php`
   - Locate the `pgsql` section
   - Modify credentials to match your PostgreSQL setup


### 4. Setup Database and Authentication
```bash
# Run migrations
php artisan migrate

# Generate passport client for application
php artisan passport:client --personal

# Seed initial data 
php artisan db:seed
```

### 5. Start Development Server
```bash
php artisan serve
```

## Additional Notes
- Ensure PostgreSQL service is running
- Verify all dependencies are correctly installed
- Check `.env` file permissions
- Recommended: Run `php artisan key:generate` to set application key

## 🔐 Authentication Endpoints

### 1. User Registration
- **Endpoint:** `POST /api/register`
- **Purpose:** Create a new user account

#### Request Body
```json
{
  "first_name": "John",
  "last_name": "Cena",
  "email": "john@gmail.com",
  "password": "secure_password",
  "password_confirmation": "secure_password"
}
```

#### Successful Response
```json
{
  "message": "User registered successfully",
  "token": "access_token_here"
}
```

### 2. User Login
- **Endpoint:** `POST /api/login`
- **Purpose:** Authenticate user and receive access token

#### Request Body
```json
{
  "email": "john@gmail.com",
  "password": "secure_password"
}
```

#### Successful Response
```json
{
  "token": "access_token_here"
}
```

### 3. User Logout
- **Endpoint:** `POST /api/logout`
- **Purpose:** Invalidate user's current access token
- **Required Header:** `Authorization: Bearer {access_token}`

#### Successful Response
```json
{
  "message": "Logged out successfully"
}
```

## 📊 API Endpoints

### Projects

#### 1. List All Projects
- **Endpoint:** `GET /api/projects`
- **Required Header:** `Authorization: Bearer {access_token}`
- **Purpose:** Retrieve all projects

#### 2. Get Single Project
- **Endpoint:** `GET /api/projects/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`
- **Purpose:** Retrieve details of a specific project

#### 3. Create Project
- **Endpoint:** `POST /api/projects`
- **Required Header:** `Authorization: Bearer {access_token}`

##### Request Body
```json
{
  "name": "Web Development Project",
  "status": "in_progress",
}
```

#### 4. Update Project
- **Endpoint:** `PUT /api/projects/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

##### Request Body
```json
{
  "name": "Updated Project Name",
  "status": "completed"
}
```

#### 5. Delete Project
- **Endpoint:** `DELETE /api/projects/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

### Timesheets

#### 1. List All Timesheets
- **Endpoint:** `GET /api/timesheets`
- **Required Header:** `Authorization: Bearer {access_token}`
- **Purpose:** Retrieve all timesheet entries

#### 2. Get Single Timesheet
- **Endpoint:** `GET /api/timesheets/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`
- **Purpose:** Retrieve details of a specific timesheet entry

#### 3. Create Timesheet Entry
- **Endpoint:** `POST /api/timesheets`
- **Required Header:** `Authorization: Bearer {access_token}`

##### Request Body
```json
{
  "task_name": "Frontend Development",
  "date": "2024-03-15",
  "hours": 6.5,
  "project_id": "10ce093e-fddd-4607-a2fe-ba1679de6211",
}
```

#### 4. Update Timesheet Entry
- **Endpoint:** `PUT /api/timesheets/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

##### Request Body
```json
{
  "hours": 5.0,
}
```

#### 5. Delete Timesheet Entry
- **Endpoint:** `DELETE /api/timesheets/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

### Attributes

#### 1. List All Attributes
- **Endpoint:** `GET /api/attributes`
- **Required Header:** `Authorization: Bearer {access_token}`
- **Purpose:** Retrieve all custom attributes

#### 2. Get Single Attributes
- **Endpoint:** `GET /api/timesheets/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`
- **Purpose:** Retrieve details of a specific attributes entry

#### 3. Create Attribute
- **Endpoint:** `POST /api/attributes`
- **Required Header:** `Authorization: Bearer {access_token}`

##### Request Body
```json
{
  "name": "Project Priority",
  "type": "text",
}
```

#### 4. Update Attribute
- **Endpoint:** `PUT /api/attributes/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

#### 5. Delete Attribute
- **Endpoint:** `DELETE /api/attributes/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

### Attribute Values

#### 1. List Attribute Values
- **Endpoint:** `GET /api/attribute-values`
- **Purpose:** Retrieve all attribute values

#### 2. Create Attribute Value
- **Endpoint:** `POST /api/attribute-values`
- **Required Header:** `Authorization: Bearer {access_token}`

##### Request Body
```json
{
  "attribute_id": "10ce093e-fddd-4607-a2fe-ba1679de6211",
  "entity_id": "10ce093e-fddd-4607-a2fe-ba1679de6211",
  "value": "High Priority",
  "entity_type": "project"
}
```

#### 3. Update Attribute Value
- **Endpoint:** `PUT /api/attribute-values/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

#### 4. Delete Attribute Value
- **Endpoint:** `DELETE /api/attribute-values/{id}`
- **Required Header:** `Authorization: Bearer {access_token}`

## 🔍 Advanced Filtering

### Query Parameter Filtering
Endpoint supports flexible filtering using query parameters:

```
GET /api/projects?filters[name]=WebProject&filters[status]=in_progress
GET /api/timesheets?filters[hours]=>5&filters[date]=2024-03-15
```

Supported Operators:
- `=`: Exact match
- `>`: Greater than
- `<`: Less than
- `LIKE`: Partial text match

## 🧪 Test Credentials

For initial testing, use:
- **Email:** `john.doe@example.com`
- **Password:** `password123`

## 🔒 Security Notes
- Always use HTTPS in production
- Keep access tokens confidential
- Implement proper rate limiting
- Regularly update dependencies

## Troubleshooting
- If you encounter dependency issues, run `composer update`
- For Passport setup problems, try `php artisan passport:install`
- Verify PHP extensions are enabled in your `php.ini`

## 🔍 Recommended Development Tools
- Postman or Insomnia for API testing
- PostgreSQL management tool (DBeaver, pgAdmin)
- Visual Studio Code or PhpStorm

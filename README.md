## SB CROWDFUNDING

Crowdfunding platform to allow people in need receive money from anyone interested in donating to the needy.

## Technology Stack
### 1. Backend
    - PHP (laravel ^11.9) (A php-based web framework known for its expressive syntax  and robust features) 
    - MYSQL (A popular  open source RDMS)
    - Laravel sanctum (A laravel package for api / SPA auths)
    - Laravel sail (A CLI for interacting with Laravel's default Docker development environment)

### 2. Frontend
    - TailwindCss
    - Javascript (Vue.js)
    - Vite

# Installation

## 1. Prerequisites:
* Listing all the prerequisites required to run the project, such as:
    * PHP (^8.2)
    * Composer
    * Node.js and npm (^v18.16.1 and ^9.5.1 respectively)
    * VueJS (2)
    
## 2. Project Setup:
* Clone or download the project from the repository.
* Extract the project files to your desired folder.

## 3. Install Dependencies:
* Run the following commands in the root directory of the project to install the dependencies:
    * ```composer install``` to install PHP dependencies.
    * ```npm install``` to install JavaScript dependencies.
    
## 4. Environment Configuration:
* Create the env file from the example file ```cp .env.example .env```.
* Open the ```.env``` file and configure your environment variables:
    * Set the database connection details.
    * Set any other required environment variables.
  
## 5. Generate Application Key:
* In the terminal, navigate to the root directory of the project and run the following command to generate the application key:
    * ```php artisan key:generate```
    
## 6. Database Setup:
* Create a new database for the project if it doesn't exist.
* Update the ```.env``` file with the appropriate database credentials.

## 7. Migrate and Seed the Database:
* In the terminal, navigate to the root directory of the project and run the following commands to migrate and seed the database:
    * ```php artisan migrate``` to run database migrations.
    * ```php artisan db:seed``` to seed the database with initial data.
    
## 8. Compile Assets:
* In the terminal, navigate to the root directory of the project and run the following command to compile the assets:
    * ```npm install```
    * ```npm run dev```
    
* This will compile the Vue.js components, Tailwind CSS stylesheets and also download and install required dependencies.

## 9. Run the Development Server:
* In the terminal, navigate to the root directory of the project and run the following command to start the Laravel development server: 
    * ```php artisan serve```
* The development server will be accessible at [http://localhost:8000](http://localhost:8080).

## Testing
* In the terminal, navigate to the root directory of the project and run the following command to execute the tests: 
    * ```php artisan test```


## Api Documentation
Postman was used for testing and documenting the api. Access the documentation via this [https://www.postman.com/red-robot-194694/workspace/sb-crowdfunding-api](link).


### Document Created & Modification Date

* Created on: ```Sep 28, 2024```
* Modified on:  ```Sep 28, 2024```

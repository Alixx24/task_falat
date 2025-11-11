Installation

Clone the repository:

git clone https://github.com/Alixx24/task_falat.git
cd project-name


Install PHP dependencies:

composer install


Install Node dependencies and compile assets:

npm install
npm run dev


Set up environment variables:

cp .env.example .env
php artisan key:generate

Database Setup

Run migrations:

php artisan migrate


Seed the database with demo data:

php artisan db:seed


Note: After running php artisan db:seed, sample users will be created so you can quickly log in and test the application.

Usage

Start the local development server:

php artisan serve


Visit http://127.0.0.1:8000 in your browser.

Log in with one of the seeded users (check database/seeders/UserSeeder.php for credentials).
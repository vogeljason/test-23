This test was conducted using Laravel 10.10 as the framework and php 8.2. The key files
are 

 - routes/web.php - determines new url routes
 - app/Http/Controllers/APIController.php - new controller for the app frontend
 - resources/views/character-list.blade.php - frontend template for 
rendering content
 - public/css/styles.css - frontend styles
 - .env - added a new variable for the API url

I used a bitnami Laravel image for ease of setup on local development environment
This can be found here https://hub.docker.com/r/bitnami/laravel/
The normal setup for a Laravel application will be needed 

 - composer install
 - php artisan serve

Once setup, the main page can be accessed at <project-url>/characterslist. This displays the main page
with a list of the first page of Rick and Morty characters from the API. Pagination 
is available to browse through the other pages. The page has filters to filter by criteria and a search input
to search for a character by name. 

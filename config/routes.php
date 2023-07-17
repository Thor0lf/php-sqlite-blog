<?php 
use App\Router;
$router = new Router(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates');
$router
    ->get('/', 'home', 'home')
    ->post('/', 'home')
    ->get('/article', 'article', 'article')
    ->post('/article', 'article')
    ->get('/contact', 'contact', 'contact')
    ->post('/contact', 'contact')
    ->get('/cookies', 'cookies', 'cookies')
    ->post('/cookies', 'cookies')

    //users
    ->get('/signup', '/users/signup', 'signup')
    ->post('/signup', '/users/signup')
    ->get('/login', '/users/login', 'login')
    ->post('/login', '/users/login')
    ->get('/verify', '/users/verify', 'verify')
    ->get('/forgot-password', '/users/forgot-password', 'forgot-password')
    ->post('/forgot-password', '/users/forgot-password')
    ->get('/reset-password', '/users/reset-password', 'reset-password')
    ->post('/reset-password', '/users/reset-password')
    ->get('/profile', '/users/profile', 'profile')
    ->post('/profile', '/users/profile')
    ->get('/change-password', '/users/change-password', 'change-password')
    ->post('/change-password', '/users/change-password')
    ->get('/delete-user', '/users/delete-user', 'delete-user')
    ->get('/logout', '../templates/users/logout', 'logout')

    //admin
    ->get('/users-list', '/admin/users-list', 'users-list')
    ->post('/users-list', '/admin/users-list')
    ->get('/categories-list', '/admin/categories-list', 'categories-list')
    ->post('/categories-list', '/admin/categories-list')
    ->get('/add-edit-category', '/admin/add-edit-category', 'add-edit-category')
    ->post('/add-edit-category', '/admin/add-edit-category')
    ->get('/articles-list', '/admin/articles-list', 'articles-list')
    ->post('/articles-list', '/admin/articles-list')
    ->get('/add-edit-article', '/admin/add-edit-article', 'add-edit-article')
    ->post('/add-edit-article', '/admin/add-edit-article')

    //errors
    ->get('/403', '/errors/403', '403')
    ->post('/403', '/errors/403')
    ->get('/404', '/errors/404', '404')
    ->post('/404', '/errors/404')
    ->get('/500', '/errors/500', '500')
    ->post('/500', '/errors/500')
    ;

?>
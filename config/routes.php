<?php 
$router->map('GET', '/', 'home', 'home');
$router->map('POST', '/', 'home');
$router->map('GET', '/article', 'article', 'article');
$router->map('POST', '/article', 'article');
$router->map('GET', '/contact', 'contact', 'contact');
$router->map('POST', '/contact', 'contact');
$router->map('GET', '/cookies', 'cookies', 'cookies');
$router->map('POST', '/cookies', 'cookies');

//users
$router->map('GET', '/signup', '/users/signup', 'signup');
$router->map('POST', '/signup', '/users/signup');
$router->map('GET', '/login', '/users/login', 'login');
$router->map('POST', '/login', '/users/login');
$router->map('GET', '/verify', '/users/verify', 'verify');
$router->map('GET', '/forgot-password', '/users/forgot-password', 'forgot-password');
$router->map('POST', '/forgot-password', '/users/forgot-password');
$router->map('GET', '/reset-password', '/users/reset-password', 'reset-password');
$router->map('POST', '/reset-password', '/users/reset-password');
$router->map('GET', '/profile', '/users/profile', 'profile');
$router->map('POST', '/profile', '/users/profile');
$router->map('GET', '/change-password', '/users/change-password', 'change-password');
$router->map('POST', '/change-password', '/users/change-password');
$router->map('GET', '/delete-user', '/users/delete-user', 'delete-user');
$router->map('GET', '/logout', '../templates/users/logout', 'logout');

//admin
$router->map('GET', '/users-list', '/admin/users-list', 'users-list');
$router->map('POST', '/users-list', '/admin/users-list');
$router->map('GET', '/categories-list', '/admin/categories-list', 'categories-list');
$router->map('POST', '/categories-list', '/admin/categories-list');
$router->map('GET', '/add-edit-category', '/admin/add-edit-category', 'add-edit-category');
$router->map('POST', '/add-edit-category', '/admin/add-edit-category');
$router->map('GET', '/articles-list', '/admin/articles-list', 'articles-list');
$router->map('POST', '/articles-list', '/admin/articles-list');
$router->map('GET', '/add-edit-article', '/admin/add-edit-article', 'add-edit-article');
$router->map('POST', '/add-edit-article', '/admin/add-edit-article');

//errors
$router->map('GET', '/403', '/errors/403', '403');
$router->map('POST', '/403', '/errors/403');
$router->map('GET', '/404', '/errors/404', '404');
$router->map('POST', '/404', '/errors/404');
$router->map('GET', '/500', '/errors/500', '500');
$router->map('POST', '/500', '/errors/500');

?>
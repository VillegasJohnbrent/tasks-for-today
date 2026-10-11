<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->setAutoRoute(false);

// The original four pages remain public.
$routes->get('/', 'Dashboard::index');
$routes->get('tasks', 'Dashboard::tasks');
$routes->get('profile', 'Dashboard::profile');
$routes->get('about', 'Dashboard::about');
$routes->get('login', 'Auth::login', ['filter' => 'csrf']);
$routes->post('login', 'Auth::authenticate', ['filter' => 'csrf']);

// Auth runs before CSRF: logged-out management requests redirect to login.
$routes->group('', ['filter' => ['auth', 'csrf']], static function ($routes) {
    $routes->get('tasks/new', 'TaskManager::new');
    $routes->post('tasks', 'TaskManager::create');
    $routes->get('tasks/(:num)/edit', 'TaskManager::edit/$1');
    $routes->post('tasks/(:num)/update', 'TaskManager::update/$1');
    $routes->post('tasks/(:num)/delete', 'TaskManager::archive/$1');
    $routes->post('logout', 'Auth::logout');
});

<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('login', 'LogController::index');
$routes->post('login/logging_in', 'LogController::logging_in');
$routes->get('logout', 'LogController::logging_out');

// view page
$routes->get('p/(:segment)', 'PageController::index/$1');
// view user
$routes->get('u/(:segment)', 'UserController::index/$1');

$routes->group('account', static function ($routes) {

    // profile
    $routes->get('timeline', 'TimelineController::index');
    $routes->get('timeline/create', 'TimelineController::create');
    $routes->post('timeline/save', 'TimelineController::save');
    $routes->get('timeline/detail/(:segment)', 'TimelineController::detail/$1');
    $routes->post('timeline/update/(:segment)', 'TimelineController::update/$1');
    $routes->delete('timeline/delete/(:num)', 'TimelineController::delete/$1');
    // timeline
    $routes->get('timeline', 'TimelineController::index');
    $routes->get('timeline/create', 'TimelineController::create');
    $routes->post('timeline/save', 'TimelineController::save');
    $routes->get('timeline/detail/(:segment)', 'TimelineController::detail/$1');
    $routes->post('timeline/update/(:segment)', 'TimelineController::update/$1');
    $routes->delete('timeline/delete/(:num)', 'TimelineController::delete/$1');
    // post
    $routes->get('post', 'PostController::index');
    $routes->get('post/create', 'PostController::create');
    $routes->post('post/save', 'PostController::save');
    $routes->get('post/detail/(:segment)', 'PostController::detail/$1');
    $routes->post('post/update/(:segment)', 'PostController::update/$1');
    $routes->delete('post/delete/(:num)', 'PostController::delete/$1');
});

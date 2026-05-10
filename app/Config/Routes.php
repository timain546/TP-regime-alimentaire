<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login', 'Admin\Auth::attemptLogin');
$routes->get('admin/logout', 'Admin\Auth::logout');

$routes->group('admin', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    $routes->get('regimes', 'Admin\Regimes::index');
    $routes->get('regimes/create', 'Admin\Regimes::create');
    $routes->post('regimes', 'Admin\Regimes::store');
    $routes->get('regimes/edit/(:num)', 'Admin\Regimes::edit/$1');
    $routes->post('regimes/update/(:num)', 'Admin\Regimes::update/$1');
    $routes->post('regimes/delete/(:num)', 'Admin\Regimes::delete/$1');

    $routes->get('activities', 'Admin\Activities::index');
    $routes->get('activities/create', 'Admin\Activities::create');
    $routes->post('activities', 'Admin\Activities::store');
    $routes->get('activities/edit/(:num)', 'Admin\Activities::edit/$1');
    $routes->post('activities/update/(:num)', 'Admin\Activities::update/$1');
    $routes->post('activities/delete/(:num)', 'Admin\Activities::delete/$1');

    $routes->get('codes', 'Admin\Codes::index');
    $routes->post('codes/generate', 'Admin\Codes::generate');
    $routes->post('codes/validate/(:num)', 'Admin\Codes::validateCode/$1');

    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/update', 'Admin\Settings::update');
});

$routes->get("/auth/login", "FrontOffice\FrontOfficeController::login");
$routes->post("/api/auth/login", "FrontOffice\FrontOfficeController::attemptLogin");
$routes->get("/client/form", "FrontOffice\FrontOfficeController::inscription");
$routes->post("/api/client/create", "FrontOffice\FrontOfficeController::creerClient");
$routes->get("/sante/form", "FrontOffice\FrontOfficeController::inscriptionSante");
$routes->post("/api/sante/create", "FrontOffice\FrontOfficeController::creerSante");
$routes->get("/dashboard", "FrontOffice\FrontOfficeController::dashboard");
$routes->get("/logout", "FrontOffice\FrontOfficeController::logout");
$routes->get("/profil", "FrontOffice\FrontOfficeController::profil");
$routes->post("/api/sante/edit", "FrontOffice\FrontOfficeController::editSante");
$routes->get("/gold", "FrontOffice\FrontOfficeController::gold");
$routes->post("/gold/confirmer", "FrontOffice\FrontOfficeController::confirmerAchatGold");

$routes->group('suggestion', static function ($routes) {
    $routes->get('/', 'SuggestionController::index');
    $routes->post('/voir', 'SuggestionController::voir');
    $routes->get('/pdf', 'SuggestionController::pdf');
});
$routes->group('porte_monnaie', static function ($routes){
    $routes->get('/','PorteMonnaieController::index');
    $routes->post('/code', 'PorteMonnaieController::verifier_code');
});

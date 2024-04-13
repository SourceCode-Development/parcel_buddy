<?php

use app\controllers\{AboutController,SiteController,ParcelController,DeliveryUsersController};
use app\core\Application;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$config = [
    'userClass' => \app\models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->on(Application::EVENT_BEFORE_REQUEST, function(){
    // echo "Before request from second installation";
});

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/register', [SiteController::class, 'register']);
$app->router->post('/register', [SiteController::class, 'register']);
$app->router->get('/login', [SiteController::class, 'login']);
$app->router->get('/login/{id}', [SiteController::class, 'login']);
$app->router->post('/login', [SiteController::class, 'login']);
$app->router->get('/logout', [SiteController::class, 'logout']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->get('/about', [AboutController::class, 'index']);

$app->router->get('/parcels', [ParcelController::class, 'index']);
$app->router->get('/parcels-paginated/{page}', [ParcelController::class, 'parcels_paginated']);
$app->router->get('/add_parcel', [ParcelController::class, 'add_parcel']);
$app->router->post('/create_parcel', [ParcelController::class, 'create_parcel']);
$app->router->post('/update_parcel', [ParcelController::class, 'update_parcel']);
$app->router->get('/edit_parcel/{id}', [ParcelController::class, 'edit_parcel']);


$app->router->get('/delivery_users', [DeliveryUsersController::class, 'index']);
$app->router->get('/add_delivery_user', [DeliveryUsersController::class, 'add_delivery_user']);
$app->router->post('/create_delivery_user', [DeliveryUsersController::class, 'create_delivery_user']);
$app->router->post('/update_delivery_user', [DeliveryUsersController::class, 'update_delivery_user']);
$app->router->get('/edit_delivery_user/{id}', [DeliveryUsersController::class, 'edit_delivery_user']);

// /profile/{id}
// /profile/13
// \/profile\/\w+

// /profile/{id}/zura
// /profile/12/zura

// /{id}
$app->run();
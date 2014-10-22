<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

// include composer autoload
require 'vendor/autoload.php';

// create a fresh silex app
$app = new Silex\Application();

// define root
defined('ROOT') or define('ROOT', __DIR__ . '/');

// debug
$app['debug'] = false;

// some settings
$app['upload.dir'] = __DIR__ . '/uploads/';
// some predefined sizes
$app['image.sizes'] = [
    '960' => '600',
    '480' => '300'
];

// register twig as template engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . "/view",
));

// post route
$app->post('/api/v1/images', function (Request $request) use ($app) {
    $image = isset($_FILES['file'])
           ? $_FILES['file']
           : null;
    $text = isset($_POST['text'])
          ? htmlspecialchars($_POST['text'])
          : 'Hello World';

    $image = new Sody\Image($image);
    $upload = (new Sody\Factory())->createImageUpload($image, $app['upload.dir']);

    $upload->store($app['image.sizes'], $text);

    $images = (new Sody\Factory())
            ->createImageRepository($app['upload.dir'])
            ->getImages();

    return $app->json($images, 200);
});

// get route, returns all images
$app->get('/api/v1/images', function (Request $request) use ($app) {
    $images = (new Sody\Factory())
            ->createImageRepository($app['upload.dir'])
            ->getImages();

    return $app->json($images, 200);
});

// delete route, deletes upload directory then recreates it
$app->delete('/api/v1/images', function (Request $request) use ($app) {
    $filesystem = new Filesystem(
        new Adapter(
            (new Sody\Directory(__DIR__))->get()
        )
    );

    $filesystem->deleteDir('uploads');
    $filesystem->createDir('uploads');

    return $app->json(null, 200);
});

// home route
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig', ['uploadDir' => $app['upload.dir']]);
});

// error/exception handling
$app->error(function (\Exception $e, $code) use ($app) {
    switch($code) {
        case 404:
            $app->json([], 404);
            break;
        default:
            return $app->json($e->getMessage(), 400);
    }
});

// bootstrap app
$app->run();

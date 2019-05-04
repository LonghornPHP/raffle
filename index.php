<?php
require_once 'vendor/autoload.php';

// expects Tito CSV output
$file = __DIR__.'/checked-in.csv';
$filter = __DIR__.'/longhornphp19-filter.csv';

$app = new \Slim\App();

$app->get('/', function($request, $response)  {
    include_once(__DIR__.'/template/index.php');
});
$app->get('/rand', function($request, \Slim\Http\Response $response) use ($file, $filter){
    $names = [];
    $row = 0;

    // open the file
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row > 0) {
                $names[] = trim($data[3]) . ' ' . trim($data[4]);
            }
            $row++;
        }
        fclose($handle);
    }

    // filter any names from the list that have won already or aren't there
    $winners = file_exists($filter) ? file($filter) : [];
    foreach ($names as $n_index => $name) {
        foreach ($winners as $winner) {
            if (trim($winner) == trim($name)) {
                unset($names[$n_index]);
            }
        }
    }

    // pick a random one
    $name = $names[array_rand($names)];
    return $response->withJson(['name' => $name]);
});
$app->post('/name', function($request, $response) use ($filter){
    $name = $request->getParam('name');
    file_put_contents($filter, trim($name)."\n", FILE_APPEND);
});

$app->run();

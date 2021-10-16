<?php
require_once 'vendor/autoload.php';

// expects output from php artisan longhornphp:bingo:completed
$file = __DIR__ . '/eligible.tsv';
$filter = __DIR__ . '/raffle-filter.csv';

$app = new \Slim\App();

$app->get('/', function($request, $response)  {
    include_once(__DIR__.'/template/index.php');
});
$app->get('/rand', function($request, \Slim\Http\Response $response) use ($file, $filter){
    $people = [];
    $row = 0;

    // open the file
    if (($handle = fopen($file, "r")) !== FALSE) { // email, first, last, ticket type (in-person/virtual)
        while (($line = fgets($handle, 1000)) !== FALSE) {
            $data = explode("\t", $line);
            if ($row > 0 && count($data) >= 4) {
                $people[] = ['email' => $data[0], 'name' => "$data[1] $data[2]", 'type' => $data[3]];
            }
            $row++;
        }
        fclose($handle);
    }

    // filter any names from the list that have won already or aren't there
    $winners = file_exists($filter) ? file($filter) : [];
    foreach ($people as $n_index => $person) {
        foreach ($winners as $winner) {
            if (trim($winner) == trim($person['name'])) {
                unset($people[$n_index]);
            }
        }
    }

    // pick a random one
    $currentWinner = $people[array_rand($people)];
    return $response->withJson(['name' => $currentWinner['name']]);
});
$app->post('/name', function($request, $response) use ($filter){
    $name = $request->getParam('name');
    file_put_contents($filter, trim($name) . "\n", FILE_APPEND);
});

$app->run();

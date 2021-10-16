<?php
require_once 'vendor/autoload.php';

// expects output from php artisan longhornphp:bingo:completed
$file = __DIR__ . '/eligible.tsv';
$filter = __DIR__ . '/raffle-filter.csv';

$app = new \Slim\App();

$app->get('/', function($request, $response)  {
    include_once(__DIR__.'/template/index.php');
});
$app->get('/rand', function(\Slim\Http\Request $request, \Slim\Http\Response $response) use ($file, $filter){
    $people = [];
    $row = 0;

    // open the file
    if (($handle = fopen($file, "r")) !== FALSE) { // email, first, last, ticket type (in-person/virtual)
        while (($line = fgets($handle, 1000)) !== FALSE) {
            $data = explode("\t", trim($line));
            if ($row > 0 && count($data) >= 4) {
                $people[] = ['email' => $data[0], 'name' => "$data[1] $data[2]", 'type' => $data[3]];
            }
            $row++;
        }
        fclose($handle);
    }

    // filter any names from the list that have won already or aren't there
    $winners = file_exists($filter) ? array_map('trim', file($filter)) : [];
    $people = array_filter($people, fn ($person) => !in_array(trim($person['name']), $winners));

    // if in-person-only, skip virtual
    if ($request->getQueryParam('in_person_only', '0') === '1') {
        $people = array_filter($people, fn ($person) => $person['type'] === 'in-person');
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

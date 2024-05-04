<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);


$dbSettings = [
    'host' => '185.253.218.168',
    'dbname' => 'edpmaold_college',
    'user' => 'edpmaold_college',
    'pass' => 'qwerty123'
];

$pdo = new PDO("mysql:host={$dbSettings['host']};dbname={$dbSettings['dbname']};charset=utf8", $dbSettings['user'], $dbSettings['pass']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("Hello, world!");
    return $response;
});


$app->get('/students', function (Request $request, Response $response, array $args) use ($pdo) {
    $sql = "SELECT 
                People.firstName,
                People.lastName,
                People.phoneNumber,
                People.email,
                Groups.groupName
            FROM 
                Students
            JOIN 
                People ON Students.personId = People.personId
            JOIN 
                Groups ON Students.groupId = Groups.groupId";
                
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response->getBody()->write(json_encode($students));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/teachers', function (Request $request, Response $response, array $args) use ($pdo) {
    $sql = "SELECT 
                People.firstName,
                People.lastName,
                People.phoneNumber,
                People.email,
                Departments.departmentName
            FROM 
                Teachers
            JOIN 
                People ON Teachers.personId = People.personId
            JOIN 
                Departments ON Teachers.departmentId = Departments.departmentId";
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response->getBody()->write(json_encode($students));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();

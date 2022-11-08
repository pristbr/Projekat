
<?php echo "<head> <link rel='stylesheet' href='/website/css/style.css'> </head>" ?>
<?php echo "<head> <title> Gain&Volume </title> </head>" ?>



<?php

use MuzickaProdavnica\Core\Config;
use MuzickaProdavnica\Core\Router;
use MuzickaProdavnica\Core\Request;

use MuzickaProdavnica\Utilities\DependencyInjection;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

require_once __DIR__ . "/vendor/autoload.php";

    session_set_cookie_params(0);
    session_start();


    $config = new Config();
    $dbConfig = $config->getConfigFile('db');

    $dbPDO = new PDO("mysql:host=127.0.0.1;dbname=muzickaprodavnica;", $dbConfig['username'], $dbConfig['password']);

    $twig_loader = new FilesystemLoader(__DIR__ . '\website\views');
    $twig_view = new Environment($twig_loader);

    $monolog_log = new Logger('muzicka_prodavnica');
    $monolog_log_file = $config->getConfigFile('log');

    $monolog_log->pushHandler(new StreamHandler($monolog_log_file, Logger::DEBUG));

    $di = new DependencyInjection();

    $di->setDependency('PDO', $dbPDO); //Database Connectioin
    $di->setDependency('Twig', $twig_view); //Postavimo Twig
    $di->setDependency('Logger', $monolog_log); //Postavimo Monologov Logger
    $di->setDependency('Utilities/Config', $config); //Potrebno za dodatnu konfiguraciju nadalje

    $router = new Router($di);


    $response = $router->getRoute(new Request());



    echo $response;
?>







    
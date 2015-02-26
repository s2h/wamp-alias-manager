<?php
spl_autoload_register(function ($class) {
    include $class . '.php';
});
header("Content-type: text/json");

if ($_SERVER['HTTP_HOST'] != 'localhost') {
    echo json_encode(array('error'=> 'only for local users'));
    die();
}

$alias = new Alias('c:/wamp/alias/');
$action = htmlspecialchars(empty($_REQUEST['action']) ? '' : $_REQUEST['action']);
$name = htmlspecialchars(empty($_REQUEST['name']) ? '' : $_REQUEST['name']);
$path = htmlspecialchars(empty($_REQUEST['path']) ? '' : $_REQUEST['path']);

if ( ! empty($action) && ! empty($name)) {
    switch ($action) {
        case 'view':
            Debug::trace($alias->viewAlias($name));
            break;
        case 'create':
            if ( ! empty($path)) {
                $alias->createNewAlias($name, $path);
                echo json_encode(array('Success'=> true));
                /*Debug::trace('Alias "' .
                    $name .
                    '" with path ' .
                    $path .
                    ' created.');*/
                break;
            }
            Debug::trace('Error. Define path variable in request!');
            break;
        case 'delete':
            $alias->deleteAlias($name);
            echo json_encode(array('Success'=> true));
            break;
    }
}

if ( ! empty($action) and $action == 'list') {
    echo json_encode($alias->listAllAliases());
}
/*Debug::trace('Available actions: ' . PHP_EOL .
    'list ' . PHP_EOL .
    'delete name' . PHP_EOL .
    'view name' . PHP_EOL .
    'create name path');*/

<?php
spl_autoload_register(function ($class) {
    $prefix = 'kubokat\\ApiWrapper\\';
    $base_dir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use kubokat\ApiWrapper\Api;
use kubokat\ApiWrapper\JsonRpcTransport;
use kubokat\ApiWrapper\Validator;

$validator = new Validator();
$transport = new JsonRpcTransport();
$sender = new Api($transport, $validator);

session_start();

try {
    switch ($_POST['action']) {
        case 'create_domain':
            $user = $sender->createUser($_POST);

            if (!empty($user->result->id)) {
                $_SESSION["clientId"] = $user->result->id;

                $domain = $sender->createDomain($user->result->id, $_POST);

                if ($domain->result->id) {
                    $_SESSION["domainId"] = $domain->result->id;
                    $res = $sender->getDomain($domain->result->id);

                    echo json_encode($res->result->domain);
                }
            }
            break;
        case 'change_dns':
            unset($_POST['action']);

            $domain = $sender->changeDNS($_SESSION['clientId'], $_SESSION['domainId'], ['domain' => $_POST]);
            break;
        default:
            throw new Exception('Undefined action');
    }


} catch (Exception $e) {
    echo $e->getMessage();
}
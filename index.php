<?php
header('Content-Type: application/json');
require_once('config/settings.php');
require_once('helpers/includes.php');

$request = new Request($_GET);
$db = Database::getInstance();

$action = $request->get('action', null);
$method = $_SERVER['REQUEST_METHOD'];

switch($action) {
    case 'users':
        if ($method === 'GET') {
            $query = "SELECT `firstname`, `lastname` FROM `users`;";
            $result = $db->query($query);
            $users = $db->fetchByAssoc($result);
            response($users);
        } else if ($method === 'PUT') {
            response("Dodano uzytkownika");
        } else if ($method === 'DELETE') {
            response("Usunięto uzytkownika o id 5.");
        } else {
            response("Error! Unknown command!", false, 400);
        }
        break;
    default:
        response("Error! Unknown command!", false, 400);
}
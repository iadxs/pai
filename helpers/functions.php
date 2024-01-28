<?php
function response($data, $success = true, $code = 200) {
    http_response_code($code);
    $response = new stdClass();
    if (is_string($data)) {
        $response->message = $data;
    } else {
        $response->data = $data;
    }
    $response->success = $success;
    echo json_encode($response);
    exit;
}

function debug($variable, $exit = true) {
    header('Content-Type: text/html');
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';

    if ($exit) {
        exit;
    }
}
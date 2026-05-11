<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/Model/licensingModel.php';
require_once __DIR__ . '/Control/licensingControl.php';
require_once __DIR__ . '/View/licensingView.php';

function respond(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? $_POST['action'] ?? 'list';
$controller = new License();

try {
    if ($method === 'GET' && $action === 'list') {
        respond(200, $controller->listLicensesPayload());
    }

    if ($method === 'GET' && $action === 'checkPatent') {
        $pn = $_GET['patent_number'] ?? '';
        $result = $controller->checkPatentExists($pn);
        respond($result['success'] ? 200 : 404, $result);
    }

    if ($method === 'POST' && $action === 'create') {
        $result = $controller->createLicense($_POST);
        respond($result['success'] ? 201 : 422, $result);
    }

    if ($method === 'POST' && $action === 'update') {
        $result = $controller->updateLicenseRecord($_POST);
        respond($result['success'] ? 200 : 422, $result);
    }

    if ($method === 'POST' && $action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $result = $controller->deleteLicenseById($id);
        respond($result['success'] ? 200 : 422, $result);
    }

    respond(405, ['success' => false, 'message' => 'Unsupported request']);
} catch (Throwable $e) {
    respond(500, ['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
}

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Garante que o utilizador está autenticado.
 */
function require_login(bool $as_json = false): void {
    if (!empty($_SESSION['user_id'])) return;

    if ($as_json || is_json_request()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => 'not-authenticated']);
        exit;
    }

    header('Location: index.php'); // redireciona para login
    exit;
}

function is_json_request(): bool {
    $h = array_change_key_case(getallheaders() ?: []);
    if (isset($h['x-requested-with']) && strtolower($h['x-requested-with']) === 'xmlhttprequest') return true;
    if (isset($h['accept']) && stripos($h['accept'], 'application/json') !== false) return true;
    return false;
}

function current_user_id(): ?int { return $_SESSION['user_id'] ?? null; }
function current_user_name(): ?string { return $_SESSION['user_name'] ?? null; }

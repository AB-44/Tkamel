<?php
/**
 * Auth API — tkamel
 * Handles: POST /api/auth.php?action=login
 *          POST /api/auth.php?action=register
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ─── DB Path ───────────────────────────────────────────────────────────────
$dbPath = __DIR__ . '/../../database/database.sqlite';

function getDB(string $path): PDO
{
    $pdo = new PDO('sqlite:' . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}

function jsonOut(array $data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ─── Input ─────────────────────────────────────────────────────────────────
$action = $_GET['action'] ?? '';
$body   = json_decode(file_get_contents('php://input'), true) ?? [];

// ─── Login ─────────────────────────────────────────────────────────────────
if ($action === 'login') {
    $email    = trim($body['email'] ?? '');
    $password = $body['password'] ?? '';

    if (!$email || !$password) {
        jsonOut(['success' => false, 'message' => 'البريد الإلكتروني وكلمة المرور مطلوبان'], 400);
    }

    try {
        $pdo = getDB($dbPath);

        // Check users table first (admin accounts)
        $stmt = $pdo->prepare('SELECT u.*, r.name AS role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id WHERE u.email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login timestamp
            $pdo->prepare('UPDATE users SET updated_at = ? WHERE id = ?')
                ->execute([date('Y-m-d H:i:s'), $user['id']]);

            jsonOut([
                'success'  => true,
                'type'     => 'user',
                'id'       => $user['id'],
                'name'     => $user['full_name'],
                'email'    => $user['email'],
                'role'     => $user['role_name'] ?? 'user',
                'redirect' => 'dashboard.html',
            ]);
        }

        // Check associations table
        $stmt2 = $pdo->prepare('SELECT * FROM associations WHERE email = ?');
        $stmt2->execute([$email]);
        $assoc = $stmt2->fetch();

        if ($assoc && password_verify($password, $assoc['password_hash'])) {
            $pdo->prepare('UPDATE associations SET updated_at = ? WHERE id = ?')
                ->execute([date('Y-m-d H:i:s'), $assoc['id']]);

            jsonOut([
                'success'  => true,
                'type'     => 'association',
                'id'       => $assoc['id'],
                'name'     => $assoc['association_name'],
                'email'    => $assoc['email'],
                'role'     => 'association',
                'redirect' => 'dashboard.html',
            ]);
        }

        jsonOut(['success' => false, 'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'], 401);

    } catch (Exception $e) {
        jsonOut(['success' => false, 'message' => 'خطأ في الخادم، حاول مرة أخرى'], 500);
    }
}

// ─── Register (Association) ─────────────────────────────────────────────────
if ($action === 'register') {
    $required = ['association_name', 'email', 'license_number', 'category', 'manager_name', 'phone', 'password'];
    foreach ($required as $field) {
        if (empty($body[$field])) {
            jsonOut(['success' => false, 'message' => 'جميع الحقول مطلوبة'], 400);
        }
    }

    $email    = trim(strtolower($body['email']));
    $password = $body['password'];
    $confirm  = $body['password_confirm'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonOut(['success' => false, 'message' => 'صيغة البريد الإلكتروني غير صحيحة'], 400);
    }

    if (strlen($password) < 8) {
        jsonOut(['success' => false, 'message' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل'], 400);
    }

    if ($confirm && $password !== $confirm) {
        jsonOut(['success' => false, 'message' => 'كلمتا المرور غير متطابقتين'], 400);
    }

    try {
        $pdo = getDB($dbPath);

        // Check email uniqueness across both tables
        $s1 = $pdo->prepare('SELECT id FROM associations WHERE email = ?');
        $s1->execute([$email]);
        if ($s1->fetch()) {
            jsonOut(['success' => false, 'message' => 'البريد الإلكتروني مسجل مسبقاً'], 409);
        }

        $s2 = $pdo->prepare('SELECT id FROM associations WHERE license_number = ?');
        $s2->execute([$body['license_number']]);
        if ($s2->fetch()) {
            jsonOut(['success' => false, 'message' => 'رقم الترخيص مسجل مسبقاً'], 409);
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $now  = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare(
            'INSERT INTO associations (association_name, email, license_number, category, manager_name, phone, password_hash, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $body['association_name'],
            $email,
            $body['license_number'],
            $body['category'],
            $body['manager_name'],
            $body['phone'],
            $hash,
            $now,
            $now,
        ]);

        jsonOut(['success' => true, 'message' => 'تم إنشاء الحساب بنجاح، سيتم مراجعة طلبكم خلال 48 ساعة عمل']);

    } catch (PDOException $e) {
        $msg = str_contains($e->getMessage(), 'UNIQUE') ? 'البيانات مسجلة مسبقاً' : 'خطأ في الخادم';
        jsonOut(['success' => false, 'message' => $msg], 500);
    }
}

jsonOut(['success' => false, 'message' => 'طلب غير صالح'], 400);

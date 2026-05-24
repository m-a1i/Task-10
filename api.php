<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

$host = 'localhost';
$db   = 'salon_cms';
$user = 'root'; // Adjust to database configurations
$pass = '';     // Adjust to database configurations
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection breakdown: " . $e->getMessage()]);
    exit;
}

$request_method = $_SERVER['REQUEST_METHOD'];

// --- GET ROUTE: Retrieve About Page Records ---
if ($request_method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM about WHERE id = 1 LIMIT 1");
        $data = $stmt->fetch();
        if ($data) {
            http_response_code(200);
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Core dataset missing."]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}

// --- POST/PUT ROUTE: Mutate About Page Content ---
if ($request_method === 'POST') {
    // Collect raw payload contents or fallback securely to default standard forms variables
    $inputData = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    // Strict Validation Mapping
    $company_name = isset($inputData['company_name']) ? trim($inputData['company_name']) : '';
    $description  = isset($inputData['description']) ? trim($inputData['description']) : '';
    $mission      = isset($inputData['mission']) ? trim($inputData['mission']) : '';
    $vision       = isset($inputData['vision']) ? trim($inputData['vision']) : '';
    $image_url    = isset($inputData['image_url']) ? trim($inputData['image_url']) : '';

    if (empty($company_name) || empty($description) || empty($mission) || empty($vision) || empty($image_url)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "All functional layout data parameters are strictly mandatory."]);
        exit;
    }

    try {
        $sql = "UPDATE about SET 
                company_name = :company_name, 
                description = :description, 
                mission = :mission, 
                vision = :vision, 
                image_url = :image_url 
                WHERE id = 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':company_name' => htmlspecialchars($company_name),
            ':description'  => htmlspecialchars($description),
            ':mission'      => htmlspecialchars($mission),
            ':vision'       => htmlspecialchars($vision),
            ':image_url'    => filter_var($image_url, FILTER_SANITIZE_URL)
        ]);

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "CMS deployment tables committed and active dynamically."]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Transaction failure: " . $e->getMessage()]);
    }
}
?>

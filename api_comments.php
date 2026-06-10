<?php
ini_set('display_errors', 0);
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

try {
    require_once 'db.php';
    ob_clean(); 
    $modeInput = $_GET['mode'] ?? ($_SERVER['REQUEST_METHOD'] === 'POST' ? (json_decode(file_get_contents('php://input'), true)['mode'] ?? 'null') : 'null');

    if ($modeInput === 'atrakcje') {
        $tablePlace = 'atrakcje';
        $tableComments = 'komentarze_atrakcji';
        $fkColumn = 'atrakcja_id';
        $nameColumn = 'nazwa'; 
    } else {
        $tablePlace = 'noclegi';
        $tableComments = 'komentarze_noclegi';
        $fkColumn = 'nocleg_id';
        $nameColumn = 'nazwa';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $osm_id = $_GET['osm_id'] ?? null;
        $osm_type = $_GET['osm_type'] ?? null;

        if (!$osm_id || !$osm_type) {
            echo json_encode([]);
            exit;
        }

        $sql = "SELECT u.imie, u.nazwisko, k.tresc, TO_CHAR(k.data_dodania, 'YYYY-MM-DD HH24:MI') as data_dodania
                FROM $tableComments k
                JOIN $tablePlace p ON k.$fkColumn = p.id
                JOIN uzytkownicy u ON k.uzytkownik_id = u.id
                WHERE p.osm_id = ? AND p.osm_type = ?
                ORDER BY k.data_dodania DESC";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([(string)$osm_id, (string)$osm_type]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Musisz być zalogowany!']);
            exit;
        }

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        if (empty($input['osm_id']) || empty($input['comment_text'])) {
            echo json_encode(['success' => false, 'error' => 'Brak danych']);
            exit;
        }

        $osm_id = $input['osm_id'];
        $osm_type = $input['osm_type'];
        $place_name = $input['place_name'] ?? 'Nieznane miejsce';
        $text = $input['comment_text'];
        $user_id = $_SESSION['user_id'];

        $pdo->beginTransaction();

        $stmtCheck = $pdo->prepare("SELECT id FROM $tablePlace WHERE osm_id = ? AND osm_type = ?");
        $stmtCheck->execute([(string)$osm_id, (string)$osm_type]);
        $place_id = $stmtCheck->fetchColumn();

        if (!$place_id) {
            $stmtInsert = $pdo->prepare("INSERT INTO $tablePlace (osm_id, osm_type, $nameColumn) VALUES (?, ?, ?) RETURNING id");
            $stmtInsert->execute([(string)$osm_id, (string)$osm_type, $place_name]);
            $place_id = $stmtInsert->fetchColumn();
        }

        $stmtCom = $pdo->prepare("INSERT INTO $tableComments ($fkColumn, uzytkownik_id, tresc, data_dodania) VALUES (?, ?, ?, NOW())");
        $stmtCom->execute([$place_id, $user_id, $text]);

        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;
    }

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
?>
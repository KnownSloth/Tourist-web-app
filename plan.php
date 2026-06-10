<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$userId = $_SESSION['user_id'];
$selectedPlanId = isset($_GET['plan_id']) ? (int)$_GET['plan_id'] : null;
$alertAtrakcje = '';
$alertNoclegi = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['plan-name'])) {
    $planName = trim($_POST['plan-name']);
    if ($planName !== '') {
      $stmt = $pdo->prepare("INSERT INTO plany_podrozy (uzytkownik_id, nazwa_planu, data_utworzenia) VALUES (?, ?, NOW())");
      $stmt->execute([$userId, $planName]);
    }
  }

  if (isset($_POST['delete-plan-id'])) {
    $deleteId = (int)$_POST['delete-plan-id'];
    $stmt = $pdo->prepare("DELETE FROM plany_podrozy WHERE id = ? AND uzytkownik_id = ?");
    $stmt->execute([$deleteId, $userId]);
    if ($selectedPlanId === $deleteId) {
      $selectedPlanId = null;
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atrakcja']) && $selectedPlanId) {
  $atrakcja = trim($_POST['atrakcja']);

  if ($atrakcja !== '') {
    $stmt = $pdo->prepare("SELECT id FROM atrakcje WHERE nazwa = ?");
    $stmt->execute([$atrakcja]);
    $atrakcjaId = $stmt->fetchColumn();

    if (!$atrakcjaId) {
      $stmt = $pdo->prepare("INSERT INTO atrakcje (nazwa) VALUES (?)");
      $stmt->execute([$atrakcja]);
      $atrakcjaId = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM plan_atrakcji WHERE plan_id = ? AND atrakcja_id = ?");
    $stmt->execute([$selectedPlanId, $atrakcjaId]);
    $exists = $stmt->fetchColumn();

    if ($exists == 0) {
      $stmt = $pdo->prepare("SELECT MAX(kolejnosc) FROM plan_atrakcji WHERE plan_id = ?");
      $stmt->execute([$selectedPlanId]);
      $maxOrder = (int)$stmt->fetchColumn();

      $stmt = $pdo->prepare("INSERT INTO plan_atrakcji (plan_id, atrakcja_id, kolejnosc) VALUES (?, ?, ?)");
      $stmt->execute([$selectedPlanId, $atrakcjaId, $maxOrder + 1]);
    } else {
      $alertAtrakcje = "Ta atrakcja jest już dodana do planu.";
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nocleg']) && $selectedPlanId) {
  $nocleg = trim($_POST['nocleg']);

  if ($nocleg !== '') {
    $stmt = $pdo->prepare("SELECT id FROM noclegi WHERE nazwa = ?");
    $stmt->execute([$nocleg]);
    $noclegId = $stmt->fetchColumn();

    if (!$noclegId) {
      $stmt = $pdo->prepare("INSERT INTO noclegi (nazwa) VALUES (?)");
      $stmt->execute([$nocleg]);
      $noclegId = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM plan_noclegi WHERE plan_id = ? AND nocleg_id = ?");
    $stmt->execute([$selectedPlanId, $noclegId]);
    $exists = $stmt->fetchColumn();

    if ($exists == 0) {
      $stmt = $pdo->prepare("SELECT MAX(kolejnosc) FROM plan_noclegi WHERE plan_id = ?");
      $stmt->execute([$selectedPlanId]);
      $maxOrder = (int)$stmt->fetchColumn();

      $stmt = $pdo->prepare("INSERT INTO plan_noclegi (plan_id, nocleg_id, kolejnosc) VALUES (?, ?, ?)");
      $stmt->execute([$selectedPlanId, $noclegId, $maxOrder + 1]);
    } else {
      $alertNoclegi = "Ten nocleg jest już dodany do planu.";
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_atrakcja_id']) && $selectedPlanId) {
  $deleteId = (int)$_POST['delete_atrakcja_id'];
  $stmt = $pdo->prepare("DELETE FROM plan_atrakcji WHERE plan_id = ? AND atrakcja_id = ?");
  $stmt->execute([$selectedPlanId, $deleteId]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_nocleg_id']) && $selectedPlanId) {
  $deleteId = (int)$_POST['delete_nocleg_id'];
  $stmt = $pdo->prepare("DELETE FROM plan_noclegi WHERE plan_id = ? AND nocleg_id = ?");
  $stmt->execute([$selectedPlanId, $deleteId]);
}

$stmt = $pdo->prepare("SELECT id, nazwa_planu, data_utworzenia FROM plany_podrozy WHERE uzytkownik_id = ? ORDER BY data_utworzenia DESC");
$stmt->execute([$userId]);
$plany = $stmt->fetchAll(PDO::FETCH_ASSOC);

$atrakcje = [];
$noclegi = [];

if ($selectedPlanId) {
  $stmt = $pdo->prepare("
    SELECT a.id, a.nazwa AS atrakcja, pa.kolejnosc
    FROM plan_atrakcji pa
    JOIN atrakcje a ON pa.atrakcja_id = a.id
    WHERE pa.plan_id = ?
    ORDER BY pa.kolejnosc
");
  $stmt->execute([$selectedPlanId]);
  $atrakcje = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt = $pdo->prepare("
    SELECT n.id, n.nazwa AS nocleg, pn.kolejnosc
    FROM plan_noclegi pn
    JOIN noclegi n ON pn.nocleg_id = n.id
    WHERE pn.plan_id = ?
    ORDER BY pn.kolejnosc
  ");
  $stmt->execute([$selectedPlanId]);
  $noclegi = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Plan podróży</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" href="images/favicon.png" type="image/png">
  <script>
    function confirmDelete(planId) {
      if (confirm("Czy na pewno chcesz usunąć ten plan?")) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'plan.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete-plan-id';
        input.value = planId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>
</head>
<body>
<?php include 'header.php'; ?>

<section class="page-header">
  <h2>Plan podróży</h2>
  <p>Twórz i zarządzaj planami swoich wyjazdów — dodawaj atrakcje oraz noclegi.</p>
</section>

<div class="page-container">
  <section class="plan-create-section">
    <h3>Utwórz nowy plan</h3>
    <form class="plan-create-form" method="post" action="plan.php">
      <label for="plan-name">Nazwa planu:</label>
      <input type="text" id="plan-name" name="plan-name" placeholder="Wpisz nazwę planu" required>
      <button type="submit" class="ui-btn">Utwórz plan</button>
    </form>
  </section>

  <section class="plan-list-section">
    <h3>Twoje plany podróży</h3>
    <?php if (count($plany) === 0): ?>
      <p class="no-plans-message">Nie masz jeszcze żadnych planów podróży. Utwórz swój pierwszy plan powyżej!</p>
    <?php else: ?>
      <div class="plan-card-wrapper">
        <?php foreach ($plany as $plan): ?>
          <div class="plan-card">
            <h4><?= htmlspecialchars($plan['nazwa_planu']) ?></h4>
            <?php $formattedDate = date('Y-m-d H:i', strtotime($plan['data_utworzenia'])); ?>
            <p><strong>Data utworzenia:</strong> <?= $formattedDate ?></p>
            <a href="plan.php?plan_id=<?= $plan['id'] ?>" class="ui-btn" style="text-decoration: none;">Otwórz plan</a>
            <button onclick="confirmDelete(<?= $plan['id'] ?>)" class="ui-btn danger">Usuń plan</button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

<?php
$aktywnyPlan = null;

foreach ($plany as $plan) {
    if ($plan['id'] == $selectedPlanId) {
        $aktywnyPlan = $plan;
        break;
    }
}
?>

<?php if ($selectedPlanId): ?>
<section class="plan-details-section">

    <?php if ($aktywnyPlan): ?>
        <h3>Szczegóły planu: <?= htmlspecialchars($aktywnyPlan['nazwa_planu']) ?></h3>
    <?php endif; ?>

    <div class="plan-block" id="atrakcje">
        <h4>Atrakcje w planie</h4>

        <?php if (!empty($alertAtrakcje)): ?>
            <div class="komunikat error"><?= htmlspecialchars($alertAtrakcje) ?></div>
        <?php endif; ?>

        <?php if (count($atrakcje) === 0): ?>
            <p class="no-plans-message">Brak atrakcji w tym planie.</p>
        <?php else: ?>
            <?php foreach ($atrakcje as $a): ?>
                <div class="plan-item">
                    <p><strong>Atrakcja:</strong> <?= htmlspecialchars($a['atrakcja']) ?></p>

                    <form method="post" action="plan.php?plan_id=<?= $selectedPlanId ?>#atrakcje" style="display:inline;">
                        <input type="hidden" name="delete_atrakcja_id" value="<?= $a['id'] ?>">
                        <button type="submit" style="color:red; background:none; border:none; cursor:pointer;">
                            Usuń atrakcję
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="post" action="plan.php?plan_id=<?= $selectedPlanId ?>#atrakcje" class="plan-create-form">
            <label for="atrakcja">Dodaj atrakcję:</label>
            <input type="text" id="atrakcja" name="atrakcja" placeholder="Wpisz nazwę atrakcji" required>
            <button type="submit" class="ui-btn">Dodaj atrakcję</button>
        </form>
    </div>
    <div class="plan-block" id="noclegi">
        <h4>Noclegi w planie</h4>

        <?php if (!empty($alertNoclegi)): ?>
            <div class="komunikat error"><?= htmlspecialchars($alertNoclegi) ?></div>
        <?php endif; ?>

        <?php if (count($noclegi) === 0): ?>
            <p class="no-plans-message">Brak noclegów w tym planie.</p>
        <?php else: ?>
            <?php foreach ($noclegi as $n): ?>
                <div class="plan-item">
                    <p><strong>Nocleg:</strong> <?= htmlspecialchars($n['nocleg']) ?></p>

                    <form method="post" action="plan.php?plan_id=<?= $selectedPlanId ?>#noclegi" style="display:inline;">
                        <input type="hidden" name="delete_nocleg_id" value="<?= $n['id'] ?>">
                        <button type="submit" style="color:red; background:none; border:none; cursor:pointer;">
                            Usuń nocleg
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="post" action="plan.php?plan_id=<?= $selectedPlanId ?>#noclegi" class="plan-create-form">
            <label for="nocleg">Dodaj nocleg:</label>
            <input type="text" id="nocleg" name="nocleg" placeholder="Wpisz nazwę noclegu" required>
            <button type="submit" class="ui-btn">Dodaj nocleg</button>
        </form>
    </div>
</section>
<?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

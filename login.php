<?php
require_once 'db.php';
session_start();

$alertMessage = '';
$alertType = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, haslo, imie, nazwisko FROM uzytkownicy WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['haslo'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['imie'];
        $alertMessage = "Logowanie zakończone sukcesem!";
        $alertType = "success";
        echo "<script>setTimeout(() => { window.location.href = 'index.php'; }, 2000);</script>";
    } else {
        $alertMessage = "Nieprawidłowy e-mail lub hasło.";
        $alertType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logowanie</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" href="images/favicon.png" type="image/png">
  <style>
    .komunikat {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      max-width: 300px;
      background: rgba(244, 67, 54); 
      color: #fff;
      text-align: center;
      padding: 15px;
      font-weight: bold;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      z-index: 1000;
    }
    .komunikat.success {
      background: rgba(76, 175, 80); 
    }
  </style>
</head>
<body>

  <?php if (!empty($alertMessage)): ?>
    <div class="komunikat <?= $alertType === 'success' ? 'success' : '' ?>">
      <?= htmlspecialchars($alertMessage) ?>
    </div>
  <?php endif; ?>

<?php include 'header.php'; ?>

<section class="page-header">
  <h2>Logowanie</h2>
  <div class="container auth-container">
    <form action="login.php" method="post" class="login-form">
      <label for="username">Adres e-mail:</label>
      <input type="text" id="username" name="username" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

      <label for="password">Hasło:</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Zaloguj się</button>
    </form>

    <div class="register-link">
      <p>Nie masz konta? <a href="rejestracja.php">Zarejestruj się</a></p>
    </div>
  </div>
</section>
<?php include 'footer.php'; ?> 
</body>
</html>

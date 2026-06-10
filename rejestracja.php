<?php
require_once 'db.php';

$alertMessage = '';
$redirectToLogin = false;

$username   = '';
$nazwisko   = '';
$email      = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username']);
    $nazwisko   = trim($_POST['nazwisko']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm-password'];
    $terms      = isset($_POST['terms']);
    $data       = date('Y-m-d');

    if (!$terms) {
        $alertMessage = "Musisz zaakceptować regulamin.";
    } elseif ($password !== $confirm) {
        $alertMessage = "Hasła się nie zgadzają.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertMessage = "Nieprawidłowy adres e-mail.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM uzytkownicy WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $alertMessage = "Ten adres e-mail jest już zarejestrowany.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO uzytkownicy (email, haslo, imie, nazwisko, data_rejestracji) VALUES (?, ?, ?, ?, ?)");
            $success = $stmt->execute([$email, $hashed, $username, $nazwisko, $data]);

            if ($success) {
                $alertMessage = "Rejestracja zakończona sukcesem! Zaloguj się.";
                $redirectToLogin = true;
            } else {
                $alertMessage = "Wystąpił błąd podczas rejestracji.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rejestracja</title>
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
    <div class="komunikat <?= $redirectToLogin ? 'success' : '' ?>">
      <?= htmlspecialchars($alertMessage) ?>
    </div>
    <?php if ($redirectToLogin): ?>
      <script>
        setTimeout(() => { window.location.href = "login.php"; }, 2000);
      </script>
    <?php endif; ?>
  <?php endif; ?>

 <?php include 'header.php'; ?>

  <section class="page-header">
    <h2>Rejestracja</h2>
    <div class="container auth-container">
      <form class="register-form" method="post" action="rejestracja.php">
        <label for="username">Imię:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>

        <label for="nazwisko">Nazwisko:</label>
        <input type="text" id="nazwisko" name="nazwisko" value="<?= htmlspecialchars($nazwisko) ?>" required>

        <label for="email">Adres e-mail:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="password">Hasło:</label>
        <div class="password-wrapper">
          <input type="password" id="password" name="password" required>
        </div>

        <label for="confirm-password">Powtórz hasło:</label>
        <div class="password-wrapper">
          <input type="password" id="confirm-password" name="confirm-password" required>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="terms" name="terms" required>
          <label for="terms">Akceptuję regulamin i politykę prywatności</label>
        </div>

        <button type="submit">Zarejestruj się</button>
      </form>

      <div class="login-link">
        <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
      </div>
    </div>
  </section>
<?php include 'footer.php'; ?>
</body>
</html>

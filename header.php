<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>

<header>
  <nav class="navbar">  
    <div class="logo-wrapper">
      <img src="images/logo.png" alt="Logo" class="logo-img">
    </div>
    <ul class="nav-links">
      <li><a href="index.php">Strona główna</a></li>
      <li><a href="atrakcje.php">Atrakcje</a></li>
      <li><a href="noclegi.php">Noclegi</a></li>
      <li><a href="plan.php">Plan podróży</a></li>
    </ul>
    <div class="Login">
      <?php if ($isLoggedIn): ?>
        <li><a>Witaj, <?= htmlspecialchars($userName) ?></a></li>
        <li><a href="logout.php">Wyloguj się</a></li>
      <?php else: ?>
        <li><a href="login.php">Zaloguj się</a></li>
      <?php endif; ?>
    </div>  
  </nav>
</header>

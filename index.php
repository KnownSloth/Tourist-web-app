<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Podróże</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" href="images/favicon.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
  <section class="hero">
    <img src="images/banner.jpg" class="hero-image">
    <div class="hero-text">
      <h1>Stwórz swoją idealną podróż</h1>
    </div>
  </section>
<section class="attractions">
  <h2>Najczęściej odwiedzane atrakcje</h2>
  <div class="attraction-grid">
    <div class="attraction-card">
      <img src="images/bedzin-panorama.jpg">
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia, eius atque! Nostrum nesciunt ullam nam debitis, atque dolores labore ut voluptatibus natus veritatis maxime! Cumque recusandae quibusdam unde dolor fugiat!</p>
    </div>
    <div class="attraction-card">
      <img src="images/bedzin-panorama.jpg">
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia, eius atque! Nostrum nesciunt ullam nam debitis, atque dolores labore ut voluptatibus natus veritatis maxime! Cumque recusandae quibusdam unde dolor fugiat!</p>
    </div>
    <div class="attraction-card">
      <img src="images/bedzin-panorama.jpg">
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia, eius atque! Nostrum nesciunt ullam nam debitis, atque dolores labore ut voluptatibus natus veritatis maxime! Cumque recusandae quibusdam unde dolor fugiat!</p>
    </div>
    <div class="attraction-card">
      <img src="images/bedzin-panorama.jpg">
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia, eius atque! Nostrum nesciunt ullam nam debitis, atque dolores labore ut voluptatibus natus veritatis maxime! Cumque recusandae quibusdam unde dolor fugiat!</p>
    </div>
  </div>  
</section>
<?php include 'footer.php'; ?>
</body>
</html>

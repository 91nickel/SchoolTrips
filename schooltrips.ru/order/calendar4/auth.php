<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<script src='lib/moment.min.js'></script>
<script src='lib/jquery.min.js'></script>
<script src='fullcalendar.min.js'></script>
<script src='locale-all.js'></script>
<link rel="stylesheet" href="css/uikit.min.css" />
<script src="js/uikit.min.js"></script>
<script src="js/uikit-icons.min.js"></script>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

<script src="script.js"></script>
<link rel="stylesheet" href="main.css">
</head>
<body class="auth_page">
  <div class="menu">
    <img src="/images/logo.png" alt="">
    <a href="index.php" class="el">Купить билет</a>
    <a href="events.php" class="el">Пожарные в городе</a>
    <a href="exc.php" class="el my">Мои события</a>
    <a href="kurs.php" class="el">Онлайн-курсы</a>
    <a href="abon.php" class="el">Абонементы</a>
  </div>
  <p>
    Для того, чтобы войти, введите свой номер телефона в формате +79997776666 и нажмите "Войти".
  </p>

  <div class="filter auth_wr">
    <form id="auth_form">
      <input type="text" name="tel" class="uk-input" placeholder="Номер телефона(+79998887777)">
      <button class="uk-button uk-button-primary" name="auth">Войти</button>
    </form>
  </div>

  <?php
  require_once 'counters.php';
  ?>

</body>
</html>

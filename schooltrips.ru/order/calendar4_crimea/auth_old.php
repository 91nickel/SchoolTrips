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
  <p>
    Для того, чтобы войти, введите свой номер телефона в формате +79997776666 и пароль, который вы ранее получали в смс. Если вы не получали смс с паролем, введите номер телефона и нажмите на "получить пароль".
  </p>

  <div class="filter auth_wr">
    <form id="auth_form">
      <input type="text" name="tel" class="uk-input" placeholder="Номер телефона(+79998887777)">
      <input type="text" name="pass" class="uk-input" placeholder="Пароль">
      <button class="uk-button uk-button-primary" name="auth">Войти</button>
      <button class="uk-button uk-button-primary" name="get_pass">Получить пароль</button>
    </form>
  </div>

</body>
</html>

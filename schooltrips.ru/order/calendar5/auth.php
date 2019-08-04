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
    <img src="http://xn--b1adlabiadbhbyletoc8a9gxdg8c.xn--p1ai/images/logo.png" alt="">
    <a href="index.php" class="el">Купить билет</a>
    <a href="events.php" class="el">Карамельная фабрика</a>
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
  <!-- Yandex.Metrika counter -->
  <script type="text/javascript" >
	  (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
		  m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	  (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	  ym(39632640, "init", {
		  id:39632640,
		  clickmap:true,
		  trackLinks:true,
		  accurateTrackBounce:true,
		  webvisor:true
	  });
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/39632640" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->

  <script>
	  (function(w, d, s, h, id) {
		  w.roistatProjectId = id; w.roistatHost = h;
		  var p = d.location.protocol == "https:" ? "https://" : "http://";
		  var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init";
		  var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
	  })(window, document, 'script', 'cloud.roistat.com', 'edb0dcbb806ca8e8a872b99c4c78a84e');
  </script>


</body>
</html>

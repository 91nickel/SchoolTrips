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

<script src="script.js?v=3"></script>
<link rel="stylesheet" href="main.css">
</head>
<body>
  <div class="menu">
    <img src="http://xn--b1adlabiadbhbyletoc8a9gxdg8c.xn--p1ai/images/logo.png" alt="">
    <a href="index.php" class="el">Купить билет</a>
    <a href="events.php" class="el">Карамельная фабрика</a>
    <a href="exc.php" class="el">Мои события</a>
    <a href="kurs.php" class="el sel">Онлайн-курсы</a>
    <a href="abon.php" class="el">Абонементы</a>
  </div>
  <div class="user"></div>
  <a href="auth.php" class="uk-button auth uk-button-primary">Войти</a>
  <p>
    Для того, чтобы приобрести курс:
    <br><br>1. Выберите курс
    <br>2. Заполните личные данные
    <br>3. Вас перенаправит на страницу оплаты, оплатите экскурсию банковской картой
    <br>4. После оплаты вы получите СМС об оплате
    <br>5. После вас добавят в группу whatsapp, куда будут приходить все метарилы курса
    <br><br>Если у вас возникли трудности, можете позвонить и забронировать место по телефону 8 (499) 938 47 00
  </p>

  <div class="events kurs">
    <div class="block"></div>
  </div>

  <div id="form" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
          <button class="uk-modal-close-default" type="button" uk-close></button>
          <h2 class="uk-modal-title">Введите данные</h2>
          <form>
              <? if(!isset($_GET['amo'])) {?>
              <div class="uk-margin">
                <input id="name" class="uk-input" placeholder="Имя">
              </div>
              <div class="uk-margin">
                <input id="tel" class="uk-input" placeholder="Телефон(+79998887777)">
              </div>
              <div class="uk-margin">
                <input id="email" class="uk-input" placeholder="Email(test@test.ru)">
              </div>
              <? } ?>
              <button class="uk-button uk-button-primary makeOrder" style="float: right">Записать</button>
              <h3 style="margin-top: 20px;">Стоимость: <b class="price">0</b>р</h3>
          </form>
         
      </div>
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

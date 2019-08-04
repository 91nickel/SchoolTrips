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
<body>
  <div class="menu">
    <img src="http://xn--b1adlabiadbhbyletoc8a9gxdg8c.xn--p1ai/images/logo.png" alt="">
    <a href="index.php" class="el">Купить билет</a>
    <a href="exc.php" class="el">Мои экскурсии</a>
    <a href="kurs.php" class="el">Онлайн-курсы</a>
    <a href="abon.php" class="el sel">Абонементы</a>
  </div>
  <div class="user"></div>
  <a href="auth.php" class="uk-button auth uk-button-primary">Войти</a>
  <p>
    Для того, чтобы приобрести абоменет:
    <br><br>1. Выберите абонемент
    <br>2. Заполните личные данные
    <br>3. Вас перенаправит на страницу оплаты, оплатите банковской картой
    <br>4. После оплаты вы получите СМС об оплате
    <br>5. После у вас появится возможность делать запись по абонементу, кликнув на соответствующую кнопку в процессе записи
    <br><br>Если у вас возникли трудности, можете позвонить и уточнить информацию по телефону 8 (499) 938 47 00
  </p>

  <div class="events abon">
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
              <button class="uk-button uk-button-primary makeOrder" data="abon" style="float: right">Купить</button>
              <h3 style="margin-top: 20px;">Стоимость: <b class="price">0</b>р</h3>
              <p>Нажимая на "купить" вы соглашаетесь с <a href="#" uk-toggle="target: #offert" type="button">офертой</a></p>
          </form>
         
      </div>
  </div>

  <div id="offert" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
          <button class="uk-modal-close-default" type="button" uk-close></button>
          <h2 class="uk-modal-title">Публичная оферта</h2>
          <p>
            Условия покупки абонементов.
            <br>1. В наличие абонементы 2-х типов: 4-х месячный (4 экскурсии) и 9-ти месячный (9 экскурсий + 1 в подарок на выбор).
            <br>2. 4-х месячный абонемент включает в себя 4 экскурсии на любую музейную/усадебную/парковую или сезонную программу. В программу абонементов не входят однодневными  и многодневные выездные мероприятия!
            <br>3. 9-ти месячный абонемент включает в себя 9 экскурсий + 1 экскурсия в подарок на выбор  на любую музейную/усадебную/парковую  или сезонную программу. В программу абонементов не входят однодневными  и многодневные выездные мероприятия!
            <br>4. Срок действия абонемента:
            <br>🔸4-х месячный: с 1.09.2018 г. по 31.12.2018 г.
            <br>🔸9-ти месяный: с 1.09.2018 г. по 31.05.2019 г.
            <br>5. Абонементы не являются именными, поэтому могут быть переданы 3-им лицам для использования! 
            <br>6. Абонемент не ограничивает количество используемых экскурсий в месяц. Обладатель абонемента имеет право посетить любое количество экскурсий в месяц согласно расписанию для сборных групп!
            <br>7. Абонементом могут воспользоваться сразу несколько детей, в этом случае количество используемых экскурсий рассчитывается изходя из количества детей и на каждого ребёнка приходится 1 (одна) экскурсия!
            <br>8. Абонемент позволяет отменить или перенести экскурсию не позднее чем за день до предполагаемой даты экскурсии! Днём считается время с 10:00 до 18:00, в которое необходимо сообщить о невозможности посещения экскурсии любым удобным способом: тел. 8(499) 9384700, почта schooltrip@yandex.ru, или в личном кабинете.
            <br>9. При сообщении о невозможности посещения экскурсии по абонементу в более поздние сроки, чем указано в пп. 8 - экскурсия сгорает!
            <br>10. При оплате вы соглашаетесь со всеми условиями, перечисленными выше.
            <br>11. При отказе от оплаченного абонемента, проект вправе удержать комиссию в размере - 1500 руб., при этом использованные экскурсии перерасчитываются по стоимости для сборных групп на момент отказа от абонемента.
          </p>
         
      </div>
  </div>

</body>
</html>

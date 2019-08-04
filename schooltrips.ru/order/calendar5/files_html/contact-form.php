    <?php
	require "./less/func.php";
	require "./less/lessc.inc.php";
	
	# -------- META---------- #
	$meta = new stdClass();
	# Название сайта
	$meta->sitename = '';
	# Заголовок
	$meta->title = '';
	# Описание
	$meta->desc = '';
	# Текущий линк на страницу
	$meta->url = getBaseUrl();
	# Картинка для соц. сетей, размер: 1200x630px
	$meta->image = $meta->url.'/images/social.jpg';
	
    # ----------------------- ВНИМАНИЕ ----------------------- #
    /*
        Далее находится php функция, которая генерирует CSS из Less
        Если есть необходиость править стили, то править нужно Less файл (!!!)
        Less файл лежит тут: assets/less/style.less
        Если у вас не обновляются стили на новом хосте, нужно 
        удалить кеш-файл: assets/less/style.less.cache (!!!)
        Вопросы, жалобы, пожелания писать сюда: colin990@gmail.com :)
    */
	# Функция для компиляции CSS из Less
    autoCompileLess('./less/style.less', './css/style.css');
?>
<!DOCTYPE html>

<html>
	<head>
		<title><?php echo $meta->title; ?></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="plugins/bxslider/jquery.bxslider.css">
        <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="plugins/fancybox/jquery.fancybox.min.css">
        <link rel="stylesheet" href="plugins/slider/settings.css">
        <link rel="stylesheet" href="plugins/slider/navstylechange.css">
        <link rel="stylesheet" href="css/fontawesome-all.css">
        <link rel="stylesheet" href="css/style.css">
        
        <script src="plugins/jquery/jquery-3.2.0.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
       <script src="plugins/bxslider/jquery.bxslider.min.js"></script>
       <script src="plugins/bootstrap/js/popper.min.js"></script>
       <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
       <script src="plugins/fancybox/jquery.fancybox.min.js"></script>
       <script src="plugins/jqueryvalidate/jquery.validate.min.js"></script>
       <script src="plugins/slider/jquery.themepunch.plugins.min.js"></script>
       <script src="plugins/slider/jquery.themepunch.revolution.min.js"></script>
       <script src="plugins/inputmask/jquery.inputmask.bundle.js"></script>
       <script src="plugins/datepicker/jquery-ui.min.js"></script>
       <script src="plugins/datepicker/datepicker-ru.js"></script>
       <script src="js/my.js"></script>
		
	</head>
	<body>
      <div class="wrapper">
           <section class="contacts-form">
        <div class="container">
            <a href="./"><img src="images/header-logo.png" alt=""></a>
            <div class="date-time-loc">
                <div class="date-age d-flex">
                    <p class="date-month big">22 сен,</p>
                    <p class="time big">19:00</p>
                    <p class="symbol big">|</p>
                    <p class="age big">6-8 лет</p>
                </div>
                <div class="top-t">Сказки о красной<br class="d-none d-xl-inline"> прекрасной площади</div>
                <div class="location-u d-flex align-items-center">
                    <p>м. Охотный ряд, Манежная площадь д. 1</p>
                </div>
                <div class="ticket-types d-flex">
                    <p class="ticket-type">1 детский, </p>
                    <p class="ticket-type">1 взрослый</p>
                </div>
            </div>
            <div class="for-modal">
                <form action="" method="post">
                    <div class="input-block ib-1">
                        <label for="name">Введите имя</label>
                        <div class="in-1">
                            <input type="text" name="name" placeholder="Иван" required>
                        </div>
                    </div>
                    <div class="input-block ib-1">
                        <label for="name">Введите e-mail</label>
                        <div class="in-2">
                            <input type="text" name="email" placeholder="ivanov@ivan.ru" required>
                        </div>
                    </div>
                    <div class="input-block ib-1">
                        <label for="phone">Введите телефон</label>
                        <div class="in-3">
                            <input type="text" class="phone" name="phone" placeholder="+7 (999) 999 - 99 - 99" required>
                        </div>
                    </div>
                    <div class="total">
                        <p class="sum">Итого:</p>
                        <p class="price">1 700 руб.</p>
                    </div>
                    <div class="pay-methods d-flex align-items-center">
                        <div class="order or-1"><a href="#">Перейти к оплате</a></div>
                    </div>
                    <p class="after-p">
                        После оплаты мы вышлем СМС с информацией о месте, времени проведения экскурсии и контакты куратора
                    </p>
                </form>
            </div>
        </div>
    </section>
          <?php include 'footer.php'; ?>
      </div>
    </body>
</html>
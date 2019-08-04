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
	        <section class="order-form">
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
                    <div class="img"><img src="images/loc-img.png" alt=""></div>
                    <p>м. Охотный ряд, Манежная площадь д. 1</p>
                    <a href="#" class="small">посмотреть на карте</a>
                </div>
            </div>
            <div class="for-modal">
                <form action="" method="post">
                    <div class="choise">
                        <div class="line d-flex justify-content-between align-items-center">
                            <p class="ticket-type">Детский</p>
                            <p class="price">1 200 руб.</p>
                            <p class="amount grey">3 билета</p>
                            <div class="amount-block d-flex">
                                <a href="#" class="div minus d-flex justify-content-center align-items-center">-</a>
                                <input class="current-amount" name="quantity" size="3" value="0">
                                <a href="#" class="div plus d-flex justify-content-center align-items-center">+</a>
                            </div>
                        </div>
                        <div class="line d-flex justify-content-between align-items-center">
                            <p class="ticket-type">Взрослый</p>
                            <p class="price">500 руб.</p>
                            <p class="amount grey">2 билета</p>
                            <div class="amount-block d-flex">
                                <a href="#" class="div minus d-flex justify-content-center align-items-center">-</a>
                                <input class="current-amount" name="quantity" size="3" value="0">
                                <a href="#" class="div plus d-flex justify-content-center align-items-center">+</a>
                            </div>
                        </div>
                    </div>
                    <div class="input-block">
                        <p class="big">Промокод</p>
                        <input type="text" name="promocode" placeholder="PROMOKOD">
                    </div>
                    <div class="total">
                        <p class="sum">Итого:</p>
                        <p class="price">1 700 руб.</p>
                    </div>
                    <div class="pay-methods d-flex align-items-center">
                        <div class="order or-1"><a href="contact-form.php">Оплатить</a></div>
                        <p class="grey">или</p>
                        <div class="order or-2">
                            <a href="#">Оплатить в счет абонемента (5)</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
	    </div>
    </body>
</html>
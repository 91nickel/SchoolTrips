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
  <?php include 'modals.php'; ?>
  <div class="wrapper">
  <?php include 'header.php'; ?>
    <section class="subscription">
        <div class="container">
           <div class="top-t">Абонементы на экскурсии</div>
            <div class="row r-1">
                <div class="col-12 col-md-4">
                    <div class="packet-b text-center pab-1">
                        <div class="big-m grey bt">Пакет</div>
                        <div class="top-t"><a href="#"><u>«Начальный»</u></a></div>
                        <div class="quant">
                            <p class="digit">5</p>
                            <p class="big">экскурсий</p>
                        </div>
                        <div class="price">5 500 <span>₽</span></div>
                        <div class="order">
                            <a href="#">Купить</a>
                        </div>
                        <div class="big-m grey bb">Действует до 31.12.18 </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="packet-b text-center pab-2">
                        <div class="hit"><p>Хит</p></div>
                        <div class="big-m grey bt">Пакет</div>
                        <div class="top-t "><a href="#" class="p-blue"><u>«Исследователь»</u></a></div>
                        <div class="quant">
                            <p class="digit">10</p>
                            <p class="big">экскурсий</p>
                        </div>
                        <div class="price p-blue">5 500 <span>₽</span></div>
                        <div class="order">
                            <a href="#">Купить</a>
                        </div>
                        <div class="big-m grey bb">Действует до 31.12.18 </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="packet-b text-center pab-3">
                        <div class="gold"><p>Gold</p></div>
                        <div class="big-m p-gold bt">Пакет</div>
                        <div class="top-t"><a href="#" class="p-gold"><u>«Покоритель»</u></a></div>
                        <div class="quant">
                            <p class="digit">20</p>
                            <p class="big">экскурсий</p>
                        </div>
                        <div class="price p-gold">5 500 <span>₽</span></div>
                        <div class="order">
                            <a href="#">Купить</a>
                        </div>
                        <div class="big-m bb p-gold">Действует до 31.12.18 </div>
                    </div>
                </div>
            </div>
            <div class="row r-2">
                <div class="col-12 col-md-4">
                    <div class="packet-b text-center pab-1">
                        <div class="big-m grey bt">Пакет</div>
                        <div class="top-t"><a href="#"><u>«Месяц»</u></a></div>
                        <div class="quant">
                            <p class="digit">30</p>
                            <p class="big">дней</p>
                        </div>
                        <div class="price">10 000 <span>₽</span></div>
                        <div class="order">
                            <a href="#">Купить</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="packet-b text-center pab-1">
                        <div class="big-m grey bt">Пакет</div>
                        <div class="top-t"><a href="#"><u>«3 месяца»</u></a></div>
                        <div class="quant">
                            <p class="digit">90</p>
                            <p class="big">дней</p>
                        </div>
                        <div class="price">25 000 <span>₽</span></div>
                        <div class="order">
                            <a href="#">Купить</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="packet-b text-center pab-1">
                        <div class="big-m grey bt">Пакет</div>
                        <div class="top-t"><a href="#"><u>«Пол года»</u></a></div>
                        <div class="quant">
                            <p class="digit">180</p>
                            <p class="big">дней</p>
                        </div>
                        <div class="price">40 000 <span>₽</span></div>
                        <div class="order">
                            <a href="#">Купить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
   </div>
	</body>
</html>
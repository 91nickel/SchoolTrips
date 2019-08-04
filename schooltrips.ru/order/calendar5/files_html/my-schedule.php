<?php
	require "./less/func.php";
	require "./less/lessc.inc.php";
	
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
    <section class="my-schedule">
        <div class="container">
            <div class="top-t">Мое расписание</div>
            <div class="for-top-t d-flex align-items-center">
                <div class="not-payed-t">Неоплаченные</div>
                <div class="slides-count-3 slides-count not-payed-t">(5)</div>
                <div class="line"></div>
            </div>
            <div class="for-slider">
                <div class="slideshow3">
                    <div class="feed-slide">
                        <div class="up-text">
                           <p class="close">&times;</p>
                            <div class="text">
                                <div class="p-block">
                                     <div class="info d-flex">
                                         <p class="name">Экскурсия</p>
                                         <p class="symb">|</p>
                                         <p class="age">6-8 лет</p>
                                     </div>
                                     <div class="top-t">Почему исчезли динозавры</div>
                                     <div class="types">
                                         <a href="#" data-toggle="modal" data-target="#modal-pay"><u class="dotted">1 взрослый, 1 детский</u></a>
                                     </div>
                                     <a class="date-time d-flex" href="#"  data-toggle="modal" data-target="#modal-sched">
                                         <u class="m-date dotted">Ср, 15 октября</u>
                                         <p class="symb">|</p>
                                         <u class="time dotted">20:00</u>
                                     </a>
                                     <div class="price">1 200 руб.</div>
                                     <div class="order or-1">
                                         <a href="#">Оплатить</a>                                     
                                     </div>
                                     <div class="order">
                                         <a href="#">В счет абонемента</a>
                                     </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="up-text">
                           <p class="close">&times;</p>
                            <div class="text">
                            <div class="p-block">
                                 <div class="info d-flex">
                                     <p class="name">Абонемент</p>
                                 </div>
                                 <div class="top-t">Пакет Начальный - 5 экскурсий</div>
                                 <div class="price">5 500 руб.</div>
                                 <div class="order or-1">
                                     <a href="#">Оплатить</a>                                     
                                 </div>
                                 <div class="order">
                                     <a href="#">В счет абонемента</a>
                                 </div>
                             </div>
                        </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="up-text">
                           <p class="close">&times;</p>
                            <div class="text">
                                <div class="p-block">
                                     <div class="info d-flex">
                                         <p class="name">Экскурсия</p>
                                         <p class="symb">|</p>
                                         <p class="age">6-8 лет</p>
                                     </div>
                                     <div class="top-t">Почему исчезли динозавры</div>
                                     <div class="types">
                                         <a href="#" data-toggle="modal" data-target="#modal-pay"><u class="dotted">1 взрослый, 1 детский</u></a>
                                     </div>
                                     <a class="date-time d-flex" href="#"  data-toggle="modal" data-target="#modal-sched">
                                         <u class="m-date dotted">Ср, 15 октября</u>
                                         <p class="symb">|</p>
                                         <u class="time dotted">20:00</u>
                                     </a>
                                     <div class="price">1 200 руб.</div>
                                     <div class="order or-1">
                                         <a href="#">Оплатить</a>                                     
                                     </div>
                                     <div class="order">
                                         <a href="#">В счет абонемента</a>
                                     </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="up-text">
                           <p class="close">&times;</p>
                            <div class="text">
                                <div class="p-block">
                                     <div class="info d-flex">
                                         <p class="name">Экскурсия</p>
                                         <p class="symb">|</p>
                                         <p class="age">6-8 лет</p>
                                     </div>
                                     <div class="top-t">Почему исчезли динозавры</div>
                                     <div class="types">
                                         <a href="#" data-toggle="modal" data-target="#modal-pay"><u class="dotted">1 взрослый, 1 детский</u></a>
                                     </div>
                                     <a class="date-time d-flex" href="#"  data-toggle="modal" data-target="#modal-sched">
                                         <u class="m-date dotted">Ср, 15 октября</u>
                                         <p class="symb">|</p>
                                         <u class="time dotted">20:00</u>
                                     </a>
                                     <div class="price">1 200 руб.</div>
                                     <div class="order or-1">
                                         <a href="#">Оплатить</a>                                     
                                     </div>
                                     <div class="order">
                                         <a href="#">В счет абонемента</a>
                                     </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="up-text">
                           <p class="close">&times;</p>
                            <div class="text">
                                <div class="p-block">
                                     <div class="info d-flex">
                                         <p class="name">Экскурсия</p>
                                         <p class="symb">|</p>
                                         <p class="age">6-8 лет</p>
                                     </div>
                                     <div class="top-t">Почему исчезли динозавры</div>
                                     <div class="types">
                                         <a href="#" data-toggle="modal" data-target="#modal-pay"><u class="dotted">1 взрослый, 1 детский</u></a>
                                     </div>
                                     <a class="date-time d-flex" href="#"  data-toggle="modal" data-target="#modal-sched">
                                         <u class="m-date dotted">Ср, 15 октября</u>
                                         <p class="symb">|</p>
                                         <u class="time dotted">20:00</u>
                                     </a>
                                     <div class="price">1 200 руб.</div>
                                     <div class="order or-1">
                                         <a href="#">Оплатить</a>                                     
                                     </div>
                                     <div class="order">
                                         <a href="#">В счет абонемента</a>
                                     </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="for-top-t d-flex align-items-center">
                <div class="not-payed-t">Оплаченные</div>
                <div class="slides-count-4 slides-count not-payed-t">(1)</div>
                <div class="line"></div>
            </div>
            <div class="for-slider">
                <div class="slideshow3 payed-slider">
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                 <div class="info d-flex">
                                     <p class="name">Программа</p>
                                     <p class="symb">|</p>
                                     <p class="age">6-8 лет</p>
                                 </div>
                                 <div class="top-t">Третьяковка малышам - 16 экскурсий</div>
                                 <div class="date-time d-flex">
                                     <p class="big-m">С 01.30.18 по 30.04.19</p>
                                 </div>
                                 <div class="order or-1">
                                     <a href="#"  data-toggle="modal" data-target="#modal-info">Подробнее</a>                                     
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="for-top-t d-flex align-items-center">
                <div class="not-payed-t">Прошедшие</div>
                <div class="slides-count-5 slides-count not-payed-t">(2)</div>
                <div class="line"></div>
            </div>
            <div class="for-slider">
                <div class="slideshow3 overed-slider">
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                 <div class="info d-flex">
                                     <p class="name">Программа</p>
                                     <p class="symb">|</p>
                                     <p class="age">6-8 лет</p>
                                 </div>
                                 <div class="top-t">Почему исчезли динозавры</div>
                                 <div class="date-time d-flex">
                                     <u class="m-date">Ср, 15 октября</u>
                                     <p class="symb">|</p>
                                     <u class="time">20:00</u>
                                 </div>
                                 <div class="order or-1">
                                     <a href="#">Открыть фотоотчет</a>
                                 </div>
                                 <div class="order or-2">
                                     <a href="#" >Написать отзыв</a>
                                 </div>
                             </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                 <div class="info d-flex">
                                     <p class="name">Программа</p>
                                     <p class="symb">|</p>
                                     <p class="age">6-8 лет</p>
                                 </div>
                                 <div class="top-t">Почему исчезли динозавры</div>
                                 <div class="date-time d-flex">
                                     <u class="m-date">Ср, 15 октября</u>
                                     <p class="symb">|</p>
                                     <u class="time">20:00</u>
                                 </div>
                                 <div class="order or-1">
                                     <a href="#">Открыть фотоотчет</a>
                                 </div>
                                 <div class="order or-2">
                                     <a href="#">Написать отзыв</a>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="for-top-t d-flex align-items-center">
                <div class="not-payed-t">Абонементы</div>
                <div class="line"></div>
            </div>
            <div class="feed-slide fs-1">
                <div class="text">
                    <div class="p-block text-center">
                         <div class="info">
                             Пакет
                         </div>
                         <div class="top-t">«Начальный»</div>
                        <div class="quant">
                            <p class="digit">5</p>
                            <p class="big">экскурсий</p>
                        </div>
                         <div class="order or-1">
                             <a href="index.php">Выбрать экскурсию</a>                                     
                         </div>
                         <div class="big-m grey bb">Действует до 31.12.18 </div>
                     </div>
                </div>
            </div>
        </div>
    </section>
    
    
    
    <div class="modal fade" id="modal-info" tabindex="-1" role="dialog" aria-labelledby="modal-info" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="date-time-loc">
                    <div class="date-age d-flex">
                        <p class="date-month big">22 сен,</p>
                        <p class="time big">19:00</p>
                        <p class="symbol big">|</p>
                        <p class="age big">6-8 лет</p>
                    </div>
                    <div class="top-t">Сказки о красной<br class="d-none d-xl-inline"> прекрасной площади</div>
                </div>
                <div class="p-block">
                    <p class="grey big-m">Куратор:</p>
                    <p class="nik">Никитин Антон</p>
                    <p class="phone big">+7 903 231-91-89</p>
                    <p class="grey big-m">Место встречи:</p>
                    <p class="big-m simple">м. Охотный ряд, Манежная площадь д. 1</p>
                    <p class="grey big-m">Состав:</p>
                    <p class="big-m simple">1 взрослый, 1 ребенок</p>
                </div>
                <div class="img">
                    <img src="images/qr-code.png" alt="">
                </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
   </div>
	</body>
</html>
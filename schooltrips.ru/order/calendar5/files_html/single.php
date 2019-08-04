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
    <section class="excursion-info">
        <div class="container">
            <div class="info-block">
                <div class="name-age d-flex">
                    <p class="grey exc-name">Красная площадь </p> 
                    <p class="grey space"> / </p>
                    <p class="grey age"> 6-8 лет</p>
                </div>
                <div class="top-t">Сказки о красной<br class="d-none d-xl-inline"> прекрасной площади</div>
                <div class="location-u d-flex align-items-center">
                    <div class="img"><img src="images/loc-img.png" alt=""></div>
                    <p>м. Охотный ряд, Манежная площадь д. 1</p>
                    <a href="#" class="small">посмотреть на карте</a>
                </div>
            </div>
            <div class="order-block d-flex align-items-center">
                <div class="order or-1">
                    <a href="#" data-toggle="modal" data-target="#modal-sched">Расписание и билеты</a>
                </div>
                <p class="grey">или</p>
                <div class="or-2">
                    <u><a href="#">Записаться<br class="d-none d-xl-inline"> группой</a></u>
                </div>
                <div class="socials d-flex">
                    <div style="background-color: #00599F;"><a href="#"><i class="fab fa-facebook-f"></i></a></div>
                    <div style="background-color: #00AFF5;"><a href="#"><i class="fab fa-twitter"></i></a></div>
                    <div style="background-color: #00668F;"><a href="#"><i class="fab fa-vk"></i></a></div>
                    <div style="background-color: #FF9400;"><a href="#"><i class="fab fa-odnoklassniki"></i></a></div>
                </div>
            </div>
            <div class="main-slider-b">
                <div class="for-slider1">
                    <div id="slideshow1">
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                        <div><img src="images/slide.png" alt=""></div>
                    </div>
                    <div id="slide-counter1" class="s-counter">
                        из 
                    </div>
                </div>
                <div id="bx-pager">
                    <ul class="d-flex justify-content-between align-items-center flex-wrap">
                        <li> 
                            <a data-slide-index="0" href="">
                                <img src="images/pager1.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="1" href="">
                                <img src="images/pager2.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="2" href="">
                                <img src="images/pager3.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="3" href="">
                                <img src="images/pager4.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="4" href="">
                                <img src="images/pager1.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="5" href="">
                                <img src="images/pager2.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="6" href="">
                                <img src="images/pager3.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="7" href="">
                                <img src="images/pager3.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="8" href="">
                                <img src="images/pager1.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="9" href="">
                                <img src="images/pager2.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="10" href="">
                                <img src="images/pager3.png" alt="">
                            </a>
                        </li>
                        <li> 
                            <a data-slide-index="11" href="">
                                <img src="images/pager3.png" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="about-exc">
                Познавательная экскурсия для детей и их родителей по историческому центру Москвы в компании Кошечки и Зайчика, которые проведут детишек по тайнам кремля и не только по тайнам и не только кремля, воот.
            </div>
            <div class="stickers d-flex">
                <div class="stick st-1 d-flex justify-content-center align-items-center">
                    <img src="images/stick1img.png" alt="">
                    <div class="text">6-8 лет</div>
                </div>
                <div class="stick st-2 d-flex justify-content-center align-items-center">
                    <img src="images/stick2img.png" alt="">
                    <div class="text">45 мин.</div>
                </div>
                <div class="stick st-3 d-flex justify-content-center align-items-center go-to-block" data-target="#schedule" >
                    <img src="images/stick3img.png" alt="">
                    <div class="text">1 200 р.</div>
                </div>
            </div>
        </div>
    </section>
    <section class="feedbacks">
        <div class="container">
            <div class="for-top-t d-flex align-items-center">
                <div class="top-t">Отзывы</div>
                <div class="slides-count-3 slides-count">(7)</div>
            </div>
            <div class="for-slider">
                <div id="slideshow2">
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                <div class="visible-part">
                                     <div class="vis-text">
                                         <div class="vis-inner">
                                             Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв                                      Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                         </div>
                                     </div>
                                     <div class="open-hidden">
                                         <a href="#">читать полностью</a>
                                     </div>
                                     <p class="surname">
                                        Панкратов
                                    </p>
                                    <p class="name">
                                        Александр
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                <div class="visible-part">
                                     <div class="vis-text">
                                         Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     </div>
                                     <div class="open-hidden">
                                         <a href="#">читать полностью</a>
                                     </div>
                                     <p class="surname">
                                        Панкратов
                                    </p>
                                    <p class="name">
                                        Александр
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                <div class="visible-part">
                                     <div class="vis-text">
                                         Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     </div>
                                     <div class="open-hidden">
                                         <a href="#">читать полностью</a>
                                     </div>
                                     <p class="surname">
                                        Панкратов
                                    </p>
                                    <p class="name">
                                        Александр
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                <div class="visible-part">
                                     <div class="vis-text">
                                         Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     </div>
                                     <div class="open-hidden">
                                         <a href="#">читать полностью</a>
                                     </div>
                                     <p class="surname">
                                        Панкратов
                                    </p>
                                    <p class="name">
                                        Александр
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                <div class="visible-part">
                                     <div class="vis-text">
                                         Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     </div>
                                     <div class="open-hidden">
                                         <a href="#">читать полностью</a>
                                     </div>
                                     <p class="surname">
                                        Панкратов
                                    </p>
                                    <p class="name">
                                        Александр
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                <div class="visible-part">
                                     <div class="vis-text">
                                         Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     </div>
                                     <div class="open-hidden">
                                         <a href="#">читать полностью</a>
                                     </div>
                                     <p class="surname">
                                        Панкратов
                                    </p>
                                    <p class="name">
                                        Александр
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="feed-slide">
                        <div class="text">
                            <div class="p-block">
                                <div class="visible-part">
                                     <div class="vis-text">
                                         Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     Текст отзыва человек рассказывает как он хорошо провел время в этой кайфов экскурсии оп оп фывап фыва фыафывфцафыпаф фып ыв
                                     </div>
                                     <div class="open-hidden">
                                         <a href="#">читать полностью</a>
                                     </div>
                                     <p class="surname">
                                        Панкратов 
                                    </p>
                                    <p class="name">
                                        Александр
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="schedule" id="schedule">
        <div class="container">
            <div class="top-t">Расписание</div>
            <div class="for-modal-1">
                <nav>
                <div class="nav nav-tabs monthes d-flex" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Сентябрь</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Октябрь</a>
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Ноябрь</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                  <div class="dates">
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                               <div class="free-places text-center grey">
                                    Свободные <br> места
                                </div>
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                  <div class="dates">
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                               <div class="free-places text-center grey">
                                    Свободные <br> места
                                </div>
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                  <div class="dates">
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                               <div class="free-places text-center grey">
                                    Свободные <br> места
                                </div>
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-sched" tabindex="-1" role="dialog" aria-labelledby="lesson" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="for-modal-1">
                <nav>
                <div class="nav nav-tabs monthes d-flex" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Сентябрь</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Октябрь</a>
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Ноябрь</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                  <div class="dates">
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                               <div class="free-places text-center grey">
                                    Свободные <br> места
                                </div>
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить билет</a></div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                  <div class="dates">
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                               <div class="free-places text-center grey">
                                    Свободные <br> места
                                </div>
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                  <div class="dates">
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                               <div class="free-places text-center grey">
                                    Свободные <br> места
                                </div>
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                    </div>
                    <div class="date d-flex">
                        <div class="day">
                            <div class="day-month">22 сен</div>
                            <div class="day-name">среда</div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">13:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">7</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить билет</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#order">Купить билет</a></div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
    <div class="modal fade" id="modal-pay" tabindex="-1" role="dialog" aria-labelledby="lesson" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="for-modal">
                <form action="" method="post">
                    <div class="choise">
                        <div class="line d-flex justify-content-between align-items-center">
                            <p class="ticket-type">Детский</p>
                            <p class="price">1 200 руб.</p>
                            <p class="amount grey">3 билета</p>
                            <div class="amount-block d-flex">
                                <a href="#" class="div minus d-flex justify-content-center align-items-center">-</a>
                                <input class="current-amount" name="quantity" size="3" value="1">
                                <a href="#" class="div plus d-flex justify-content-center align-items-center">+</a>
                            </div>
                        </div>
                        <div class="line d-flex justify-content-between align-items-center">
                            <p class="ticket-type">Взрослый</p>
                            <p class="price">500 руб.</p>
                            <p class="amount grey">2 билета</p>
                            <div class="amount-block d-flex">
                                <a href="#" class="div minus d-flex justify-content-center align-items-center">-</a>
                                <input class="current-amount" name="quantity" size="3" value="1">
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
                        <div class="order or-1"><a href="#">Перейти к оплате</a></div>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
        </div>
    </body>
</html>
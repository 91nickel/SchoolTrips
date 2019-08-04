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
    <section class="online-study">
        <div class="container">
           <div class="top-t">Online-обучение</div>
            <div class="row s-row">
                <div class="col-12 col-md-6">
                  <div class="exc-card">
                      <div class="text" style="background-image: url(images/exc-3.png);">
                          <div class="top-line d-flex justify-content-between">
                              <p class="big-m">7-10 лет</p>
                          </div>
                          <a class="min-t" href="#"><u>Юный гений</u></a>
                          <div class="description big-m"> Уникальная программа для тех кто только собирается в школу и тех, кто уже учится в школе.</div>
                      </div>
                      <div class="under d-flex justify-content-start align-items-center">
                          <div class="order">
                              <a href="#" data-toggle="modal" data-target="#lesson">Подробнее</a>
                          </div>
                          <p class="price">от 990 <span>₽</span></p>
                      </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="exc-card">
                      <div class="text" style="background-image: url(images/exc-3.png);">
                          <div class="top-line d-flex justify-content-between">
                              <p class="big-m">7-10 лет</p>
                          </div>
                          <a class="min-t" href="#"><u>Юный гений</u></a>
                          <div class="description big-m"> Уникальная программа для тех кто только собирается в школу и тех, кто уже учится в школе.</div>
                      </div>
                      <div class="under d-flex justify-content-start align-items-center">
                          <div class="order">
                              <a href="#" data-toggle="modal" data-target="#lesson">Подробнее</a>
                          </div>
                          <p class="price">от 990 <span>₽</span></p>
                      </div>
                  </div>
                </div>
              </div>
        </div>
    </section>
    <div class="modal fade" id="lesson" tabindex="-1" role="dialog" aria-labelledby="lesson" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="main-b">
                <div class="top-t">Юный гений</div>
                <div class="text">
                    «Юный гений» - летний курс по подготовке к школе - это уникальная программа для тех кто только собирается в школу и тех, кто уже учится в школе и сейчас наслаждается каникулами.
                </div>
                <div class="text">
                    Не спешите огорчаться: эта методика довольна легка и не навязчива, что позволяет детям непринужденно влиться в режим и уже самим ждать каждый день нового он-лайн урока!
                </div>
                <div class="text">
                    Курс «юный гений» представляет из себя занятия по арифметике, развитию речи, окружающему миру - это крутой симбиоз интерактивных занятий с логическими заданиями и конечно же классными призами!
                </div>
                <div class="ul-b ub-1">
                   Что такое курс «Юный гений»:
                    <ul>
                        <li>Арифметика</li>
                        <li>Развитие речи</li>
                        <li>Окружающий мир</li>
                        <li>Занятие по тренировке памяти</li>
                        <li>2 месяца</li>
                        <li>40 занятий по 20 занятий в месяц</li>
                        <li>20 - 30 минут/занятие</li>
                        <li>Ежедневные домашние задания</li>
                        <li>Чаты в Whatsupp</li>
                        <li>Закрытый чат в инстаграмм</li>
                        <li>У каждой группы свой куратор</li>
                        <li>Дополнительные интересные учебные обзоры и он-лайн экскурсии (в закрытом чате)</li>
                        <li>Еженедельные методички-игралки для доп. занятий с родителями</li>
                        <li>Чек-листы по каждому предмету на предстоящий учебный год для лучшего результата</li>
                    </ul>
                </div>
                <div class="ul-b ub-2">
                   Для кого данный курс:
                    <ul>
                        <li>для детей 5-7 лет</li>
                        <li>для тех, кто окончил 1, 2, 3 классы</li>
                    </ul>
                </div>
                <div class="text">
                    У каждой возрастной группы свой курс согласно возрасту! Стоимость 4 000 р./месяц.
                </div>
                <div class="text">
                    Для тех, кто особенно заинтересован в результате и будет заниматься 2 месяца с нами - спец предложение - курс «Третьяковка глазами ребёнка» в подарок
                </div>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="less-p">
                            <p class="big">4 урока</p>
                            <p class="price">990 руб.</p>
                            <div class="order">
                                <a href="#">Купить</a>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="less-p">
                            <p class="big">1 месяц</p>
                            <p class="price">4 990 руб.</p>
                            <div class="order">
                                <a href="#">Купить</a>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="less-p">
                            <p class="big">2 месяца</p>
                            <p class="price">8 000 руб.</p>
                            <div class="order">
                                <a href="#">Купить</a>                                
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
      </div>
	</body>
</html>
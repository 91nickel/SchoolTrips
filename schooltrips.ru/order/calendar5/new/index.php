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
   <section class="main">
        <div class="container">
          <div class="top-t d-none d-md-block">Нескучные экскурсии <span>в Москве</span></div>
          <div class="top-t d-block d-md-none">Нескучные <br> экскурсии</div>
          <div class="details d-flex align-items-center">
              <div class="location">
                  <p class="big g-1">Локация</p>
                  <div class="select">
                      <select name="location" id="loc">
                          <option value="1">Красная площадь</option>
                          <option value="2" selected>Музей востока</option>
                          <option value="3">Пушкинский музей</option>
                          <option value="4">Палеонтологический музей</option>
                      </select>
                  </div>
              </div>
              <div class="age">
                  <p class="big g-1">Возраст</p>
                  <div class="select">
                      <select name="age" id="age">
                          <option value="1">6-8 лет</option>
                      </select>
                  </div>
              </div>
              <div class="date">
                <p class="big">Дата</p>
                <div class="input-block">
                    <input type="text" id="datepicker">
                    <div class="tod-tom d-flex align-items-center">
                        <a href="#" class="d-tod big">Сегодня,</a>
                        <a href="#" class="d-tom big">завтра</a>
                    </div>
                </div>
              </div>
          </div>
          <div class="row s-row">
            <div class="col-12 col-md-6">
              <div class="exc-card" style="background-image: url(images/exc-1.jpg);" >
                  <div class="text">
                      <div class="top-line d-flex justify-content-between">
                          <p class="big-m"><u class="dotted"><a href="#">Палеонтолог. музей</a> </u>  <u class="age dotted" ><a href="#" >7-10 </a>лет</u></p>
                          <p class="exc-price-t">1 500 <span>₽</span></p>
                      </div>
                      <a class="min-t" href="single.php"><u>Тайны древнего кремля</u></a>
                      <div class="description big-m"> Яркое путешествие по территории сердца Москвы - Кремля.</div>

                  </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="exc-card" style="background-image: url(images/exc-2.jpg);" >
                  <div class="text">
                      <div class="top-line d-flex justify-content-between">
                          <p class="big-m"><u class="dotted"><a href="#">Музей востока</a> </u>   <u class="age dotted"><a href="#" >< 4 лет </a></u></p>
                          <p class="exc-price-t">1 500 <span>₽</span></p>
                      </div>
                      <a class="min-t" href="single.php"><u>Восточные сказки волшебной лампы</u></a>
                      <div class="description big-m"> Яркое путешествие по территории сердца Москвы - Кремля.</div>

                  </div>
              </div>
            </div>
          </div>
          <div class="modals d-flex flex-column justify-content-between align-items-center">
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-sched">Модалка расписания</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-info">Модалка с куратором</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-pay">Модалка оплаты</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-sign-in">Модалка входа</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#lesson">Модалка Обучение</a></div>
            <div class="order mt-5"><a href="contact-form.php">Сбор контактов</a></div>
            <div class="order mt-5"><a href="order-form.php">Форма заказа</a></div>
         </div>
        </div> 
        </section>
      <?php include 'footer.php'; ?>
  </div>
	</body>

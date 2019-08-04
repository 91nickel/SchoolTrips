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
<body class="ind-n">
  <?php include 'modals.php'; ?>
  <div class="wrapper">
  <div class="top-info-b">
      <div class="container">
          <div class="info-line d-flex justify-content-between">
             
              <div class="left-b">
                 <div class="header-logo d-md-none">
                      <a href="./"><img src="images/header-logo.png" alt=""></a>
                  </div>
                  <p>Экскурсии для детей от 3-9 <br class="d-none d-md-inline"> лет по Москве и Московской обл.</p>
              </div>
              <div class="right-b d-flex">
                  <div class="info i-1">
                      <a href="tel:8 (800) 100-10-01" class="tel">8 (800) <span>100-10-01</span> <span class="pal">|</span></a>
                      <p>Бесплатно по всей РФ</p>
                  </div>
                  <div class="info i-2">
                      <a href="tel:+7 (495) 100-10-01" class="tel">+7 (495) <span>100-10-01</span></a>
                      <p>Бесплатно по Мск и области</p>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="menu-backdrop"></div>
   <!--***БОКОВОЕ МОБ МЕНЮ***-->
    <div class="n-manage">
      <div class="humb">
          <div></div>
          <div></div>
          <div></div>
      </div>
      <a href="#">Выбрать экскурсию</a>
      <div class="login d-flex align-items-center">
            <div class="img">
                <a class="big" href="#" data-toggle="modal" data-target="#modal-sign-in"><img src="images/n-login.png" alt=""></a>
            </div>
            
        </div>
      </div>
      <div class="left-menu">
         <div class="close-menu">&times;</div>
          <div class="for-menu">
              <div class="mob-title">
                  МЕНЮ
              </div>
              <div class="links">
                  
              </div>
          </div>
      </div>
      <!--//боковое моб. меню-->
      
    <header class="header-n">
        <div class="container">
            <div class="head-b top-line d-flex">
              <div class="header-logo">
                  <a href="./"><img src="images/header-logo.png" alt=""></a>
              </div>
                <div class="top-menu links d-flex justify-content-start">
                    <div><a href="./" class="active">Выбрать экскурсию</a></div>
                    <div><a href="abonements.php">Приехать группой</a></div>
                </div>
                <div class="login d-flex">
                    <div class="img">
                        <img src="images/n-login.png" alt="">
                    </div>
                    <a class="big" href="#" data-toggle="modal" data-target="#modal-sign-in"><u>Войти</u></a>
                </div>
            </div>
        </div>
    </header>
   <section class="n-main">
        <div class="container">
          <div class="n-details">
             <div class="min-tt">Подобрать увлекательную интерактивную экскурсию</div>
              <div class="age">
                  <p class="g-1">Возраст</p>
                  <div class="select">
                      <select name="age" id="age">
                          <option value="1">6-8 лет</option>
                      </select>
                  </div>
              </div>
              <div class="n-date">
                <p class="g-1">Дата</p>
                <div class="input-block">
                    <input type="text" id="datepicker" placeholder="Ближайшие даты">
                </div>
              </div>
              <div class="order"><a href="#">Подобрать</a></div>
          </div>
        </div> 
    </section>
    <section class="n-cards">
        <div class="container">
           <div class="title">Самое крутое в декабре</div>
            <div class="row s-row">
            <div class="col-12 col-md-6">
              <div class="exc-card" style="background-image: url(images/exc-1.jpg);" >
                  <div class="text">
                      <div class="top-line d-flex justify-content-between">
                          <p class="big-m"> <u class="age dotted" ><a href="#" >7-10 </a>лет</u></p>
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
                          <p class="big-m">   <u class="age dotted"><a href="#" >< 4 лет </a></u></p>
                          <p class="exc-price-t">1 500 <span>₽</span></p>
                      </div>
                      <a class="min-t" href="single.php"><u>Восточные сказки волшебной лампы</u></a>
                      <div class="description big-m"> Яркое путешествие по территории сердца Москвы - Кремля.</div>

                  </div>
              </div>
            </div>
          </div>
        </div>
    </section>
    <section class="owners">
        <div class="container">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="text-block">
                    <p class="l-text">
                        Основатели компании, родители 5х детей и создатели авторских экскурсий School Trips
                    </p>
                    <p class="b-text">
                        Оставляем только то, <br class="d-none d-xl-inline"> что ребенку действительно интересно и пропускаем то, что будет скучно
                    </p>
                </div>
                <div class="img d-md-none">
                    <img src="images/mob-owners.png" alt="">
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
  </div>
	</body>

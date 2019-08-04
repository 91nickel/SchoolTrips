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
        <section class="catalogue">
            <div class="container">
               <div class="top-t">Программы для детей</div>
                <div class="row s-row">
                <div class="col-12 col-md-6">
                  <div class="exc-card" style="background-image: url(images/exc-1.jpg);" >
                      <div class="text">
                          <div class="top-line d-flex justify-content-between">
                              <p class="big-m">6-8 лет</p>
                              <p class="exc-price-t">19 000 <span>₽</span></p>
                          </div>
                            <div class="for-min d-flex justify-content-between">
                              <a class="min-t" href="#"><u>Третьяковка<br> малышам</u></a>
                              <div class="amount">
                                  16
                                  <p class="big-m">экскурсий</p>
                              </div>
                            </div>
                          <div class="description big-m">с 01.09.18 - 30.04.19  </div>
                      </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="exc-card" style="background-image: url(images/exc-1.jpg);" >
                      <div class="text">
                          <div class="top-line d-flex justify-content-between">
                              <p class="big-m">6-8 лет</p>
                              <p class="exc-price-t">19 000 <span>₽</span></p>
                          </div>
                            <div class="for-min d-flex justify-content-between">
                              <a class="min-t" href="#"><u>Третьяковка<br> малышам</u></a>
                              <div class="amount">
                                  16
                                  <p class="big-m">экскурсий</p>
                              </div>
                            </div>
                          <div class="description big-m">с 01.09.18 - 30.04.19  </div>
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
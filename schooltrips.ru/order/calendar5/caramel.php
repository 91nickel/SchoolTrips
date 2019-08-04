<?
include 'utm.php';

ini_set('display_errors', 'Off');

$els = [];

$folders = myscandir('exc');

foreach ($folders as $key => $value) {
    $els[] = ["n" => $value, "photo" => "https://schooltrips.ru/order/calendar5/exc/" . $value . '/main_photo.jpg', "desc" => file_get_contents('exc/' . $value . '/desc.txt')];
}

//$els[] = ["n"=>$arr[0],"photo"=>"https://dmitrybondar.ru/auto/schooltrip/photo/".$arr[1].'/'.$photo,"desc"=>$arr[2]];

function myscandir($dir, $sort = 0)
{
    $list = scandir($dir, $sort);

    // если директории не существует
    if (!$list) return false;

    // удаляем . и .. (я думаю редко кто использует)
    if ($sort == 0) unset($list[0], $list[1]);
    else unset($list[count($list) - 1], $list[count($list) - 1]);
    return $list;
}

?>
<!DOCTYPE html>

<html>
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <base href="/order/calendar5/">
        <link rel="stylesheet" href="files_html/plugins/bxslider/jquery.bxslider.css">
        <link rel="stylesheet" href="files_html/plugins/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="files_html/plugins/fancybox/jquery.fancybox.min.css">
        <link rel="stylesheet" href="files_html/plugins/slider/settings.css">
        <link rel="stylesheet" href="files_html/plugins/slider/navstylechange.css">
        <link rel="stylesheet" href="files_html/css/fontawesome-all.css">
        <link rel="stylesheet" href="style.css">
        
        <script src="files_html/plugins/jquery/jquery-3.2.0.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
       <script src="files_html/plugins/bxslider/jquery.bxslider.min.js"></script>
       <script src="files_html/plugins/bootstrap/js/popper.min.js"></script>
       <script src="files_html/plugins/bootstrap/js/bootstrap.min.js"></script>
       <script src="files_html/plugins/fancybox/jquery.fancybox.min.js"></script>
       <script src="files_html/plugins/jqueryvalidate/jquery.validate.min.js"></script>
       <script src="files_html/plugins/slider/jquery.themepunch.plugins.min.js"></script>
       <script src="files_html/plugins/slider/jquery.themepunch.revolution.min.js"></script>
       <script src="files_html/plugins/inputmask/jquery.inputmask.bundle.js"></script>
       <script src="files_html/plugins/datepicker/jquery-ui.min.js"></script>
       <script src="files_html/plugins/datepicker/datepicker-ru.js"></script>
       <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
       <script src="js/my.js"></script>
       <script src="script.js"></script>
        
    </head>
    <body class="caramel">
<div class="js-json-exc" style="display:none"><?= json_encode($els) ?></div>
  <div class="modal fade" id="modal-sign-in" tabindex="-1" role="dialog" aria-labelledby="modal-sign-in" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <div class="main-block text-center">
                <div class="top-t">Войдите в личный кабинет</div>
                <div class="text">Чтобы посмотреть расписание ваших экскурсий, список неоплаченных мероприятий и многое другое</div>
                <div class="form-block">
                    <form action="" method="post">
                        <label for="phone">Введите телефон</label>
                           <div class="in-block">
                            <input type="text" class="phone" name="phone" placeholder="+7 (999) 999 - 99 - 99" required>
                        </div>
                        <div class="order">
                            <a href="my-schedule.php">Войти</a>                        
                        </div>
                    </form>
                    <p class="red">Номер не найден. Используйте тот, что использовали при заказе.</p>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
                    <img src="files_html/images/qr-code.png" alt="">
                </div>
          </div>
        </div>
      </div>
    </div>
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
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить</a></div>
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
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить</a></div>
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
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#modal-pay">Купить</a></div>
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
                            <div class="order"><a href="#">Купить</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить</a></div>
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
                            <div class="order"><a href="#">Купить</a></div>
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
                            <div class="order"><a href="#">Купить</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить</a></div>
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
                            <div class="order"><a href="#">Купить</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#">Купить</a></div>
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
                            <div class="order"><a href="#">Купить</a></div>
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
                            <div class="order"><a href="#">Купить</a></div>
                        </div>
                        <div class="line d-flex">
                            <div class="time big">19:00</div>
                            <div class="location">м. Охотный ряд, Манежная площадь д. 1</div>
                            <div class="places d-flex">
                                <div class="free big">3</div>
                                <div class="symb big">/</div>
                                <div class="amount big">12</div>
                            </div>
                            <div class="order"><a href="#" data-toggle="modal" data-target="#order">Купить</a></div>
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
                        <div class="order or-1"><a href="my-schedule.php">Выбрать</a></div>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>  <div class="wrapper">
  <div class="menu-backdrop"></div>
   <!--***БОКОВОЕ МОБ МЕНЮ***-->
    <div class="manage">
      <div class="humb">
          <div></div>
          <div></div>
          <div></div>
      </div>
      <div class="login d-flex align-items-center">
            <div class="img">
                <img src="files_html/images/user-login.png" alt="">
            </div>
            <a class="big" href="#" data-toggle="modal" data-target="#modal-sign-in">Войти</a>
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
      
    <header class="header">
        <div class="container">
            <div class="head-b top-line d-flex align-items-center justify-content-between">
              <div class="header-logo">
                  <a href="/list"><img src="files_html/images/header-logo.png" alt=""></a>
              </div>
                <div class="top-menu links d-flex justify-content-between">
                    <div><a href="/list">Экскурсии</a></div>
                    <div><a href="/event/pozharnye_v_gorode" class="active">Пожарные в городе</a></div>
                    <!--<div><a href="abonements.php">Абонементы</a></div>
                    <div><a href="online-learn.php">Обучение</a></div>
                    <div><a href="programs.php">Программы</a></div>-->
                </div>
                <!--
                <div class="login d-flex align-items-center">
                    <div class="img">
                        <img src="files_html/images/user-login.png" alt="">
                    </div>
                    <a class="big" href="#" data-toggle="modal" data-target="#modal-sign-in">Войти</a>
                </div>-->
            </div>
        </div>
    </header>

        <section class="main">
        <div class="container">
          <div class="top-t d-none d-md-block">Нескучные экскурсии <span>в Москве</span></div>
          <div class="top-t d-block d-md-none">Нескучные <br> экскурсии</div>
          <div class="details d-flex align-items-center">
              <div class="location">
                  <p class="big g-1">Локация</p>
                  <div class="select">
                      <select name="location" id="loc"></select>
                  </div>
              </div>
              <!--<div class="age">
                  <p class="big g-1">Возраст</p>
                  <div class="select">
                      <select name="age" id="age"></select>
                  </div>
              </div>-->
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
          <div class="row s-row list_v"></div>
          <!--
          <div class="modals d-flex flex-column justify-content-between align-items-center">
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-sched">Модалка расписания</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-info">Модалка с куратором</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-pay">Модалка оплаты</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#modal-sign-in">Модалка входа</a></div>
            <div class="order mt-5"><a href="#" data-toggle="modal" data-target="#lesson">Модалка Обучение</a></div>
            <div class="order mt-5"><a href="contact-form.php">Сбор контактов</a></div>
            <div class="order mt-5"><a href="order-form.php">Форма заказа</a></div>
         </div>-->
        </div> 
        </section>
  </div>
  <? include "footer.php"; ?>
    </body>

    <style>
        .list_v > *{
            margin-bottom: 40px;
        }
    </style>
</html>
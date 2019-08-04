<?
ini_set('display_errors', 'Off');

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


include 'utm.php';

require_once "../../date/amocrm_lite.php";

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
auth_amoCRM($amo);
$leadsids = array(array('id'=>$_GET['id']));
foreach ($leadsids as $leadid){
    $id = $leadid['id'];
    $leads = m($amo,'/api/v2/leads?id='.$id);
    $leads = $leads['_embedded']['items'];
    foreach ($leads as $lead) {
        foreach ($lead['custom_fields'] as $key2 => $value2) {
            if($value2['name']=='Название экскурсии')
                $n = $value2['values'][0]['value'];
            if($value2['name']=='Количество детей')
                $k = $value2['values'][0]['value'];
            if($value2['name']=='Взрсл. за деньги')
                $a = $value2['values'][0]['value'];
            if($value2['name']=='Дата экскурсии')
                $d = $value2['values'][0]['value'];
            if($value2['name']=='Время начала экскурсии')
                $t = $value2['values'][0]['value'];
            if($value2['name']=='Ссылка на фото')
                $u = $value2['values'][0]['value'];
        }
	}
}

$photos = json_decode(file_get_contents('https://cloud-api.yandex.net/v1/disk/public/resources?public_key='.$u.'&limit=999&preview_size=780x500&preview_crop=true'),1);
?>
<!DOCTYPE html>

<html>
<head>
	<title>Фотографии с экскурсии</title>
	<base href="/order/calendar5/">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
	      integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<script src="js/my.js"></script>
	<script src="script.js"></script>

	<? include 'head.php' ?>

</head>
<body class="photo">
<div class="modal fade" id="modal-sign-in" tabindex="-1" role="dialog" aria-labelledby="modal-sign-in"
     aria-hidden="true">
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
					<div class="text">Чтобы посмотреть расписание ваших экскурсий, список неоплаченных мероприятий и
						многое другое
					</div>
					<div class="form-block">
						<form action="" method="post">
							<label for="phone">Введите телефон</label>
							<div class="in-block">
								<input type="text" class="phone" name="phone" placeholder="+7 (999) 999 - 99 - 99"
								       required>
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
<div class="modal fade" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="modal-contact"
     aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" method="post" class="contacts-form">
					<div class="input-block ib-1">
						<label for="name">Введите имя</label>
						<div class="in-1">
							<input type="text" name="name" placeholder="Иван" required=""
							       style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="name">Введите e-mail</label>
						<div class="in-2">
							<input type="text" name="email" placeholder="ivanov@ivan.ru" required="">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="phone">Введите телефон</label>
						<div class="in-3">
							<input type="text" class="phone" name="phone" placeholder="+7 (999) 999 - 99 - 99"
							       required="">
						</div>
					</div>
					<div class="total">
						<p class="sum">Итого:</p>
						<p class="price">1 700 руб.</p>
					</div>
					<div class="input-block ib-1">
						<span class="in-3">
							<input style="width: auto" type="checkbox" checked name="sendpulse">
						</span>
						<label for="phone">Согласен(-на) на новостную рассылку</label>
					</div>
					<div class="input-block ib-1">
						<span class="in-3">
							<input style="width: auto" type="checkbox" name="offert">
						</span>
						<label for="phone">Cогласен с <a target="_blank" href="#" data-toggle="modal" data-target="#modal-offert">условиями покупки</a></label>
					</div>
					<div class="pay-methods d-flex align-items-center">
						<div class="order or-1"><a href="#" class="js-pay">Перейти к оплате</a></div>
					</div>
					<p class="after-p">
						После оплаты мы вышлем СМС с информацией о месте, времени проведения экскурсии и контакты
						куратора
					</p>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-contact2" tabindex="-1" role="dialog" aria-labelledby="modal-contact2"
     aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" method="post" class="contacts-form" style="padding-bottom: 0">
					<div class="input-block ib-1">
						<label for="name">Введите имя</label>
						<div class="in-1">
							<input type="text" name="name" placeholder="Иван" required=""
							       style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="name">Введите e-mail</label>
						<div class="in-2">
							<input type="text" name="email" placeholder="ivanov@ivan.ru" required="">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="phone">Введите телефон</label>
						<div class="in-3">
							<input type="text" class="phone" name="phone" placeholder="+7 (999) 999 - 99 - 99"
							       required="">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="phone">Согласен(-на) на новостную рассылку</label>
						<div class="in-3">
							<input type="checkbox" checked name="sendpulse">
						</div>
					</div>
					<div class="pay-methods d-flex align-items-center">
						<div class="order or-1"><a href="#" class="js-notice">Отправить</a></div>
					</div>
					<p class="after-p">
						Мы свяжемся с вами, как тоько экскурсия будет запланирована
					</p>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-contact3" tabindex="-1" role="dialog" aria-labelledby="modal-contact3"
     aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" method="post" class="contacts-form" style="padding-bottom: 0">
					<div class="input-block ib-1">
						<label for="name">Введите имя</label>
						<div class="in-1">
							<input type="text" name="name" placeholder="Иван" required=""
							       style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="name">Введите e-mail</label>
						<div class="in-2">
							<input type="text" name="email" placeholder="ivanov@ivan.ru" required="">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="phone">Введите телефон</label>
						<div class="in-3">
							<input type="text" class="phone" name="phone" placeholder="+7 (999) 999 - 99 - 99"
							       required="">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="phone">Введите кол-во детей</label>
						<div class="in-3">
							<input type="text" class="children" name="children" value="5">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="phone">Введите кол-во взрослых</label>
						<div class="in-3">
							<input type="text" class="adult" name="adult" value="1">
						</div>
					</div>
					<div class="input-block ib-1">
						<label for="phone">Согласен(-на) на новостную рассылку</label>
						<div class="in-3">
							<input type="checkbox" checked name="sendpulse">
						</div>
					</div>
					<div class="pay-methods d-flex align-items-center">
						<div class="order or-1"><a href="#" class="js-group">Отправить</a></div>
					</div>
					<p class="after-p"></p>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-offert" tabindex="-1" role="dialog" aria-labelledby="modal-sign-in"
     aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="main-block text-center">
					<div class="top-t">Публичная оферта</div>
					<p style="text-align: left">
			            <b>Условия покупки билетов на экскурсии и мероприятия</b>
						<br>1.	Покупка билета на мероприятие даёт право посещения указанного мероприятия для количества участников, указанного в билете или договоре;
						<br>2.	Билет на мероприятие предоставляется в электронном виде, доступ для просмотра будет организован через ссылку, которая будет направлена вам в SMS-сообщении, либо через личный кабинет клиента на сайте schooltrips.ru. В случае возникновения проблем с отображением билета, вам необходимо связаться с отделом продаж School Trip любым удобным способом;
						<br>3.	Билет не требуется распечатывать. Для доступа на мероприятие необходимо показать ваш билет куратору программы в электронном виде;
						<br>4.	Билет на мероприятие не является именным и может быть передан третьим лицам;
						<br>5.	Билет реализовывается полностью в дату и время мероприятия. В случае полной или частичной неявки участников, услуга считается полностью оказанной, возврат средств не производится, возможность переноса мероприятия утрачивается;
						<br>6.	В случае если по каким-либо причинам клиенту необходимо перенести дату или время мероприятия, либо изменить программу, необходимо отказаться от посещения мероприятия, а затем записаться на мероприятие заново;
						<br>7.	За 12 часов до начала программы, вам будет отправлено SMS-сообщение с указанием адреса места встречи и контактного телефона куратора мероприятия. В случае отсутствия уведомления, вам необходимо связаться с отделом продаж School Trip любым удобным способом;
						<br>8.	За 2 часа до начала программы с Вами свяжется куратор мероприятия для подтверждения записи на мероприятие.
						<br>9.	При оплате вы соглашаетесь со всеми условиями, перечисленными на данной странице.
						<br><br><b>Правила отмены и возврата билетов:</b>
						<br>1.	В случае если отмена мероприятия происходит по инициативе организатора, возможно сохранение оплаченной суммы на депозите клиента, либо возврат оплаченной суммы клиенту;
						<br>2.	Для возврата денежных средств, клиенту необходимо направить на электронную почту организатора скан-копию заявления по установленной форме;
						<br>3.	Срок рассмотрения заявления на возврат денежных средств – 2 (две) недели с момента получения заявления на электронную почту;
						<br>4.	В случае если отмена посещения мероприятия происходит по инициативе клиента, возврат денежных средств, за оплаченное мероприятие не производится;
						<br>5.	Денежные средства могут быть сохранены на депозите клиента. Для этого клиенту необходимо заранее уведомить организатора мероприятия об отказе от посещения;
						<br>6.	При уведомлении организатора об отказе от посещения мероприятия за 2 (два) или более календарных дня до начала программы, возможно сохранение до 100% оплаченной суммы на депозите клиента;
						<br>7.	При уведомлении организатора об отказе от посещения мероприятия за не менее, чем 1 (один) календарный день до начала программы, возможно сохранение до 50% оплаченной суммы на депозите клиента;
						<br>8.	При уведомлении организатора об отказе от посещения в день мероприятия, услуга считается полностью оказанной, денежные средства на депозите не сохраняются;
						<br>9.	Депозит является бессрочным и может быть израсходован на покупку любых продуктов компании School Trip.
						<br><br><b>Условия покупки абонементов</b>
						<br>1.	В наличие абонементы следующих типов: 3-х месячный (3 экскурсии) и 6-ти месячный (6 экскурсий + 1 в подарок на выбор).   
						<br>2.	3-х месячный абонемент включает в себя 3 экскурсии на любую музейную, парковую или пешеходную экскурсию. В программу абонементов не входят однодневные и многодневные выездные мероприятия! Экскурсионные билеты для взрослых приобретаются дополнительно! 
						<br>3.	6-ти месячный абонемент включает в себя 6 экскурсий + 1 экскурсия в подарок на выбор на любую музейную, парковую или пешеходную экскурсию. В программу абонементов не входят однодневными и многодневные выездные мероприятия! Экскурсионные билеты для взрослых приобретаются дополнительно! 
						<br>4.	12-ти месячный абонемент включает в себя 11 экскурсий + 1 экскурсия в подарок на выбор на любую музейную, парковую или пешеходную экскурсию. В программу абонементов не входят однодневными и многодневные выездные мероприятия! Экскурсионные билеты для взрослых приобретаются дополнительно! 
						<br>5.	Срок действия абонемента:  🔸3-х месячный: с 10.01.2019 г. по 10.05.2019 г.  🔸6-ти месячный: с 10.01.2019 г. по 10.07.2019 г.   🔸12-ти месячный: с 10.01.2019 г. по 10.01.2020 г.  
						<br>6.	Абонементы не являются именными, поэтому могут быть переданы 3-им лицам для использования!  
						<br>7.	Абонемент не ограничивает количество используемых экскурсий в месяц. Обладатель абонемента имеет право посетить любое количество (в рамках приобретённого абонемента) экскурсий в месяц согласно расписанию для сборных групп!  
						<br>8.	Абонементом могут воспользоваться сразу несколько детей, в этом случае количество используемых экскурсий рассчитывается исходя из количества детей и на каждого ребёнка приходится 1 (одна) экскурсия!  
						<br>9.	Абонемент позволяет отменить или перенести экскурсию не позднее чем за 1 (один) день до предполагаемой даты экскурсии! Днём считается время с 10:00 до 18:00, в которое необходимо сообщить о невозможности посещения экскурсии любым удобным способом: тел. 8 (499) 938 47 00 или в личном кабинете.  
						<br>10.	При сообщении о невозможности посещения экскурсии по абонементу в более поздние сроки, чем указано в п. 9 - экскурсия сгорает!  
						<br>11.	При оплате вы соглашаетесь со всеми условиями, перечисленными на данной странице.  
						<br><br><b>Правила отказа от абонемента</b>
						<br>1.	При отказе от оплаченного абонемента, проект вправе удержать комиссию в размере - 1500 руб., при этом использованные экскурсии пересчитываются по стоимости для сборных групп на момент отказа от абонемента;
						<br>2.	При отказе от абонемента по инициативе клиента, возврат оплаченной суммы не производится;
						<br>3.	Пересчитанная сумма сохраняется на депозите клиента на неограниченный срок;
						<br>4.	Депозит может быть израсходован на оплату любых продуктов компании;
			          </p>
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
						«Юный гений» - летний курс по подготовке к школе - это уникальная программа для тех кто только
						собирается в школу и тех, кто уже учится в школе и сейчас наслаждается каникулами.
					</div>
					<div class="text">
						Не спешите огорчаться: эта методика довольна легка и не навязчива, что позволяет детям
						непринужденно влиться в режим и уже самим ждать каждый день нового он-лайн урока!
					</div>
					<div class="text">
						Курс «юный гений» представляет из себя занятия по арифметике, развитию речи, окружающему миру -
						это крутой симбиоз интерактивных занятий с логическими заданиями и конечно же классными призами!
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
						Для тех, кто особенно заинтересован в результате и будет заниматься 2 месяца с нами - спец
						предложение - курс «Третьяковка глазами ребёнка» в подарок
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
					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-home" role="tabpanel"
						     aria-labelledby="nav-home-tab">
						    <select name="old" style="margin-top: -20px;"></select>
							<div class="dates"></div>
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
								<p class="price p1">1 200 руб.</p>
								<p class="amount grey"></p>
								<div class="amount-block d-flex">
									<a href="#" class="div minus d-flex justify-content-center align-items-center">-</a>
									<input class="current-amount a1" name="quantity" size="3" value="0">
									<a href="#" class="div plus d-flex justify-content-center align-items-center">+</a>
								</div>
							</div>
							<div class="line d-flex justify-content-between align-items-center">
								<p class="ticket-type">Взрослый</p>
								<p class="price p2">500 руб.</p>
								<p class="amount grey"></p>
								<div class="amount-block d-flex">
									<a href="#" class="div minus d-flex justify-content-center align-items-center">-</a>
									<input class="current-amount a2" name="quantity" size="3" value="0">
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
							<div class="order or-1" style="margin-right: 10px;"><a href="#" data-toggle="modal"
							                                                       data-dismiss="modal"
							                                                       data-target="#modal-contact">Выбрать</a>
							</div>
							<div class="order or-1"><a href="my-schedule.php" class="promogo">Применить промокод</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="wrapper">
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

	<header class="header">
		<div class="container">
			<div class="head-b top-line d-flex align-items-center justify-content-between">
				<div class="header-logo">
					<a href="/list"><img src="files_html/images/header-logo.png" alt=""></a>
				</div>
				<div class="top-menu links d-flex justify-content-between">
					<div><a href="/list">Экскурсии</a></div>
					<div><a href="/event/pozharnye_v_gorode">Пожарные в городе</a></div>
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
				</div>
				-->
			</div>
		</div>
	</header>

	<section class="excursion-info">
		<div class="container">
			<div class="info-block">
				<div class="name-age d-flex">
					<p class="grey exc-name"><?=$n?></p>
				</div>
				<br>
				<div class="name-age d-flex">
					<p class="grey exc-name"><?=date('d.m.Y',strtotime($d))?> <?=$t?></p>
				</div>
				<!--
					<div class="order or-1" style="
    display: inline-block;
    margin-right: 10px;
    top: 0;
    width: 50px;
    position: relative;
">
					<a href="/list" data-toggle="modal" data-target="#modal-sched" style="
    padding: 0 15px;
    height: 40px;
    margin-top: -30px;
    display: block;
    line-height: 30px;
    position: absolute;
    top: 0;
">&lt;</a>
				</div>-->
				<div class="top-t js-name">Сказки о красной<br class="d-none d-xl-inline"> прекрасной площади</div>
				<div class="location-u d-flex align-items-center">
					<!--<div class="img"><img src="files_html/images/loc-img.png" alt=""></div>
					<p>м. Охотный ряд, Манежная площадь д. 1</p>
					<a href="#" class="small">посмотреть на карте</a>-->
				</div>
			</div>
			<div class="about-exc">
				Разместите фото в instagram c хештегом <b>#schooltrip</b> и получите возможность выиграть бесплатную экскурсию! Розыгрыш в нашем профиле каждую неделю.
			</div>
			<div class="order-block d-flex align-items-center">
				<div class="order or-1">
					<a href="<?=json_decode(file_get_contents('https://cloud-api.yandex.net/v1/disk/public/resources/download?public_key='.$u),1)['href']?>" target="_blank">Скачать все</a>
				</div>
				<!--
				<div class="or-2">
					<u><a href="#">Записаться<br class="d-none d-xl-inline"> группой</a></u>
				</div>-->
			</div>
			<div class="order-block d-flex align-items-center">
				<span style="font-size: 20px;">Поделиться:</span>
				<br>
				<div class="socials d-flex" style="margin: 0; margin-left: 20px;">
					<div style="background-color: #00599F;"><a target="_blank" href="http://www.facebook.com/sharer.php?s=100&p[url]=<?="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]?>&p[title]=Фото с нашей экскурсии со schooltrips&p[images][0]=<?=$photos['_embedded']['items'][0]['file']?>"><i class="fab fa-facebook-f"></i></a></div>
					<div style="background-color: #4ECA5B;"><a target="_blank" href="whatsapp://send?text=Фото с нашей экскурсии со schooltrips <?="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]?>"><i class="fab fa-whatsapp"></i></a></div>
					<div style="background-color: #00668F;"><a target="_blank" href="https://vk.com/share.php?url=<?="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]?>&title=Фото с нашей экскурсии со schooltrips&image=<?=$photos['_embedded']['items'][0]['file']?>"><i class="fab fa-vk"></i></a></div>
					<div style="background-color: #FF9400;"><a target="_blank" href="https://connect.ok.ru/offer?url=<?="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]?>&title=Фото с нашей экскурсии со schooltrips&imageUrl=<?=$photos['_embedded']['items'][0]['file']?>"><i class="fab fa-odnoklassniki"></i></a></div>
				</div>
			</div>
			<div class="main-slider-b">
				<div class="for-slider1">
					<div id="slideshow1">
						<?foreach ($photos['_embedded']['items'] as $key => $value) {?>
							<div style="float: left; list-style: none; position: relative; width: 780px;">
								<div class="order or-1">
									<a target="_blank" href="<?=$value['file']?>" style="position: absolute;z-index: 100;font-size: 16px;padding: 5px;margin-top: 10px;margin-left: 10px;">Скачать</a>
								</div>
								<img src="<?=$value['preview']?>" alt="">
							</div>
						<?}?>
					</div>
					<div id="slide-counter1" class="s-counter">
						из
					</div>
				</div>
				<div id="bx-pager">
					<ul class="d-flex justify-content-between align-items-center flex-wrap">
						<?
						foreach ($photos['_embedded']['items'] as $key => $value) {?>
							<li>
								<a data-slide-index="<?=$key?>" href="" class="active">
									<div style="display: inline-block; width: 183px; height: 122px; background-image: url('<?=$value['preview']?>'); background-size: cover; background-position: center;"></div>
								</a>
							</li>
						<?
						}
						?>
					</ul>
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
						<div class="tab-content" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-home" role="tabpanel"
							     aria-labelledby="nav-home-tab">
							    <select name="old" style="margin-top: -20px;"></select>
								<div class="dates"></div>
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
										<a href="#"
										   class="div minus d-flex justify-content-center align-items-center">-</a>
										<input class="current-amount" name="quantity" size="3" value="1">
										<a href="#"
										   class="div plus d-flex justify-content-center align-items-center">+</a>
									</div>
								</div>
								<div class="line d-flex justify-content-between align-items-center">
									<p class="ticket-type">Взрослый</p>
									<p class="price">500 руб.</p>
									<p class="amount grey">2 билета</p>
									<div class="amount-block d-flex">
										<a href="#"
										   class="div minus d-flex justify-content-center align-items-center">-</a>
										<input class="current-amount" name="quantity" size="3" value="1">
										<a href="#"
										   class="div plus d-flex justify-content-center align-items-center">+</a>
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
<? include 'footer.php' ?>

<? include 'counters.php' ?>

</body>

<style>
	.contacts-form input {
		width: 100%;
	}

	body .contacts-form .after-p {
		max-width: 100%;
	}
</style>
</html>
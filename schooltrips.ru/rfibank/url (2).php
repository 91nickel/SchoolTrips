<?php
require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$linkFieldId = 572679;
$emailFieldId = 95406;
$ROPID = 2242528;
auth_amoCRM($amo);

$skip = false;

//file_put_contents(DIRNAME(__FILE__)."/hook.log", print_r($_REQUEST,1));
$leadsids = array();
$leadsids = array(array('id'=>$_GET['id']));
$update = array('update' => array());
foreach ($leadsids as $leadid){
    $id = $leadid['id'];
    //$leads = getLeadsById($amo, $id);
    //$leads = $leads['response']['leads'];
    $leads = getLeadsByIdNew($amo, $id);
    $leads = $leads['_embedded']['items'];
    $k=0;
    $a=0;
    //$contact = $contact[0];
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
        }
        if(($lead['status_id']==142)||($lead['status_id']==143))
            die("Извините, ваша запись отменена. Попробуйте записаться на другую экскурсию.<br><a href='/'>Записаться</a>");
        //echo $lead['main_contact']['id'];
        $contact = getContactById($amo, $lead['main_contact']['id']);
        $contact = $contact[0];
        foreach ($contact['custom_fields'] as $field){
            if ($field['id'] == $emailFieldId){
                $email = $field['values'][0]['value'];
            }
        }
        if($lead['pipeline']['id']!=679696)
        {
            $skip = true;
        }
        $price = $lead['sale'];
        if(isset($_GET['s']))
            $price = $_GET['s']*2-346;
        if(isset($_GET['per']))
            $price = (int)$lead['sale']/100*(int)$_GET['per'];
        if(isset($_GET['per']))
        {
            $f = json_decode(file_get_contents('finance/pays.json'),1);
            $payed = 0;
            foreach ($f as $key => $value) {
                if($value['id']==$lead['id'])
                    $payed += (int)$value['sum'];
            }
            $price = ((int)$lead['sale'] - (int)$payed)/100*(int)$_GET['per'];
        }
        if(isset($_GET['ost']))
        {
            $f = json_decode(file_get_contents('finance/pays.json'),1);
            $payed = 0;
            foreach ($f as $key => $value) {
                if($value['id']==$lead['id'])
                    $payed += (int)$value['sum'];
            }
            $price = (int)$lead['sale'] - (int)$payed;
        }
        //print_r($lead['catalog_elements']['_links']['self']['href']);
        if(empty($skip))
        {
            $id_cat = explode('&id=', $lead['catalog_elements']['_links']['self']['href']);
            $id_cat = $id_cat[1];
            $cat = meth($amo,'/api/v2/catalog_elements?id='.$id_cat);
            foreach ($cat['_embedded']['items'] as $key => $value) {
                if($value['catalog_id']==6687)
                    $cat = $value;
            }
            $ost = '';
            $type = '';
            $ost_a = 0;
            foreach ($cat['custom_fields'] as $key => $value) {
                //print_r($value);
                if($value['id']==579273)
                {
                    $type = 'all';
                    $ost = (int)$value['values'][0]['value'];
                }
                if($value['id']==578761)
                {
                    $ost_k = (int)$value['values'][0]['value'];
                }
                if($value['id']==579269)
                {
                    $ost_a = (int)$value['values'][0]['value'];
                }
            }

            $adult = 0;
            $kids = 0;

            foreach ($lead['custom_fields'] as $key => $value) {
                if($value['id']==103878)
                    $kids = $value['values'][0]['value'];
                if($value['id']==118758)
                    $adult = $value['values'][0]['value'];
            }

            $all = $adult+$kids;

            if($lead['pipeline']['id']==679696)
                if($ost!='')
                {
                    if($all>$ost)
                        die("Извините, но мест больше нет. Попробуйте записаться на другую экскурсию.<br><a href='/'>Записаться</a>");
                }else{
                    if($kids>$ost_k)
                        die("Извините, но мест больше нет. Попробуйте записаться на другую экскурсию.<br><a href='/'>Записаться</a>");
                    if($adult>$ost_a)
                        die("Извините, но мест больше нет. Попробуйте записаться на другую экскурсию.<br><a href='/'>Записаться</a>");
                }
        }

        $name = $lead['name'];
        $key = 'SORmCvpVV5f6A3aN14bk7UZoHAi8ej/chESKCDSgq5c=';
        //print_r($lead['price']);
        $request = array(
            'key' => $key,
            'cost' => $price,
            'name' => 'Мероприятие от SchoolTrip',

            'order_id' => $lead['id'].'_'.time(),
            //'email' => $email,
   /*         'invoice_data' => array(
                'items' => array(
                    'name' => $name,
                    'price' => $price,
                    'unit' => 'service',
                    'quantity' => 1,
                    'sum' => $price,
                    'vat_mode' => 'none',
                )
            ),*/
        );
        if (isset($email)){
            $request['email'] = $email;
        }
        $request = http_build_query($request, 'flags_');

	    file_put_contents(DIRNAME(__FILE__)."/lead.log", print_r($lead,1));
	    // Выставить счёт
	    $link='https://partner.rficb.ru/alba/build_link/input_short/?'.$request;
        file_put_contents(DIRNAME(__FILE__)."/link.log", $link);
	    $curl=curl_init();
	    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
	    curl_setopt($curl,CURLOPT_URL,$link);
	    // curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
	    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($request));
	    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

	    $out=curl_exec($curl);
	    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
	    curl_close($curl);
	    $code=(int)$code;
	    $result = json_decode($out, true);
	    $link = $result['url'];
        /*array_push($update['update'], array(
            'id' => $lead['id'],
            'updated_at' => time(),
            'custom_fields' => array(
                array(
                    'id' => $linkFieldId,
                    'values' => array(
                        array('value' => $link)
                    )
                )
            )
        ));*/
	    //updateLeadsByIds($amo, $update);
	    file_put_contents(DIRNAME(__FILE__)."/link.log", $link);
        file_put_contents(DIRNAME(__FILE__)."/update.log", print_r($update, true));
	}
}

if(isset($_GET['skip'])||($skip==true))
{
    header('Location: '.$link);
}

if(empty($n))
{
    file_get_contents('http://nova-agency.ru/auto/schooltrip/yclients/cron.php?link='.$_GET['id']);
    echo "<script>window.location.reload();</script>";
    die;
}
?>


<!DOCTYPE html>
<html data-wf-page="5a71eb73ead1240001ec4daf" data-wf-site="5a71eb72ead1240001ec4daa">
<head>
  <meta charset="utf-8">
  <title>School trip - авторские экскурсии-квесты в музеях Москвы</title>
  <meta content="Нескучные экскурсии для детей, 👍 Экскурсии, квесты и занятия в музеях, ✍️Онлайн подготовка к школе, 🎉Крутые Дни Рождения, 📞84999384700, 📝Запись на экскурсии" name="description">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Webflow" name="generator">
  <link href="/css/normalize.css" rel="stylesheet" type="text/css">
  <link href="/css/webflow.css" rel="stylesheet" type="text/css">
  <link href="/css/school-trip-a67234.webflow.css" rel="stylesheet" type="text/css">
  <script src='/order/calendar4/lib/jquery.min.js'></script>
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js" type="text/javascript"></script>
  <script type="text/javascript">WebFont.load({  google: {    families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic"]  }});</script>
  <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
  <link href="/order-page/images/favic.png" rel="shortcut icon" type="image/x-icon">
  <link href="/order-page/images/favicon.png" rel="apple-touch-icon">
  <link rel="stylesheet" href="/order/calendar4/css/uikit.min.css" />
	<script src="/order/calendar4/js/uikit.min.js"></script>
	<script src="/order/calendar4/js/uikit-icons.min.js"></script>
  <style>
.w-webflow-badge {
display: none !important;
}
</style>
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1832160763697453');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1832160763697453&ev=PageView&noscript=1"
        /></noscript>


</head>
    <body class="body">
        <div class="order-page">
            
            <section class="o-header">
                <div class="w-container">
                    <a href="/" class="logo"></a>
                      <div class="phone-box">
                          <a href="#" class="phone"><span class="light">8 (499) </span>938 47 00</a>
                      </div>
                </div>
            </section>
            
            <section class="o-body">
                <div class="w-container">
                    <div class="ob-block">
                        <div class="ob-line obl-1">
                            Добрый день, <span><?=$contact['name']?></span>
                        </div>
                        <div class="tit">
                            Ваш заказ:
                        </div>
                        <div class="ob-line">
                            Программа: <span><?=$n?></span>
                        </div>
                        <div class="ob-line">
                            Детей: <span><?=$k?></span>
                        </div>
                        <div class="ob-line">
                            Взрослых: <span><?=$a?></span>
                        </div>
                        <div class="ob-line">
                            Дата программы: <span><?=date('d.m.Y',strtotime($d))?></span>
                        </div>
                        <div class="ob-line">
                            Начало программы: <span><?=$t?></span>
                        </div>
                    </div>
                    <form action="" method="post">
                        <div class="checks">
                            <input type="checkbox" id="check-1" required>
                            <label for="check-1">Я согласен с <a target="_blank" href="#" uk-toggle="target: #offert">условиями покупки</a></label>
                        </div>
                        <a href="<?=$link?>" class="button" type="submit">Перейти к оплате</a>
                    </form>
                </div>
            </section>
            
            <section class="o-footer">
                <div class="w-container">
                    <div class="div-block-29">
                        <div class="div-block-30"><img src="/images/logo2.png" class="image-6">
                          <div class="text-block-5">Экскурсии, квесты, мастер-классы<br>для детей в Москве и МО</div>

                        <div class="text-block-5"><p><a style="color: #d3c9e1; line-height: 3.5;" href="/publichnaya_oferta_Norkaytene.pdf" target="_blank">Публичная оферта</a></p></div>

                        </div>
                        <div class="div-block-31">
                          <div>Адрес: г. Москва, <br>ул.&nbsp;Большие Каменщики 6<div style="color: #d3c9e1; font-size: 12px;">ОГРНИП 318392600000150 <br>ИП Норкайтене Д.А.</div>  </div>

                        </div>
                        <div class="div-block-32">
                          <div>schooltrip@yandex.ru</div>
                          <div>8(499) 938 47 00&nbsp;</div></div>
                      </div>
                </div>
            </section>
            
        </div>

        <div id="offert" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
          <button class="uk-modal-close-default" type="button" uk-close></button>
          <h2 class="uk-modal-title">Публичная оферта</h2>
          <p>
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
  <style>
  	.button{
	  	color: #fff!important;
	    position: relative;
	    margin: 0px;
	    top: 0;
  	}
  </style>

  <script>
  	$(document).ready(function(){
  		$('.button').click(function(e){
  			if(!$('#check-1').is(':checked'))
  			{
  				e.preventDefault();
  				alert('Необходимо согласиться с условиями');
  			}
  		})
  	})
  </script>
  <script src="https://callback.onlinepbx.ru/loader.js" charset="UTF-8" data-onpbxcb-id="250af62f8d0118738df62e30972f14e4"></script>
    </body>
      
</html>

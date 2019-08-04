<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='fullcalendar.min.css' rel='stylesheet' />
<link href='fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='lib/moment.min.js'></script>
<script src='lib/jquery.min.js'></script>
<script src='fullcalendar.min.js'></script>
<script src='locale-all.js'></script>
<link rel="stylesheet" href="css/uikit.min.css" />
<script src="js/uikit.min.js"></script>
<script src="js/uikit-icons.min.js"></script>
<script>

  var list = [];
  var event_opened = {};
  var cal_event_opened = {};

  $(document).ready(function() {
    var initialLocaleCode = 'ru';

    $.get('../list.php',{},function(data){
      list = data;
      var events = [];
      for(var i in data)
      {
        var event = {};
        event.title = data[i].name;
        var places = 0;
        var places_ost = '';
        var start = 0;
        var date = 0;
        var places_all='';
        var places_kids = '';
        var places_adult = '';

        var old = '';
        for(var t in data[i].custom_fields)
        {
          if(data[i].custom_fields[t].id==572521)
            date = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==572531)
            start = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==578757)
            places = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==578907)
            places = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==579273)
            places_all = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==578761)
            places_kids = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==579269)
            places_adult = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==579273)
            places_ost = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==578761)
            places_ost = data[i].custom_fields[t].values[0].value;
          if(data[i].custom_fields[t].id==579707)
            old = data[i].custom_fields[t].values[0].value;
        }
        if(places_ost=='')
          places_ost = places;
        event.title+=" "+old;
        event.title+=" (осталось "+places_ost+"/"+places+")";
        date = date.split('.');
        event.start=date[2]+"-"+date[1]+"-"+date[0]+"T"+start+":00";
        start = start.split(':');
        event.end=date[2]+"-"+date[1]+"-"+date[0]+"T"+(parseInt(start[0])+2)+":"+start[1]+":00";
        if(places_ost==0)
          event.backgroundColor = "#bdd0da";
        event.className="ev"+i;
        event.places_all = places_all;
        event.places_kids = places_kids;
        event.places_adult = places_adult;
        events.push(event);
      }
      console.log(events);
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'basicWeek,basicDay'
        },
        locale: initialLocaleCode,
        buttonIcons: true, // show the prev/next text
        weekNumbers: false,
        navLinks: false, // can click day/week names to navigate views
        editable: false,
        eventLimit: false, // allow "more" link when too many events
        defaultView: 'basicWeek',
        allDaySlot: false,
        minTime: "08:00:00",
        maxTime: "20:00:00",
        height: 'auto',
        events: events,
        eventClick: function(calEvent, jsEvent, view) {

          console.log(calEvent);

          if(calEvent.backgroundColor=='#bdd0da')
          {
            UIkit.modal.alert('Мест больше нет')
            return false;
          }

          if(calEvent.places_all!='')
          {
            $('#form .max').show();
            $('#form .max span').html(calEvent.places_all);
          }else{
            $('#form .max').hide();
          }

          if(calEvent.places_kids!='')
          {
            $('#p1').attr('placeholder','Кол-во детей(осталось мест '+calEvent.places_kids+')');
          }else{
            $('#p1').attr('placeholder','Кол-во детей');
          }

          if(calEvent.places_adult!='')
          {
            $('#p2').attr('placeholder','Кол-во взрослых(осталось мест '+calEvent.places_adult+')');
          }else{
            $('#p2').attr('placeholder','Кол-во взрослых');
          }

          UIkit.modal("#form").show();
          console.log(calEvent.className[0].substr(2));
          event_opened = list[calEvent.className[0].substr(2)];
          cal_event_opened = calEvent;
          recalc();
        }
      });
    },'json')

    $('#p1').keyup(recalc);
    $('#p2').keyup(recalc);

    $('.makeOrder').click(function(e){
      e.preventDefault();
      UIkit.modal("#form").hide();
      event_opened.k1 = $('#p1').val();
      event_opened.k2 = $('#p2').val();
      event_opened.sale = $('.price').html();
      event_opened.name = $('#name').val();
      event_opened.tel = $('#tel').val();
      event_opened.email = $('#email').val();

      if(cal_event_opened.places_kids!='')
      if(parseInt(event_opened.k1)>parseInt(cal_event_opened.places_kids))
      {
        UIkit.modal.alert('Осталось мест для детей: '+cal_event_opened.places_kids)
        return false;
      }
      if(cal_event_opened.places_adult!='')
      if(parseInt(event_opened.k2)>parseInt(cal_event_opened.places_adult))
      {
        UIkit.modal.alert('Осталось мест для взрослых: '+cal_event_opened.places_adult)
        return false;
      }
      if(cal_event_opened.places_all!='')
      if((parseInt(event_opened.k1)+parseInt(event_opened.k2))>parseInt(cal_event_opened.places_all))
      {
        UIkit.modal.alert('Осталось мест(дети+взрослые): '+cal_event_opened.places_all)
        return false;
      }
      console.log('ok');
      window.parent.postMessage({name:'order',val:event_opened},'*');
    })
  });

  function recalc()
  {
    var p1,p2;
    for(var i in event_opened.custom_fields)
    {
      if(event_opened.custom_fields[i].id==572523)
        p1 = parseInt(event_opened.custom_fields[i].values[0].value);
      if(event_opened.custom_fields[i].id==572527)
        p2 = parseInt(event_opened.custom_fields[i].values[0].value);
    }
    if(p1==undefined)
      p1 = 0;
    if(p2==undefined)
      p2 = 0;
    var cp1 = $('#p1').val();
    var cp2 = $('#p2').val();
    cp1 = cp1==''?0:cp1;
    cp2 = cp2==''?0:cp2;
    var price = parseInt(cp1)*p1+parseInt(cp2)*p2;
    if(isNaN(price))
      price = 0;
    $('.price').html(price);
  }

</script>
<style>

  body {
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #top {
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    line-height: 40px;
    font-size: 12px;
  }

  #calendar {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 10px;
  }

  .fc-event{
    cursor: pointer;
  }

  .fc-title{
    white-space: normal;
  }

  p{
    padding: 25px;
  }

  #form .max{
    padding: 10px 0;
    margin: 0;
    display: none;
  }

</style>
</head>
<body>
  <p>
    Для того, чтобы записаться:
    <br><br>1. Выберите экскурсию
    <br>2. Заполните личные данные и данные по кол-ву посетителей
    <br>3. Вас перенаправит на страницу оплаты, оплатите экскурсию банковской картой
    <br>4. После оплаты вы получите СМС с билетом
    <br>5. За день до экскурсии с 20:00 до 21:00 вы получите детали экскурсии с местом встречи и номером куратора
    <br>6. Группа сбора будет ожидать вас на месте встречи
  </p>

  <div id='calendar'></div>

  <div id="form" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
          <button class="uk-modal-close-default" type="button" uk-close></button>
          <h2 class="uk-modal-title">Введите данные</h2>
          <p class="max">Осталось мест на <span>10</span> человек</p>
          <form>
              <div class="uk-margin">
                <input id="p1" class="uk-input" placeholder="Кол-во детей">
              </div>
              <div class="uk-margin">
                <input id="p2" class="uk-input" placeholder="Кол-во взрослых">
              </div>
              <? if(!isset($_GET['amo'])) {?>
              <div class="uk-margin">
                <input id="name" class="uk-input" placeholder="Имя">
              </div>
              <div class="uk-margin">
                <input id="tel" class="uk-input" placeholder="Телефон(+79998887777)">
              </div>
              <div class="uk-margin">
                <input id="email" class="uk-input" placeholder="Email(test@test.ru)">
              </div>
              <? } ?>
              <button class="uk-button uk-button-primary makeOrder" style="float: right">Записать</button>
              <h3 style="margin-top: 20px;">Стоимость: <b class="price">0</b>р</h3>
          </form>
         
      </div>
  </div>

</body>
</html>
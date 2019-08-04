var list = [];
var list2 = [];
var list3 = [];
  var event_opened = {};
  var cal_event_opened = {};

var abon_id = '';

var user = {};

  var exc = {};

  var weekDay = ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'];

  function showList(data)
  {
    var exc_f = $('*[name=exc]').val();
    var old_f = $('*[name=old]').val();
    $('.events.main .block').html('');
    list = data;
    var events = [];
    for(var i in data)
    {
      var event = {};
      event.title = data[i].name;
      if(exc_f!='')
        if(event.title!=exc_f)
          continue;
      exc[data[i].name] = true;
      var places = 0;
      var places_ost = '';
      var start = 0;
      var date = 0;
      var places_all='';
      var places_kids = '';
      var places_adult = '';
      var p1 = '';
      var p2 = '';
      var ad_ob = false;

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
        if(data[i].custom_fields[t].id==572523)
          p1 = parseInt(data[i].custom_fields[t].values[0].value);
        if(data[i].custom_fields[t].id==572527)
          p2 = parseInt(data[i].custom_fields[t].values[0].value);
        if(data[i].custom_fields[t].id==581177)
          ad_ob = parseInt(data[i].custom_fields[t].values[0].value);
      }
      if(places_ost=='')
        places_ost = places;
      var old_for_f = old.split('+').join('').split('-');
      if(old_f!='')
      {
        if(old_for_f=='')
          continue;
        if(old_f<old_for_f[0])
          continue;
        if(old_for_f.length>1)
          if(old_f>old_for_f[1])
            continue;
      }
      //event.title+=" "+old;
      event.old = old+' лет';
      event.date = date;
      event.start = start;
      event.places_ost = places_ost;
      event.places = places;
      event.price = p1;
      event.ad_ob = ad_ob;
      //event.start=date[2]+"-"+date[1]+"-"+date[0]+"T"+start+":00";
      //start = start.split(':');
      event.end=date[2]+"-"+date[1]+"-"+date[0]+"T"+(parseInt(start[0])+2)+":"+start[1]+":00";
      if(places_ost==0)
        event.backgroundColor = "#bdd0da";
      event.className="ev"+i;
      event.places_all = places_all;
      event.places_kids = places_kids;
      event.places_adult = places_adult;
      events.push(event);
    }

    var exc_list = [];

    for(var i in exc)
      exc_list.push(i);

    exc_list = exc_list.sort();

    for(var i in exc_list)
      $('*[name=exc]').append('<option value="'+exc_list[i]+'">'+exc_list[i]+'</option>');

    for(var i in events)
      for(var t in events)
      {
        //var d1 = events[i].date;
        var d1 = new Date(events[i].date.split('.')[2],events[i].date.split('.')[1]-1,events[i].date.split('.')[0],events[i].start.split(':')[0],events[i].start.split(':')[1]);
        var d2 = new Date(events[t].date.split('.')[2],events[t].date.split('.')[1]-1,events[t].date.split('.')[0],events[t].start.split(':')[0],events[t].start.split(':')[1]);
        if(d1.getTime()<d2.getTime())
        {
          var temp = events[t];
          events[t] = events[i];
          events[i] = temp;
        }
      }
    console.log(events);
    var lastDate = '';
    for(var i in events)
    {
      if(lastDate!=events[i].date)
      {
        lastDate = events[i].date;
        $('.events.main .block').append('<div class="date"><div class="t">'+events[i].date+' - '+weekDay[(new Date(events[i].date.split('.')[2],events[i].date.split('.')[1]-1,events[i].date.split('.')[0])).getDay()]+'</div></div>');
      }

      var classes = '';
      if(events[i].places_ost<=0)
        classes+='empty';
      var json = JSON.stringify(events[i]);
      $('.events.main .block').append('<div json=\''+json+'\' class="event '+classes+'"><div class="title">'+events[i].title+'</div><div class="places">'+events[i].places_ost+"/"+events[i].places+' мест'+'</div><div class="time">'+events[i].start+'</div>'+(events[i].old==' лет'?'':'<div class="old">'+events[i].old+'</div>')+'<div class="price">'+events[i].price+'р</div></div>');
    }
    $('.events.main .event').click(function(){
      var calEvent = $(this).attr('json');
      calEvent = $.parseJSON(calEvent);
      window.parent.postMessage({name:'scroll',val:''},'*');
      if(calEvent.places_ost<=0)
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
      event_opened = list[calEvent.className.substr(2)];
      cal_event_opened = calEvent;
      recalc();
    })

    if((window.location.hash!='#exc=')&&(window.location.hash!=''))
    {
      //$('option[value="'+decodeURIComponent(window.location.hash.substr(1).split('=')[1])+'"]').parent
      if($('select option[value="'+decodeURIComponent(window.location.hash.substr(1).split('=')[1])+'"]').length>0)
      if($('select[name=exc]').val()!=decodeURIComponent(window.location.hash.substr(1).split('=')[1]))
      {
        $('select[name=exc]').val(decodeURIComponent(window.location.hash.substr(1).split('=')[1]));
        showList(list);
      }
      //$('#filter').submit();
      //console.log(window.location.hash.substr(1).split('=')[1])
    }

    if(window.location.href.indexOf('login')!=-1)
    {
      var pars = window.location.href.split('?')[1];
      pars = pars.split('&');
      console.log(pars);
      var params = {};
      for(var i in pars)
        params[pars[i].split('=')[0]] = decodeURIComponent(pars[i].split('=')[1]);
      console.log(params);
      $('input[name=tel]').val(params.login);
      $('input[name=pass]').val(params.pass);
      $('*[name=auth]').click();
    }
  }

  function showList2(data)
  {
    $('.events.kurs .block').html('');
    list2 = data;
    var events = [];
    for(var i in data)
    {
      var event = {};

      var old = '';
      var time = '';
      var desc = '';
      var price = 0;
      for(var t in data[i].custom_fields)
      {
        if(data[i].custom_fields[t].id==583019)
          price = data[i].custom_fields[t].values[0].value;
        if(data[i].custom_fields[t].id==583023)
          old = data[i].custom_fields[t].values[0].value;
        if(data[i].custom_fields[t].id==583021)
          time = data[i].custom_fields[t].values[0].value;
        if(data[i].custom_fields[t].id==583029)
          desc = data[i].custom_fields[t].values[0].value;
      }
      event.className="ev"+i;
      event.price = price;
      event.title = data[i].name;
      event.old = old+' лет';
      event.start = time;
      event.desc = desc;
      events.push(event);
    }

    var lastDate = '';
    $('.events.kurs .block').append('<div class="date"><div class="t">Онлайн курсы</div></div>');
    for(var i in events)
    {
      var json = JSON.stringify(events[i]);
      $('.events.kurs .block').append('<div json=\''+json+'\' class="event"><div class="title">'+events[i].title+'</div><div class="time">'+events[i].start+'</div>'+(events[i].old==' лет'?'':'<div class="old">'+events[i].old+'</div>')+'<div class="price">'+events[i].price+'р</div><div class="cur">'+events[i].desc.split('\n').join('<br>')+'</div></div>');
    }
    $('.events.kurs .event').click(function(){
      var calEvent = $(this).attr('json');
      calEvent = $.parseJSON(calEvent);
      window.parent.postMessage({name:'scroll',val:''},'*');

      UIkit.modal("#form").show();
      event_opened = list2[calEvent.className.substr(2)];
      cal_event_opened = calEvent;
      $('#form .price').html(calEvent.price);
    })
  }

  function showList3(data)
  {
    $('.events.abon .block').html('');
    list3 = data;
    var events = [];
    for(var i in data)
    {
      var event = {};

      var time = '';
      var desc = '';
      var price = 0;
      for(var t in data[i].custom_fields)
      {
        if(data[i].custom_fields[t].id==584417)
          price = data[i].custom_fields[t].values[0].value;
        if(data[i].custom_fields[t].id==584413)
          time = data[i].custom_fields[t].values[0].value;
        if(data[i].custom_fields[t].id==584415)
          desc = data[i].custom_fields[t].values[0].value;
      }
      event.className="ev"+i;
      event.price = price;
      event.title = data[i].name;
      event.start = time;
      event.desc = desc+' экскурсий';
      events.push(event);
    }

    var lastDate = '';
    $('.events.abon .block').append('<div class="date"><div class="t">Абонементы</div></div>');
    for(var i in events)
    {
      var json = JSON.stringify(events[i]);
      $('.events.abon .block').append('<div json=\''+json+'\' class="event"><div class="title">'+events[i].title+'</div><div class="time">'+events[i].start+'</div>'+'<div class="price">'+events[i].price+'р</div><div class="cur">'+events[i].desc.split('\n').join('<br>')+'</div></div>');
    }
    $('.events.abon .event').click(function(){
      var calEvent = $(this).attr('json');
      calEvent = $.parseJSON(calEvent);
      window.parent.postMessage({name:'scroll',val:''},'*');

      UIkit.modal("#form").show();
      event_opened = list3[calEvent.className.substr(2)];
      cal_event_opened = calEvent;
      $('#form .price').html(calEvent.price);
    })
  }

  $(document).ready(function() {
    $("#auth_form").submit(function(e){
      e.preventDefault();
    })

    if($('.auth_page').length>0)
    {
      set_cookie("user_schooltrip4", '');
    }



    if((get_cookie("user_schooltrip4")!=undefined)&&(get_cookie("user_schooltrip4")!=''))
    {
      $('.user').show();
      $('.my').show();
      user = $.parseJSON(get_cookie("user_schooltrip4"));
      $('.auth').html('Выйти');
      for(var i in user.custom_fields)
        if(user.custom_fields[i].id==95404)
        {
          user.phone = user.custom_fields[i].values[0].value
          $('.user').html(user.custom_fields[i].values[0].value)
        }
    }else{
      console.log('ok');
      if(window.location.href.split('user=').length>1)
      {
        var l = window.location.href.split('?')[1];
        l = l.split('&');
        for(var i in l)
          l[i] = l[i].split('=');
        for(var i in l)
        {
          if(l[i][0]=='userid')
            user.id = l[i][1];
          if(l[i][0]=='userphone')
            user.phone = l[i][1];
          user.phone = user.phone.split('%20').join('');
          $('.user').html(user.phone)
          $('.user').show();
          $('.my').show();
          $('.auth').html('Выйти');
        }
      }else if($('.events_exc').length>0)
      {
        window.location.href="auth.php";
      }
    }

    if(user.id!=undefined)
      $.get('../get_abon.php',{con:user.id},function(data){
        if(data.c!=0)
        {
          $('.makeOrderAbon span').html('(осталось '+data.c+')');
          $('.makeOrderAbon span').attr('count',data.c);
          abon_id = data.id;
        }
      },'json')

    if($('.events_exc').length>0)
    {
      $.get('../get_leads.php',{tel: user.phone}, function(data){
        if(data!='')
        {
          data = $.parseJSON(data);
          console.log(data);
          var t1 = '';
          var t2 = '';
          var t3 = '';
          for(var i in data){
            var title = '';
            var price = data[i].sale;
            var start = '';
            var date = '';
            var cur = 'Не назначен';
            var place = 'Не определено';
            var kids = 0;
            var adult = 0;
            var photo = 'В процессе обработки';
            for(var t in data[i].custom_fields)
            {
              if(data[i].custom_fields[t].id==574181)
              {
                title = data[i].custom_fields[t].values[0].value;
              }
              if(data[i].custom_fields[t].id==118724)
              {
                start = data[i].custom_fields[t].values[0].value;
              }
              if(data[i].custom_fields[t].id==103924)
              {
                date = data[i].custom_fields[t].values[0].value.split(' ')[0].split('-');
                date = date[2]+'.'+date[1]+'.'+date[0];
              }
              if(data[i].custom_fields[t].id==578295)
              {
                cur = data[i].custom_fields[t].values[0].value;
              }
              if(data[i].custom_fields[t].id==103892)
              {
                place = data[i].custom_fields[t].values[0].value;
              }
              if(data[i].custom_fields[t].id==103878)
              {
                kids = data[i].custom_fields[t].values[0].value;
              }
              if(data[i].custom_fields[t].id==118758)
              {
                adult = data[i].custom_fields[t].values[0].value;
              }
              if(data[i].custom_fields[t].id==576817)
              {
                photo = data[i].custom_fields[t].values[0].value;
              }
            }
            start += '<br>'+date;
            start = "<span style='text-align:center; display:inline-block'>"+start+"</span>";
            if(title=='')
              continue;
            if(data[i].status_id==15562216)
              t1+=('<div class="event"><div class="title">'+title+'</div><div class="time">'+start+'</div><div class="cur">Состав: '+kids+' детей + '+adult+' взрослых</div>'+'<div class="price">'+price+'р</div><a href="http://nova-agency.ru/auto/schooltrip/rfibank/url.php?id='+data[i].id+'" target="_blank" class="uk-button pay uk-button-primary">Оплатить</a></div>');
            else if((data[i].status_id==142))
              t3+=('<div class="event"><div class="title">'+title+'</div><div class="time">'+start+'</div><div class="cur">Куратор: '+cur+'</div><div class="place">Место встречи: '+place+'</div><div class="cur">Состав: '+kids+' детей + '+adult+' взрослых</div><div class="photo">Фото: '+(photo=='В процессе обработки'?photo:'<a href="'+photo+'">'+photo+'</a>')+'</div>'+'<div class="price">'+price+'р</div></div>');
            else
              t2+=('<div class="event"><div class="title">'+title+'</div><div class="time">'+start+'</div><div class="cur">Куратор: '+cur+'</div><div class="place">Место встречи: '+place+'</div><div class="cur">Состав: '+kids+' детей + '+adult+' взрослых</div>'+'<div class="price">'+price+'р</div><img src="http://qrcoder.ru/code/?http%3A%2F%2Fnova-agency.ru%2Fauto%2Fschooltrip%2FQR.php%3Flead_id%3D'+data[i].id+'&4&0" alt="" /></div>');
          }
          console.log(t2);
          console.log('T2');
          if(t1!='')
          {
            $('.events_exc .block').append('<div class="date"><div class="t">Еще не оплаченные</div></div>');
            $('.events_exc .block').append(t1);
          }
          if(t2!='')
          {
            $('.events_exc .block').append('<div class="date"><div class="t">Оплаченные</div></div>');
            $('.events_exc .block').append(t2);
          }
          if(t3!='')
          {
            $('.events_exc .block').append('<div class="date"><div class="t">Прошедшие</div></div>');
            $('.events_exc .block').append(t3);
          }
        }
      });
    }

    $('button[name=auth]').click(function(){
      console.log('auth click');
      var tel = $('input[name=tel]').val();
      tel = tel.split('+').join('');
      var code = $('input[name=pass]').val();
      $.get('../auth.php',{tel:tel,code:code},function(data){
        if(data!='false')
        {
          set_cookie("user_schooltrip4", data);
          window.location.href="exc.php";
        }else{
          UIkit.modal.alert('Пароль не подходит');
        }
      })
    })

    $('button[name=get_pass]').click(function(){
      var tel = $('input[name=tel]').val();
      tel = tel.split('+').join('');
      $.get('../get_pass.php',{tel:tel},function(data){
        UIkit.modal.alert('Пароль отправлен в СМС');
      })
    })

    var initialLocaleCode = 'ru';

    $('#filter').submit(function(e){
      e.preventDefault();
      window.location.hash = '#exc=';
      showList(list);
    })

    $.get('../list.php',{},function(data){
      showList(data);
    },'json')

    $.get('../list_kurs.php',{},function(data){
      showList2(data);
    },'json')

    $.get('../list_abon.php',{},function(data){
      showList3(data);
    },'json')

    $('#p1').keyup(recalc);
    $('#p2').keyup(recalc);

    $('.makeOrder').click(function(e){
      e.preventDefault();
      event_opened.k1 = $('#p1').val();
      event_opened.k2 = $('#p2').val();
      event_opened.name = $('#name').val();
      event_opened.sale = $('#form .price').html();
      event_opened.title = cal_event_opened.title+' '+cal_event_opened.date+' '+cal_event_opened.start;
      event_opened.tel = $('#tel').val();
      event_opened.email = $('#email').val();
      event_opened.data = $(this).attr('data');

      if(cal_event_opened.desc!=undefined)
      {
        event_opened.count = cal_event_opened.desc;
        event_opened.count = event_opened.count.split(' ')[0];
      }

      if((cal_event_opened.ad_ob!=false))
      {
        if((event_opened.k2=='')||(parseInt(event_opened.k2)==0))
        {
          UIkit.modal.alert('На эту экскурсию должен пойти хотя бы 1 взрослый')
          return false;
        }
      }
      
      UIkit.modal("#form").hide();

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
      console.log(event_opened);
      window.parent.postMessage({name:'order',val:event_opened},'*');
    })

    $('.makeOrderAbon').click(function(e){
      e.preventDefault();
      if($('.makeOrderAbon span').attr('count')==undefined)
        window.location.href = 'abon.php';
      event_opened.k1 = $('#p1').val();
      event_opened.k2 = $('#p2').val();
      event_opened.name = $('#name').val();
      event_opened.sale = $('#form .price').html();
      event_opened.title = cal_event_opened.title+' '+cal_event_opened.date+' '+cal_event_opened.start;
      event_opened.tel = $('#tel').val();
      event_opened.email = $('#email').val();
      event_opened.data = $(this).attr('data');

      if((cal_event_opened.ad_ob!=false))
      {
        if((event_opened.k2=='')||(parseInt(event_opened.k2)==0))
        {
          UIkit.modal.alert('На эту экскурсию должен пойти хотя бы 1 взрослый')
          return false;
        }
      }
      
      UIkit.modal("#form").hide();

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
      console.log(event_opened);
      if(parseInt(event_opened.k1)>parseInt($('.makeOrderAbon span').attr('count')))
      {
        UIkit.modal.alert('Осталось экскурсий по абонементу: '+$('.makeOrderAbon span').attr('count'))
        return false;
      }
      event_opened.p1 = event_opened.k1;
      event_opened.p2 = event_opened.k2;
      event_opened.cat_id = event_opened.id;
      event_opened.con = user.id;
      event_opened.abon = abon_id;
      UIkit.modal.alert('Формирование заказа');
      $.get('../../excursions/newAbon.php',event_opened,function(data){
        UIkit.modal.alert('Спасибо, за день до экскурсии(до 18:00) вы получите детали с местом встречи и телефоном куратора');
      })
      //window.parent.postMessage({name:'order',val:event_opened},'*');
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
    $('#form .price').html(price);
  }

  function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
  {
    var cookie_string = name + "=" + escape ( value );
   
    if ( exp_y )
    {
      var expires = new Date ( exp_y, exp_m, exp_d );
      cookie_string += "; expires=" + expires.toGMTString();
    }
   
    if ( path )
          cookie_string += "; path=/";
   
    if ( domain )
          cookie_string += "; domain=" + escape ( domain );
    
    if ( secure )
          cookie_string += "; secure";
    
    document.cookie = cookie_string;
  }

  function get_cookie ( cookie_name )
  {
    var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
   
    if ( results )
      return ( unescape ( results[2] ) );
    else
      return null;
  }
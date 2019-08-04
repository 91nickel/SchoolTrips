var list = [];
var list2 = [];
var list3 = [];
var event_opened = {};
var cal_event_opened = {};

var abon_id = '';

var user = {};

var openedOB = '';

var exc = {};

var weekDay = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];

$(document).ready(function(){
    if(get_cookie('auto_name')!=undefined)
        $('#modal-contact input[name=name]').val(get_cookie('auto_name'));
    if(get_cookie('auto_phone')!=undefined)
        $('#modal-contact input[name=phone]').val(get_cookie('auto_phone'));
    if(get_cookie('auto_email')!=undefined)
        $('#modal-contact input[name=email]').val(get_cookie('auto_email'));
})

function openModalContact(e)
{
    e.preventDefault();
    if($('.a1').val()>0)
        $('*[data-target="#modal-contact"]').click();
}

function showList(data) {
    var exc_f = $('*[name=exc]').val();
    var old_f = $('*[name=old]').val();
    var childs = $('*[name=childs]').val();
    var adults = $('*[name=adults]').val();
    $('.events.main .block').html('');
    list = data;
    var events = [];
    for (var i in data) {
        var event = {};
        event.title = data[i].name;
        if ($('*[name=exc]').length > 0)
            if (exc_f != '')
                if (event.title != exc_f)
                    continue;
        exc[data[i].name] = true;
        var places = 0;
        var places_ost = '';
        var start = 0;
        var date = 0;
        var places_all = '';
        var places_kids = '';
        var places_adult = '';
        var p1 = '';
        var p2 = '';
        var ad_ob = false;
        var type = '';
        var type_s = 'Сборная';

        var old = '';
        for (var t in data[i].custom_fields) {
            if (data[i].custom_fields[t].id == 572521)
                date = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 572531)
                start = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 578757)
                places = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 578907)
                places = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 579273)
                places_all = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 578761)
                places_kids = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 579269)
                places_adult = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 579273)
                places_ost = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 578761)
                places_ost = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 579707)
                old = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 572523)
                p1 = parseInt(data[i].custom_fields[t].values[0].value);
            if (data[i].custom_fields[t].id == 572527)
                p2 = parseInt(data[i].custom_fields[t].values[0].value);
            if (data[i].custom_fields[t].id == 581177)
                ad_ob = parseInt(data[i].custom_fields[t].values[0].value);
            if (data[i].custom_fields[t].id == 589342)
                type = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 590536)
                type_s = data[i].custom_fields[t].values[0].value;
        }
        if (places_ost == '')
            places_ost = places;
        if (places_ost == 0)
            places_ost = places_adult;
        var old_for_f = old.split('+').join('').split('-');
        if (old_f != '') {
            if (old_for_f == '')
                continue;
            if (old_f < old_for_f[0])
                continue;
            if (old_for_f.length > 1)
                if (old_f > old_for_f[1])
                    continue;
        }
        if ($('body').hasClass('events')) {
            if (type != 'Мероприятие')
                continue;
        } else {
            if (type == 'Мероприятие')
                continue;
        }
        //event.title+=" "+old;
        if (date == 0)
            continue;
        event.old = old + ' лет';
        event.date = date;
        event.start = start;
        event.places_ost = places_ost;
        event.places = places;
        event.price = p1;
        event.ad_ob = ad_ob;
        event.type = type;
        //event.start=date[2]+"-"+date[1]+"-"+date[0]+"T"+start+":00";
        //start = start.split(':');
        event.end = date[2] + "-" + date[1] + "-" + date[0] + "T" + (parseInt(start[0]) + 2) + ":" + start[1] + ":00";
        if (places_ost == 0)
            event.backgroundColor = "#bdd0da";
        event.className = "ev" + i;
        event.places_all = places_all;
        event.places_kids = places_kids;
        event.places_adult = places_adult;
        event.type_s = type_s;
        if ((places != places_ost) && (type_s == 'Групповая'))
            continue;
        if (childs != '')
            if (parseInt(event.places_kids) < parseInt(childs))
                continue;
            else if (event.places_kids == '')
                continue;
        if (adults != '')
            if (parseInt(event.places_adult) < parseInt(adults))
                continue;
            else if (event.places_adult == '')
                continue;
        if (event.type != 'Мероприятие')
            events.push(event);
    }

    var exc_list = [];

    for (var i in exc)
        exc_list.push(i);

    exc_list = exc_list.sort();

    for (var i in exc_list)
        $('*[name=exc]').append('<option value="' + exc_list[i] + '">' + exc_list[i] + '</option>');

    for (var i in events)
        for (var t in events) {
            //var d1 = events[i].date;
            var d1 = new Date(events[i].date.split('.')[2], events[i].date.split('.')[1] - 1, events[i].date.split('.')[0], events[i].start.split(':')[0], events[i].start.split(':')[1]);
            var d2 = new Date(events[t].date.split('.')[2], events[t].date.split('.')[1] - 1, events[t].date.split('.')[0], events[t].start.split(':')[0], events[t].start.split(':')[1]);
            if (d1.getTime() < d2.getTime()) {
                var temp = events[t];
                events[t] = events[i];
                events[i] = temp;
            }
        }
    var lastDate = '';
    for (var i in events) {

        var classes = '';
        if (events[i].places_ost <= 0)
            classes += 'empty';
        var json = JSON.stringify(events[i]);
        $('.events.main .block').append('<div json=\'' + json + '\' class="event ' + classes + '"><div class="title">' + events[i].title + '</div><div class="places">' + events[i].places_ost + ' мест свободно' + '</div><div class="places2">' + events[i].places + ' мест всего' + '</div><div class="time">' + events[i].start + '</div>' + (events[i].old == ' лет' ? '' : '<div class="old">' + events[i].old + '</div>') + '<div class="price">' + events[i].price + 'р</div></div>');
    }
    $('.events.main .event').click(function () {
        var calEvent = $(this).attr('json');
        calEvent = $.parseJSON(calEvent);
        window.parent.postMessage({name: 'scroll', val: ''}, '*');
        if (calEvent.places_ost <= 0) {
            UIkit.modal.alert('Мест больше нет')
            return false;
        }

        if (calEvent.places_all != '') {
            $('#form .max').show();
            $('#form .max span').html(calEvent.places_all);
        } else {
            $('#form .max').hide();
        }

        if (calEvent.places_kids != '') {
            $('#p1').attr('placeholder', 'Кол-во детей(осталось мест ' + calEvent.places_kids + ')');
        } else {
            $('#p1').attr('placeholder', 'Кол-во детей');
        }

        if (calEvent.places_adult != '') {
            $('#p2').attr('placeholder', 'Кол-во взрослых(осталось мест ' + calEvent.places_adult + ')');
        } else {
            $('#p2').attr('placeholder', 'Кол-во взрослых');
        }

        UIkit.modal("#form").show();
        event_opened = list[calEvent.className.substr(2)];
        cal_event_opened = calEvent;
        recalc();
    })

    if ((window.location.hash != '#exc=') && (window.location.hash != '')) {
        //$('option[value="'+decodeURIComponent(window.location.hash.substr(1).split('=')[1])+'"]').parent
        if ($('select option[value="' + decodeURIComponent(window.location.hash.substr(1).split('=')[1]) + '"]').length > 0)
            if ($('select[name=exc]').val() != decodeURIComponent(window.location.hash.substr(1).split('=')[1])) {
                $('select[name=exc]').val(decodeURIComponent(window.location.hash.substr(1).split('=')[1]));
                showList(list);
            }
        //$('#filter').submit();
        //console.log(window.location.hash.substr(1).split('=')[1])
    }

    if (window.location.href.indexOf('login') != -1) {
        var pars = window.location.href.split('?')[1];
        pars = pars.split('&');
        var params = {};
        for (var i in pars)
            params[pars[i].split('=')[0]] = decodeURIComponent(pars[i].split('=')[1]);
        $('input[name=tel]').val(params.login);
        $('input[name=pass]').val(params.pass);
        $('*[name=auth]').click();
    }
}

function showList2(data) {
    $('.events.kurs .block').html('');
    list2 = data;
    var events = [];
    for (var i in data) {
        var event = {};

        var old = '';
        var time = '';
        var desc = '';
        var price = 0;
        for (var t in data[i].custom_fields) {
            if (data[i].custom_fields[t].id == 583019)
                price = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 583023)
                old = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 583021)
                time = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 583029)
                desc = data[i].custom_fields[t].values[0].value;
        }
        event.className = "ev" + i;
        event.price = price;
        event.title = data[i].name;
        event.old = old + ' лет';
        event.start = time;
        event.desc = desc;
        events.push(event);
    }

    var lastDate = '';
    $('.events.kurs .block').append('<div class="date"><div class="t">Онлайн курсы</div></div>');
    for (var i in events) {
        var json = JSON.stringify(events[i]);
        $('.events.kurs .block').append('<div json=\'' + json + '\' class="event"><div class="title">' + events[i].title + '</div><div class="time">' + events[i].start + '</div>' + (events[i].old == ' лет' ? '' : '<div class="old">' + events[i].old + '</div>') + '<div class="price">' + events[i].price + 'р</div><div class="cur">' + events[i].desc.split('\n').join('<br>') + '</div></div>');
    }
    $('.events.kurs .event').click(function () {
        var calEvent = $(this).attr('json');
        calEvent = $.parseJSON(calEvent);
        window.parent.postMessage({name: 'scroll', val: ''}, '*');

        UIkit.modal("#form").show();
        event_opened = list2[calEvent.className.substr(2)];
        cal_event_opened = calEvent;
        $('#form .price').html(calEvent.price);
    })
}

function showList3(data) {
    $('.events.abon .block').html('');
    list3 = data;
    var events = [];
    for (var i in data) {
        var event = {};

        var time = '';
        var desc = '';
        var price = 0;
        for (var t in data[i].custom_fields) {
            if (data[i].custom_fields[t].id == 584417)
                price = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 584413)
                time = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 584415)
                desc = data[i].custom_fields[t].values[0].value;
        }
        event.className = "ev" + i;
        event.price = price;
        event.title = data[i].name;
        event.start = time;
        event.desc = desc + ' экскурсий';
        events.push(event);
    }

    var lastDate = '';
    $('.events.abon .block').append('<div class="date"><div class="t">Абонементы</div></div>');
    for (var i in events) {
        var json = JSON.stringify(events[i]);
        $('.events.abon .block').append('<div json=\'' + json + '\' class="event"><div class="title">' + events[i].title + '</div><div class="time">' + events[i].start + '</div>' + '<div class="price">' + events[i].price + 'р</div><div class="cur">' + events[i].desc.split('\n').join('<br>') + '</div></div>');
    }
    $('.events.abon .event').click(function () {
        var calEvent = $(this).attr('json');
        calEvent = $.parseJSON(calEvent);
        window.parent.postMessage({name: 'scroll', val: ''}, '*');

        UIkit.modal("#form").show();
        event_opened = list3[calEvent.className.substr(2)];
        cal_event_opened = calEvent;
        $('#form .price').html(calEvent.price);
    })
}

var disc = 0;
var disc_type = '';
var promo = '';

$(document).ready(function () {
    $("#auth_form").submit(function (e) {
        e.preventDefault();
    })

    if ($('.auth_page').length > 0) {
        set_cookie("user_schooltrip4", '');
    }

    $('.promo_btn').click(function () {
        $.get('../list_promo.php', function (data) {
            var promo2 = $('.promo').val();
            var is = false;
            for (var i in data)
                if (data[i].name.toUpperCase() == promo2.toUpperCase()) {
                    for (var t in data[i].custom_fields)
                        if (data[i].custom_fields[t].name == 'Скидка') {
                            var v = data[i].custom_fields[t].values[0].value;
                            disc = v.substr(0, v.length - 1);
                            disc_type = v.substr(v.length - 1);
                            promo = data[i].name.toUpperCase();
                            is = true;
                        }
                }
            if (!is) {
                disc = 0;
                disc_type = '';
                promo = '';
                alert('Промокод не найден');
            }
            recalc();
        }, 'json')
    })

    if (window.location.href.split('user').length > 1) {
        var l = window.location.href.split('?')[1];
        l = l.split('&');
        for (var i in l)
            l[i] = l[i].split('=');
        for (var i in l) {
            if (l[i][0] == 'userid')
                user.id = l[i][1];
            if (l[i][0] == 'userphone')
                user.phone = l[i][1];
            user.phone = user.phone.split('%20').join('');
            $('.user').html(user.phone);
            set_cookie("user_schooltrip4", JSON.stringify({id: user.id, phone: user.phone}));
            $('.user').show();
            $('.my').show();
            $('.auth').html('Выйти');
        }
    } else if ((get_cookie("user_schooltrip4") != undefined) && (get_cookie("user_schooltrip4") != '')) {
        console.log('user show');
        $('.user').show();
        $('.my').show();
        user = $.parseJSON(get_cookie("user_schooltrip4"));
        $('.auth').html('Выйти');
        for (var i in user.custom_fields)
            if (user.custom_fields[i].id == 95404) {
                user.phone = user.custom_fields[i].values[0].value
            }
        $('.user').html(user.phone);
    } else {
        if ($('.events_exc').length > 0) {
            window.location.href = "auth.php";
        }
    }

    $.get('../get_id.php', {tel: user.phone}, function (data) {
        if (data != '') {
            user.id = data;
            set_cookie("user_schooltrip4", JSON.stringify({id: user.id, phone: user.phone}));
            getAbon()
        }
    })

    if (user.id != undefined)
        getAbon();

    if ($('.events_exc').length > 0) {
        $.get('../get_leads.php', {tel: user.phone}, function (data) {
            if (data != '') {
                data = $.parseJSON(data);
                console.log(data);
                var t1 = '';
                var t2 = '';
                var t3 = '';
                for (var i in data) {
                    var title = '';
                    var price = data[i].sale;
                    var start = '';
                    var date = '';
                    var cur = 'Не назначен';
                    var place = 'Не определено';
                    var kids = 0;
                    var adult = 0;
                    var photo = 'В процессе обработки';
                    for (var t in data[i].custom_fields) {
                        if (data[i].custom_fields[t].id == 574181) {
                            title = data[i].custom_fields[t].values[0].value;
                        }
                        if (data[i].custom_fields[t].id == 118724) {
                            start = data[i].custom_fields[t].values[0].value;
                        }
                        if (data[i].custom_fields[t].id == 103924) {
                            date = data[i].custom_fields[t].values[0].value.split(' ')[0].split('-');
                            date = date[2] + '.' + date[1] + '.' + date[0];
                        }
                        if (data[i].custom_fields[t].id == 578295) {
                            cur = data[i].custom_fields[t].values[0].value;
                        }
                        if (data[i].custom_fields[t].id == 103892) {
                            place = data[i].custom_fields[t].values[0].value;
                        }
                        if (data[i].custom_fields[t].id == 103878) {
                            kids = data[i].custom_fields[t].values[0].value;
                        }
                        if (data[i].custom_fields[t].id == 118758) {
                            adult = data[i].custom_fields[t].values[0].value;
                        }
                        if (data[i].custom_fields[t].id == 576817) {
                            photo = data[i].custom_fields[t].values[0].value;
                        }
                    }
                    start += '<br>' + date;
                    start = "<span style='text-align:center; display:inline-block'>" + start + "</span>";
                    if (title == '')
                        continue;
                    if (data[i].status_id == 15562216)
                        t1 += ('<div class="event"><div class="title">' + title + '</div><div class="time">' + start + '</div><div class="cur">Состав: ' + kids + ' детей + ' + adult + ' взрослых</div>' + '<div class="price">' + price + 'р</div><a href="/rfibank/url.php?id=' + data[i].id + '" target="_blank" class="uk-button pay uk-button-primary">Оплатить</a></div>');
                    else if ((data[i].status_id == 142))
                        t3 += ('<div class="event"><div class="title">' + title + '</div><div class="time">' + start + '</div><div class="cur">Куратор: ' + cur + '</div><div class="place">Место встречи: ' + place + '</div><div class="cur">Состав: ' + kids + ' детей + ' + adult + ' взрослых</div><div class="photo">Фото: ' + (photo == 'В процессе обработки' ? photo : '<a href="' + photo + '">' + photo + '</a>') + '</div>' + '<div class="price">' + price + 'р</div></div>');
                    else
                        t2 += ('<div class="event"><div class="title">' + title + '</div><div class="time">' + start + '</div><div class="cur">Куратор: ' + cur + '</div><div class="place">Место встречи: ' + place + '</div><div class="cur">Состав: ' + kids + ' детей + ' + adult + ' взрослых</div>' + '<div class="price">' + price + 'р</div><img src="http://qrcoder.ru/code/?http%3A%2F%2Fnova-agency.ru%2Fauto%2Fschooltrip%2FQR.php%3Flead_id%3D' + data[i].id + '&4&0" alt="" /></div>');
                }
                console.log(t2);
                console.log('T2');
                if (t1 != '') {
                    $('.events_exc .block').append('<div class="date"><div class="t">Еще не оплаченные</div></div>');
                    $('.events_exc .block').append(t1);
                }
                if (t2 != '') {
                    $('.events_exc .block').append('<div class="date"><div class="t">Оплаченные</div></div>');
                    $('.events_exc .block').append(t2);
                }
                if (t3 != '') {
                    $('.events_exc .block').append('<div class="date"><div class="t">Прошедшие</div></div>');
                    $('.events_exc .block').append(t3);
                }
            }
        });
    }

    $('button[name=auth]').click(function () {
        console.log('auth click');
        var tel = $('input[name=tel]').val();
        tel = tel.split('+').join('');
        tel = tel.split(' ').join('');
        tel = tel.split('-').join('');
        tel = tel.split('(').join('');
        tel = tel.split(')').join('');
        window.location.href = 'http://nova-agency.ru/auto/schooltrip/cal/?link=exc.php?userphone=+' + tel;
    })

    $('button[name=get_pass]').click(function () {
        var tel = $('input[name=tel]').val();
        tel = tel.split('+').join('');
        $.get('../get_pass.php', {tel: tel}, function (data) {
            UIkit.modal.alert('Пароль отправлен в СМС');
        })
    })

    $('#loc, #age, #datepicker').change(function () {
        if (typeof listData != 'undefined')
            showNewList(false, true);
    })

    if (decodeURI(window.location.href).indexOf('d=') != -1) {
        var d = decodeURI(window.location.href).split('d=')[1].split('&')[0];
        if (d != '') {
            $('#datepicker').val(d);
            $('.tod-tom').removeClass('d-flex').addClass('d-none');
            $('#datepicker').change();
        }
    }

    $('body').on('click', '*[data-target="#modal-pay"]', function () {
        var ob = $(this).parents('.date').attr('json').split("'").join('"');
        ob = $.parseJSON(ob);
        console.log(ob);
        openedOB = ob;
        $('.p1').html(ob.price + " руб");
        $('.p2').html(ob.price2 + " руб");
        calcNew();
    })

    var initialLocaleCode = 'ru';

    var listData = [];

    $('.a1, .a2').change(function () {
        calcNew();
    })

    $('.promogo').click(function (e) {
        e.preventDefault();
        $.get('../list_promo.php', function (data) {
            var promo2 = $('*[name=promocode]').val();
            var is = false;
            for (var i in data)
                if (data[i].name.toUpperCase() == promo2.toUpperCase()) {
                    for (var t in data[i].custom_fields)
                        if (data[i].custom_fields[t].name == 'Скидка') {
                            var v = data[i].custom_fields[t].values[0].value;
                            disc = v.substr(0, v.length - 1);
                            disc_type = v.substr(v.length - 1);
                            promo = data[i].name;
                            is = true;
                        }
                }
            if (!is) {
                disc = 0;
                disc_type = '';
                promo = '';
                alert('Промокод не найден');
            }
            calcNew();
        }, 'json')
    })

    $('.js-pay').click(function (e) {
        e.preventDefault();
        if (!$('#modal-contact input[name=offert]').is(':checked')) {
            alert('Необходимо согласиться с условиями покупки');
            return false;
        }
        if(($('#modal-contact input[name=name]').val()=='')||($('#modal-contact input[name=phone]').val()==''))
        {
            alert('Поля ФИО и Номер телефона являются обязательными');
            return false;
        }
        var data = {
            sendpulse: $('#modal-contact input[name=sendpulse]').is(':checked') ? 'yes' : 'no',
            name: $('#modal-contact input[name=name]').val(),
            tel: $('#modal-contact input[name=phone]').val(),
            email: $('#modal-contact input[name=email]').val(),
            p1: $('.a1').val(),
            p2: $('.a2').val(),
            price: calcNew(),
            cat_id: openedOB.id
        };
        set_cookie('auto_name',$('#modal-contact input[name=name]').val());
        set_cookie('auto_phone',$('#modal-contact input[name=phone]').val());
        set_cookie('auto_email',$('#modal-contact input[name=email]').val());
        if (promo != '')
            data.promo = promo;
        $('body').html('<p class="text" style="margin-top: 200px; text-align: center">Формируется заказ<br><br><img src="2.gif"></p>');
        $.get('/excursions/new.php', data, function (data) {
            console.log(data);
            ym(39632640, 'reachGoal', 'ORDER_NEW');
            window.location.href = "/rfibank/url.php?skip&id=" + data;
        });
    });

    $('.js-notice').click(function (e) {
        e.preventDefault();
        var data = {
            sendpulse: $('#modal-contact2 input[name=sendpulse]').is(':checked') ? 'yes' : 'no',
            name: $('#modal-contact2 input[name=name]').val(),
            tags: 'форма-желание',
            title: $('.exc-name').html() + " - " + $('.js-name').html(),
            tel: $('#modal-contact2 input[name=phone]').val(),
            email: $('#modal-contact2 input[name=email]').val(),
            status_id: 15562204
        };
        $('#modal-contact2 .after-p').append('<b style="display: block; color: orange; margin-top: 20px">Отправка...</b>')
        $.get('/excursions/new.php', data, function (data) {
            console.log(data);
            $('#modal-contact2 .after-p').append('<b style="display: block; color: green; margin-top: 20px">Спасибо</b>')
        });
    })

    $('.js-group').click(function (e) {
        e.preventDefault();
        var data = {
            sendpulse: $('#modal-contact3 input[name=sendpulse]').is(':checked') ? 'yes' : 'no',
            name: $('#modal-contact3 input[name=name]').val(),
            tags: 'с сайта',
            title: $('.exc-name').html() + " - " + $('.js-name').html(),
            tel: $('#modal-contact3 input[name=phone]').val(),
            email: $('#modal-contact3 input[name=email]').val(),
            p1: $('#modal-contact3 input[name=children]').val(),
            p2: $('#modal-contact3 input[name=adult]').val(),
            status_id: 22196457
        };
        $('#modal-contact3 .after-p').append('<b style="display: block; color: orange; margin-top: 20px">Отправка...</b>')
        $.get('/excursions/new.php', data, function (data) {
            console.log(data);
            $('#modal-contact3 .after-p').append('<b style="display: block; color: green; margin-top: 20px">Спасибо</b>')
        });
    })

    $('#filter').submit(function (e) {
        e.preventDefault();
        window.location.hash = '#exc=';
        showList(list);
    })
    var all = 'false';
    if (decodeURI(window.location.href).indexOf('d=') == -1)
        all = 'true';
    console.log('start');
    $.get('../list.php', {all: all}, function (data) {
        console.log('1');
        showList(data);
        console.log('2');
        if (decodeURI(window.location.href).indexOf('d=') != -1)
            showNewList(data, true);
        else
            showNewList(data);
        if ($('body').hasClass('single')) {
            showOlds(data);
            console.log('show single');
            showSingle(data);
        }
    }, 'json')

    $.get('../list_kurs.php', {}, function (data) {
        showList2(data);
    }, 'json')

    $.get('../list_abon.php', {}, function (data) {
        showList3(data);
    }, 'json')

    $('#p1').keyup(recalc);
    $('#p2').keyup(recalc);

    $('body').on('click', '.nav-item', function () {
        showRasp($(this).html());
    });

    $('.makeOrder').click(function (e) {
        e.preventDefault();
        event_opened.k1 = $('#p1').val();
        event_opened.k2 = $('#p2').val();
        event_opened.name = $('#name').val();
        event_opened.sale = $('#form .price').html();
        if (event_opened.sale.indexOf(' ') != -1)
            event_opened.sale = event_opened.sale.split(' ')[1];
        event_opened.title = cal_event_opened.title + ' ' + cal_event_opened.date + ' ' + cal_event_opened.start;
        event_opened.tel = $('#tel').val();
        event_opened.email = $('#email').val();
        event_opened.data = $(this).attr('data');

        if (cal_event_opened.desc != undefined) {
            event_opened.count = cal_event_opened.desc;
            event_opened.count = event_opened.count.split(' ')[0];
        }

        if ((cal_event_opened.ad_ob != false)) {
            if ((event_opened.k2 == '') || (parseInt(event_opened.k2) == 0)) {
                UIkit.modal.alert('На эту экскурсию должен пойти хотя бы 1 взрослый')
                return false;
            }
        }

        UIkit.modal("#form").hide();

        if (cal_event_opened.places_kids != '')
            if (parseInt(event_opened.k1) > parseInt(cal_event_opened.places_kids)) {
                UIkit.modal.alert('Осталось мест для детей: ' + cal_event_opened.places_kids)
                return false;
            }
        if (cal_event_opened.places_adult != '')
            if (parseInt(event_opened.k2) > parseInt(cal_event_opened.places_adult)) {
                UIkit.modal.alert('Осталось мест для взрослых: ' + cal_event_opened.places_adult)
                return false;
            }
        if (cal_event_opened.places_all != '')
            if ((parseInt(event_opened.k1) + parseInt(event_opened.k2)) > parseInt(cal_event_opened.places_all)) {
                UIkit.modal.alert('Осталось мест(дети+взрослые): ' + cal_event_opened.places_all)
                return false;
            }
        event_opened.type_s = cal_event_opened.type_s;
        event_opened.promo = promo;
        window.parent.postMessage({name: 'order', val: event_opened}, '*');
    })

    $('.makeOrderAbon').click(function (e) {
        e.preventDefault();
        if ($('.makeOrderAbon span').attr('count') == undefined) {
            window.location.href = 'abon.php';
            return false;
        }
        event_opened.k1 = $('#p1').val();
        event_opened.k2 = $('#p2').val();
        event_opened.name = $('#name').val();
        event_opened.sale = $('#form .price').html();
        event_opened.title = cal_event_opened.title + ' ' + cal_event_opened.date + ' ' + cal_event_opened.start;
        event_opened.tel = $('#tel').val();
        event_opened.email = $('#email').val();
        event_opened.data = $(this).attr('data');

        if ((cal_event_opened.ad_ob != false)) {
            if ((event_opened.k2 == '') || (parseInt(event_opened.k2) == 0)) {
                UIkit.modal.alert('На эту экскурсию должен пойти хотя бы 1 взрослый')
                return false;
            }
        }

        UIkit.modal("#form").hide();

        if (cal_event_opened.places_kids != '')
            if (parseInt(event_opened.k1) > parseInt(cal_event_opened.places_kids)) {
                UIkit.modal.alert('Осталось мест для детей: ' + cal_event_opened.places_kids)
                return false;
            }
        if (cal_event_opened.places_adult != '')
            if (parseInt(event_opened.k2) > parseInt(cal_event_opened.places_adult)) {
                UIkit.modal.alert('Осталось мест для взрослых: ' + cal_event_opened.places_adult)
                return false;
            }
        if (cal_event_opened.places_all != '')
            if ((parseInt(event_opened.k1) + parseInt(event_opened.k2)) > parseInt(cal_event_opened.places_all)) {
                UIkit.modal.alert('Осталось мест(дети+взрослые): ' + cal_event_opened.places_all)
                return false;
            }
        if (parseInt(event_opened.k1) > parseInt($('.makeOrderAbon span').attr('count'))) {
            UIkit.modal.alert('Осталось экскурсий по абонементу: ' + $('.makeOrderAbon span').attr('count'))
            return false;
        }
        event_opened.p1 = event_opened.k1;
        event_opened.p2 = event_opened.k2;
        event_opened.cat_id = event_opened.id;
        event_opened.con = user.id;
        event_opened.abon = abon_id;
        UIkit.modal.alert('Формирование заказа');
        $.post('../../excursions/newAbon.php', event_opened, function (data) {
            UIkit.modal.alert('Спасибо, за день до экскурсии(до 18:00) вы получите детали с местом встречи и телефоном куратора');
        })
        //window.parent.postMessage({name:'order',val:event_opened},'*');
    })
});

function calcNew() {
    var total = $('.a1').val() * openedOB.price + $('.a2').val() * openedOB.price2;
    console.log(promo);
    console.log(disc);
    console.log(disc_type);

    if (disc_type == '%')
        total = parseInt(total * ((100 - disc) / 100));
    else
        total -= disc;

    $('.total .price').html(total + ' руб');
    return total;
}

function showNewList(data, filter) {
    console.log('show new list');
    if (filter && data == false) {
        console.log('write');
        data = listData;
    }

    listData = data;

    if ($('.js-json-exc').length == 0)
        return false;
    var json = $('.js-json-exc').html();
    json = $.parseJSON(json);

    var rj = {};
    for (var i in json)
        if (json[i].n)
            rj[json[i].n.split(' ').join('').toLowerCase().split('(малыши)').join('')] = json[i];

    console.log(rj);

    var nd = [];

    data.sort(function (a, b) {
        for (var t in a.custom_fields) {
            if (a.custom_fields[t].id == 572521)
                var date1 = a.custom_fields[t].values[0].value;
            if (a.custom_fields[t].id == 572531)
                var time1 = a.custom_fields[t].values[0].value;
        }

        for (var t in b.custom_fields) {
            if (b.custom_fields[t].id == 572521)
                var date2 = b.custom_fields[t].values[0].value;
            if (b.custom_fields[t].id == 572531)
                var time2 = b.custom_fields[t].values[0].value;
        }

        if (Date.parse(date1 + ' ' + time1) > Date.parse(date2 + ' ' + time2))
            return -1;
        else
            return 1;
    })

    $('.list_v').html('');
    var locations = {};
    var olds = {};
    for (var i in data) {
        var fullname = data[i].name;
        var folder = '';
        var n = data[i].name;
        n = n.split('-');
        var name = '';
        var place = '';
        if (n.length == 1) {
            name = n[0];
        } else {
            place = n[0];
            name = n[1];
        }

        if(place[0]==' ')
            place = place.substr(1);

        if(place[place.length-1]==' ')
            place = place.substr(0,place.length-1);

        locations[place] = true;

        var type = '', old = '', price = '', date = '';
        for (var t in data[i].custom_fields) {
            if (data[i].custom_fields[t].id == 589342)
                type = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 579707)
                old = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 572523)
                price = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 572521)
                date = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].id == 594140)
                folder = data[i].custom_fields[t].values[0].value;
        }
        olds[old] = true;

        if ($('body').hasClass('caramel')) {
            if (type != 'Мероприятие')
                continue;
        } else {
            if (type == 'Мероприятие')
                continue;
        }

        if (filter) {
            /*if($('#age').val()!='')
             if(old!=$('#age').val())
             continue;*/
            if (($('#loc').val() != '') && ($('#loc').val() != null))
                if (place != $('#loc').val())
                    continue;
            if ($('#datepicker').attr('d') != undefined)
                if (date != $('#datepicker').attr('d'))
                    continue;
        }

        if (nd[fullname] != undefined)
            continue;

        if(folder=='')
            continue;

        nd[fullname] = true;

        var fn = folder;

        if (rj[fn] == undefined) {
            continue;
        }

        console.log(fn);

        var old = 99;

        for (var t in olds) {
            if (t.indexOf('-') != -1) {
                var k = t.split('-');
                if (parseInt(k[0]) < old)
                    old = parseInt(k[0]);
                if (parseInt(k[1]) < old)
                    old = parseInt(k[1]);
            } else {
                var k = t.split('+').join('');
                if (parseInt(k) < old)
                    old = parseInt(k);
            }
        }

        if (old == 99)
            old = '';
        else
            old = old + '+';

        $('.list_v').append('<div class="col-12 col-md-6"><div class="exc-card" style="background-image: url(' + rj[fn].photo + ');" ><div class="text"><div class="top-line d-flex justify-content-between"><p class="big-m"><u class=""><span>' + place + ' ' + old + '</span> </u></p><p class="exc-price-t">' + price + ' <span>₽</span></p></div><a class="min-t" href="/event/' + fn + '"><u>' + name + '</u></a><div class="description big-m"></div></div></div></div>')
    }

    var loc = $('#loc').val();

    $('#loc').html('<option value="" selected>Все</option>');
    for (var i in locations)
        if (i != '')
            $('#loc').append('<option value="' + i + '">' + i + '</option>');

    $('#loc').val(loc);
}

function showRasp(act) {
    console.log('work');
    $('.dates').html('');
    var exc = $('body').attr('js-exc');
    console.log(listData);
    for (var i in listData) {
        var type, old, price = 0, date, time, all, ost, price2 = 0;
        for (var t in listData[i].custom_fields) {
            if (listData[i].custom_fields[t].id == 572521)
                date = listData[i].custom_fields[t].values[0].value;
            if (listData[i].custom_fields[t].id == 572531)
                time = listData[i].custom_fields[t].values[0].value;
        }
        listData[i].date = date;
        listData[i].time = time;
    }
    listData.sort(function (a, b) {
        var time1 = '00:00';
        var time2 = '00:00';
        for (var t in a.custom_fields) {
            if (a.custom_fields[t].id == 572521)
                var date1 = a.custom_fields[t].values[0].value;
            if (a.custom_fields[t].id == 572531)
                var time1 = a.custom_fields[t].values[0].value;
        }

        for (var t in b.custom_fields) {
            if (b.custom_fields[t].id == 572521)
                var date2 = b.custom_fields[t].values[0].value;
            if (b.custom_fields[t].id == 572531)
                var time2 = b.custom_fields[t].values[0].value;
        }

        if ((new Date(date1.split('.')[2], date1.split('.')[1] - 1, date1.split('.')[0], time1.split(':')[0], time1.split(':')[1])).getTime() < (new Date(date2.split('.')[2], date2.split('.')[1] - 1, date2.split('.')[0], time2.split(':')[0], time2.split(':')[1])).getTime())
            return -1;
        else
            return 1;
    })
    for (var i in listData) {
        var is = false;
        for(var t in listData[i].custom_fields)
            if(listData[i].custom_fields[t].name=='Папка')
                if(listData[i].custom_fields[t].values[0].value==openedEventSingle)
                    is = true;
        if (is) {
            var type, old, price = 0, date, time, all, ost, price2 = 0;
            for (var t in listData[i].custom_fields) {
                if (listData[i].custom_fields[t].id == 589342)
                    type = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 579707)
                    old = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 572523)
                    price = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 572527)
                    price2 = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 572521)
                    date = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 572531)
                    time = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 572531)
                    time = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 578907)
                    all = listData[i].custom_fields[t].values[0].value;
                if (listData[i].custom_fields[t].id == 578761)
                    ost = listData[i].custom_fields[t].values[0].value;
            }

            if(ost==undefined)
                ost = all;

            var d = new Date(date.split('.')[2], date.split('.')[1] - 1, date.split('.')[0]);

            var weekday = ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"];
            var month = ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"];
            var month2 = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];

            console.log(openedEventSingle);

            /*var o = $('select[name=old]').find('option[value='+$('body[js-exc]').attr('js-exc')+']').html();
            if(o==undefined)
                o = $('select[name=old]').find('option').eq(0).html();

            if($(o).length>0)
                if(old!=o.split(' лет')[0])
                    continue;*/

            if (Date.parse(date + ' ' + time) < Date.today())
                continue;

            listData[i].price = price;
            listData[i].price2 = price2;

            var json = JSON.stringify(listData[i]);
            json = json.split('"').join("'");

            $('.dates').append('<div json="' + json + '" class="date d-flex"><div class="day"><div class="day-month">' + d.getDate() + ' ' + month[d.getMonth()] + '</div><div class="day-name">' + weekday[d.getDay()]+', '+old+' лет</div></div><div class="line d-flex"><div class="time big">' + time + '</div><div class="location">|&nbsp;&nbsp;&nbsp;</div><div class="places d-flex">  <div class="free-places text-center grey">Свободные <br> места</div><div class="free big">' + ost + '</div><div class="symb big">/</div><div class="amount big">' + all + '</div></div><div class="order"><a href="#" data-toggle="modal" data-dismiss="modal" data-target="#modal-pay">Купить билет</a></div></div></div>');
        }
    }

    if ($('.dates').html() == '') {
        $('.dates').html('<p style="margin-top: 20px;">Даты не найдены</p><div class="order or-1" style="margin: 20px 0;"><a href="#" data-toggle="modal" data-target="#modal-contact2">Уведомить, когда будет</a></div>')
    }
}

var lastDataSingle = false;

function showSingle(data) {
    if (data == undefined)
        data = lastDataSingle;
    lastDataSingle = data;
    console.log('showSingle');
    var exc = $('body').attr('js-exc');

    for (var i in data) {
        if (data[i].id == exc) {
            $('body').css('opacity', '1');
            var fullname = data[i].name;
            var n = data[i].name;
            n = n.split('-');
            var name = '';
            var place = '';
            if (n.length == 1) {
                name = n[0];
            } else {
                place = n[0];
                name = n.join('-').split(place + '-').join('');
            }

            var type, old, price, date, f;
            for (var t in data[i].custom_fields) {
                if (data[i].custom_fields[t].id == 589342)
                    type = data[i].custom_fields[t].values[0].value;
                if (data[i].custom_fields[t].id == 579707)
                    old = data[i].custom_fields[t].values[0].value;
                if (data[i].custom_fields[t].id == 572523)
                    price = data[i].custom_fields[t].values[0].value;
                if (data[i].custom_fields[t].id == 572521)
                    date = data[i].custom_fields[t].values[0].value;
                if (data[i].custom_fields[t].id == 594140)
                    f = data[i].custom_fields[t].values[0].value;
            }

            $('.name-age .exc-name').html(place);
            $('.js-name').html(name);
            $.get('name.php', {meth: 'set', name: name, key: $('body').attr('js-exc-f')})
            openedEventSingle = f;
            $('.name-age .age').html(old + ' лет');
            $('.age_st').html(old + ' лет');
            $('.price_st').html(price + ' р');
            showRasp();
            break;
        }
    }
}

function showOlds(data) {
    var exc = $('body').attr('js-exc-f');
    var olds = {};
    var f_id = '';

    for (var i in data) {
        var f = '';
        var old = '';
        for (var t in data[i].custom_fields) {
            if (data[i].custom_fields[t].name == 'Папка')
                f = data[i].custom_fields[t].values[0].value;
            if (data[i].custom_fields[t].name == 'Возраст(от-до)')
                old = data[i].custom_fields[t].values[0].value;
        }

        if (f == exc) {
            olds[old] = [old, data[i].id];
            if (f_id == '')
                f_id = data[i].id;
        }
    }

    console.log(olds);

    var ol = 0;
    for (var i in olds)
        ol++;

    if (ol > 1) {
        $('select[name="old"]').html('');
        for (var i in olds) {
            $('select[name="old"]').append('<option value="' + olds[i][1] + '">' + olds[i][0] + ' лет</option>');
        }
    } else {
        $('select[name="old"]').hide();
        $('select[name="old"]').after('<p class="grey age"></p>');
    }

    $('body').attr('js-exc', f_id);

    $('select[name="old"]').change(function () {
        $('body').attr('js-exc', $(this).val());
        showSingle();
    })
}

function getAbon() {
    $.get('../get_abon.php', {con: user.id}, function (data) {
        if (data.c != 0) {
            $('.makeOrderAbon span').html('(осталось ' + data.c + ')');
            $('.makeOrderAbon span').attr('count', data.c);
            abon_id = data.id;
        }
    }, 'json')
}

function recalc() {
    var p1, p2;
    for (var i in event_opened.custom_fields) {
        if (event_opened.custom_fields[i].id == 572523)
            p1 = parseInt(event_opened.custom_fields[i].values[0].value);
        if (event_opened.custom_fields[i].id == 572527)
            p2 = parseInt(event_opened.custom_fields[i].values[0].value);
    }
    if (p1 == undefined)
        p1 = 0;
    if (p2 == undefined)
        p2 = 0;
    var cp1 = $('#p1').val();
    var cp2 = $('#p2').val();
    cp1 = cp1 == '' ? 0 : cp1;
    cp2 = cp2 == '' ? 0 : cp2;
    var price = parseInt(cp1) * p1 + parseInt(cp2) * p2;
    if (isNaN(price))
        price = 0;
    if (disc != 0) {
        if (disc_type == 'р')
            price -= disc;
        else
            price = price * ((100 - disc) / 100);
    }
    if (price < 0)
        price = 0;
    if (cal_event_opened.type_s == 'Групповая')
        price = 'предоплата 5000';
    $('#form .price').html(price);
}

function set_cookie(name, value, exp_y, exp_m, exp_d, path, domain, secure) {
    var cookie_string = name + "=" + escape(value);

    if (exp_y) {
        var expires = new Date(exp_y, exp_m, exp_d);
        cookie_string += "; expires=" + expires.toGMTString();
    }

    if (path)
        cookie_string += "; path=/";

    if (domain)
        cookie_string += "; domain=" + escape(domain);

    if (secure)
        cookie_string += "; secure";

    document.cookie = cookie_string;
}

function get_cookie(cookie_name) {
    var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');

    if (results)
        return ( unescape(results[2]) );
    else
        return null;
}

/**
 * @version: 1.0 Alpha-1
 * @author: Coolite Inc. http://www.coolite.com/
 * @date: 2008-05-13
 * @copyright: Copyright (c) 2006-2008, Coolite Inc. (http://www.coolite.com/). All rights reserved.
 * @license: Licensed under The MIT License. See license.txt and http://www.datejs.com/license/. 
 * @website: http://www.datejs.com/
 */
Date.CultureInfo={name:"ru-RU",englishName:"Russian (Russia)",nativeName:"русский (Россия)",dayNames:["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"],abbreviatedDayNames:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],shortestDayNames:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],firstLetterDayNames:["В","П","В","С","Ч","П","С"],monthNames:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],abbreviatedMonthNames:["янв","фев","мар","апр","май","июн","июл","авг","сен","окт","ноя","дек"],amDesignator:"",pmDesignator:"",firstDayOfWeek:1,twoDigitYearMax:2029,dateElementOrder:"dmy",formatPatterns:{shortDate:"dd.MM.yyyy",longDate:"d MMMM yyyy 'г.'",shortTime:"H:mm",longTime:"H:mm:ss",fullDateTime:"d MMMM yyyy 'г.' H:mm:ss",sortableDateTime:"yyyy-MM-ddTHH:mm:ss",universalSortableDateTime:"yyyy-MM-dd HH:mm:ssZ",rfc1123:"ddd, dd MMM yyyy HH:mm:ss GMT",monthDay:"MMMM dd",yearMonth:"MMMM yyyy 'г.'"},regexPatterns:{jan:/^янв(арь)?/i,feb:/^фев(раль)?/i,mar:/^мар(т)?/i,apr:/^апр(ель)?/i,may:/^май/i,jun:/^июн(ь)?/i,jul:/^июл(ь)?/i,aug:/^авг(уст)?/i,sep:/^сен(тябрь)?/i,oct:/^окт(ябрь)?/i,nov:/^ноя(брь)?/i,dec:/^дек(абрь)?/i,sun:/^воскресенье/i,mon:/^понедельник/i,tue:/^вторник/i,wed:/^среда/i,thu:/^четверг/i,fri:/^пятница/i,sat:/^суббота/i,future:/^next/i,past:/^last|past|prev(ious)?/i,add:/^(\+|aft(er)?|from|hence)/i,subtract:/^(\-|bef(ore)?|ago)/i,yesterday:/^yes(terday)?/i,today:/^t(od(ay)?)?/i,tomorrow:/^tom(orrow)?/i,now:/^n(ow)?/i,millisecond:/^ms|milli(second)?s?/i,second:/^sec(ond)?s?/i,minute:/^mn|min(ute)?s?/i,hour:/^h(our)?s?/i,week:/^w(eek)?s?/i,month:/^m(onth)?s?/i,day:/^d(ay)?s?/i,year:/^y(ear)?s?/i,shortMeridian:/^(a|p)/i,longMeridian:/^(a\.?m?\.?|p\.?m?\.?)/i,timezone:/^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\s*(\+|\-)\s*\d\d\d\d?)|gmt|utc)/i,ordinalSuffix:/^\s*(st|nd|rd|th)/i,timeContext:/^\s*(\:|a(?!u|p)|p)/i},timezones:[{name:"UTC",offset:"-000"},{name:"GMT",offset:"-000"},{name:"EST",offset:"-0500"},{name:"EDT",offset:"-0400"},{name:"CST",offset:"-0600"},{name:"CDT",offset:"-0500"},{name:"MST",offset:"-0700"},{name:"MDT",offset:"-0600"},{name:"PST",offset:"-0800"},{name:"PDT",offset:"-0700"}]};
(function(){var $D=Date,$P=$D.prototype,$C=$D.CultureInfo,p=function(s,l){if(!l){l=2;}
return("000"+s).slice(l*-1);};$P.clearTime=function(){this.setHours(0);this.setMinutes(0);this.setSeconds(0);this.setMilliseconds(0);return this;};$P.setTimeToNow=function(){var n=new Date();this.setHours(n.getHours());this.setMinutes(n.getMinutes());this.setSeconds(n.getSeconds());this.setMilliseconds(n.getMilliseconds());return this;};$D.today=function(){return new Date().clearTime();};$D.compare=function(date1,date2){if(isNaN(date1)||isNaN(date2)){throw new Error(date1+" - "+date2);}else if(date1 instanceof Date&&date2 instanceof Date){return(date1<date2)?-1:(date1>date2)?1:0;}else{throw new TypeError(date1+" - "+date2);}};$D.equals=function(date1,date2){return(date1.compareTo(date2)===0);};$D.getDayNumberFromName=function(name){var n=$C.dayNames,m=$C.abbreviatedDayNames,o=$C.shortestDayNames,s=name.toLowerCase();for(var i=0;i<n.length;i++){if(n[i].toLowerCase()==s||m[i].toLowerCase()==s||o[i].toLowerCase()==s){return i;}}
return-1;};$D.getMonthNumberFromName=function(name){var n=$C.monthNames,m=$C.abbreviatedMonthNames,s=name.toLowerCase();for(var i=0;i<n.length;i++){if(n[i].toLowerCase()==s||m[i].toLowerCase()==s){return i;}}
return-1;};$D.isLeapYear=function(year){return((year%4===0&&year%100!==0)||year%400===0);};$D.getDaysInMonth=function(year,month){return[31,($D.isLeapYear(year)?29:28),31,30,31,30,31,31,30,31,30,31][month];};$D.getTimezoneAbbreviation=function(offset){var z=$C.timezones,p;for(var i=0;i<z.length;i++){if(z[i].offset===offset){return z[i].name;}}
return null;};$D.getTimezoneOffset=function(name){var z=$C.timezones,p;for(var i=0;i<z.length;i++){if(z[i].name===name.toUpperCase()){return z[i].offset;}}
return null;};$P.clone=function(){return new Date(this.getTime());};$P.compareTo=function(date){return Date.compare(this,date);};$P.equals=function(date){return Date.equals(this,date||new Date());};$P.between=function(start,end){return this.getTime()>=start.getTime()&&this.getTime()<=end.getTime();};$P.isAfter=function(date){return this.compareTo(date||new Date())===1;};$P.isBefore=function(date){return(this.compareTo(date||new Date())===-1);};$P.isToday=function(){return this.isSameDay(new Date());};$P.isSameDay=function(date){return this.clone().clearTime().equals(date.clone().clearTime());};$P.addMilliseconds=function(value){this.setMilliseconds(this.getMilliseconds()+value);return this;};$P.addSeconds=function(value){return this.addMilliseconds(value*1000);};$P.addMinutes=function(value){return this.addMilliseconds(value*60000);};$P.addHours=function(value){return this.addMilliseconds(value*3600000);};$P.addDays=function(value){this.setDate(this.getDate()+value);return this;};$P.addWeeks=function(value){return this.addDays(value*7);};$P.addMonths=function(value){var n=this.getDate();this.setDate(1);this.setMonth(this.getMonth()+value);this.setDate(Math.min(n,$D.getDaysInMonth(this.getFullYear(),this.getMonth())));return this;};$P.addYears=function(value){return this.addMonths(value*12);};$P.add=function(config){if(typeof config=="number"){this._orient=config;return this;}
var x=config;if(x.milliseconds){this.addMilliseconds(x.milliseconds);}
if(x.seconds){this.addSeconds(x.seconds);}
if(x.minutes){this.addMinutes(x.minutes);}
if(x.hours){this.addHours(x.hours);}
if(x.weeks){this.addWeeks(x.weeks);}
if(x.months){this.addMonths(x.months);}
if(x.years){this.addYears(x.years);}
if(x.days){this.addDays(x.days);}
return this;};var $y,$m,$d;$P.getWeek=function(){var a,b,c,d,e,f,g,n,s,w;$y=(!$y)?this.getFullYear():$y;$m=(!$m)?this.getMonth()+1:$m;$d=(!$d)?this.getDate():$d;if($m<=2){a=$y-1;b=(a/4|0)-(a/100|0)+(a/400|0);c=((a-1)/4|0)-((a-1)/100|0)+((a-1)/400|0);s=b-c;e=0;f=$d-1+(31*($m-1));}else{a=$y;b=(a/4|0)-(a/100|0)+(a/400|0);c=((a-1)/4|0)-((a-1)/100|0)+((a-1)/400|0);s=b-c;e=s+1;f=$d+((153*($m-3)+2)/5)+58+s;}
g=(a+b)%7;d=(f+g-e)%7;n=(f+3-d)|0;if(n<0){w=53-((g-s)/5|0);}else if(n>364+s){w=1;}else{w=(n/7|0)+1;}
$y=$m=$d=null;return w;};$P.getISOWeek=function(){$y=this.getUTCFullYear();$m=this.getUTCMonth()+1;$d=this.getUTCDate();return p(this.getWeek());};$P.setWeek=function(n){return this.moveToDayOfWeek(1).addWeeks(n-this.getWeek());};$D._validate=function(n,min,max,name){if(typeof n=="undefined"){return false;}else if(typeof n!="number"){throw new TypeError(n+" is not a Number.");}else if(n<min||n>max){throw new RangeError(n+" is not a valid value for "+name+".");}
return true;};$D.validateMillisecond=function(value){return $D._validate(value,0,999,"millisecond");};$D.validateSecond=function(value){return $D._validate(value,0,59,"second");};$D.validateMinute=function(value){return $D._validate(value,0,59,"minute");};$D.validateHour=function(value){return $D._validate(value,0,23,"hour");};$D.validateDay=function(value,year,month){return $D._validate(value,1,$D.getDaysInMonth(year,month),"day");};$D.validateMonth=function(value){return $D._validate(value,0,11,"month");};$D.validateYear=function(value){return $D._validate(value,0,9999,"year");};$P.set=function(config){if($D.validateMillisecond(config.millisecond)){this.addMilliseconds(config.millisecond-this.getMilliseconds());}
if($D.validateSecond(config.second)){this.addSeconds(config.second-this.getSeconds());}
if($D.validateMinute(config.minute)){this.addMinutes(config.minute-this.getMinutes());}
if($D.validateHour(config.hour)){this.addHours(config.hour-this.getHours());}
if($D.validateMonth(config.month)){this.addMonths(config.month-this.getMonth());}
if($D.validateYear(config.year)){this.addYears(config.year-this.getFullYear());}
if($D.validateDay(config.day,this.getFullYear(),this.getMonth())){this.addDays(config.day-this.getDate());}
if(config.timezone){this.setTimezone(config.timezone);}
if(config.timezoneOffset){this.setTimezoneOffset(config.timezoneOffset);}
if(config.week&&$D._validate(config.week,0,53,"week")){this.setWeek(config.week);}
return this;};$P.moveToFirstDayOfMonth=function(){return this.set({day:1});};$P.moveToLastDayOfMonth=function(){return this.set({day:$D.getDaysInMonth(this.getFullYear(),this.getMonth())});};$P.moveToNthOccurrence=function(dayOfWeek,occurrence){var shift=0;if(occurrence>0){shift=occurrence-1;}
else if(occurrence===-1){this.moveToLastDayOfMonth();if(this.getDay()!==dayOfWeek){this.moveToDayOfWeek(dayOfWeek,-1);}
return this;}
return this.moveToFirstDayOfMonth().addDays(-1).moveToDayOfWeek(dayOfWeek,+1).addWeeks(shift);};$P.moveToDayOfWeek=function(dayOfWeek,orient){var diff=(dayOfWeek-this.getDay()+7*(orient||+1))%7;return this.addDays((diff===0)?diff+=7*(orient||+1):diff);};$P.moveToMonth=function(month,orient){var diff=(month-this.getMonth()+12*(orient||+1))%12;return this.addMonths((diff===0)?diff+=12*(orient||+1):diff);};$P.getOrdinalNumber=function(){return Math.ceil((this.clone().clearTime()-new Date(this.getFullYear(),0,1))/86400000)+1;};$P.getTimezone=function(){return $D.getTimezoneAbbreviation(this.getUTCOffset());};$P.setTimezoneOffset=function(offset){var here=this.getTimezoneOffset(),there=Number(offset)*-6/10;return this.addMinutes(there-here);};$P.setTimezone=function(offset){return this.setTimezoneOffset($D.getTimezoneOffset(offset));};$P.hasDaylightSavingTime=function(){return(Date.today().set({month:0,day:1}).getTimezoneOffset()!==Date.today().set({month:6,day:1}).getTimezoneOffset());};$P.isDaylightSavingTime=function(){return(this.hasDaylightSavingTime()&&new Date().getTimezoneOffset()===Date.today().set({month:6,day:1}).getTimezoneOffset());};$P.getUTCOffset=function(){var n=this.getTimezoneOffset()*-10/6,r;if(n<0){r=(n-10000).toString();return r.charAt(0)+r.substr(2);}else{r=(n+10000).toString();return"+"+r.substr(1);}};$P.getElapsed=function(date){return(date||new Date())-this;};if(!$P.toISOString){$P.toISOString=function(){function f(n){return n<10?'0'+n:n;}
return'"'+this.getUTCFullYear()+'-'+
f(this.getUTCMonth()+1)+'-'+
f(this.getUTCDate())+'T'+
f(this.getUTCHours())+':'+
f(this.getUTCMinutes())+':'+
f(this.getUTCSeconds())+'Z"';};}
$P._toString=$P.toString;$P.toString=function(format){var x=this;if(format&&format.length==1){var c=$C.formatPatterns;x.t=x.toString;switch(format){case"d":return x.t(c.shortDate);case"D":return x.t(c.longDate);case"F":return x.t(c.fullDateTime);case"m":return x.t(c.monthDay);case"r":return x.t(c.rfc1123);case"s":return x.t(c.sortableDateTime);case"t":return x.t(c.shortTime);case"T":return x.t(c.longTime);case"u":return x.t(c.universalSortableDateTime);case"y":return x.t(c.yearMonth);}}
var ord=function(n){switch(n*1){case 1:case 21:case 31:return"st";case 2:case 22:return"nd";case 3:case 23:return"rd";default:return"th";}};return format?format.replace(/(\\)?(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|S)/g,function(m){if(m.charAt(0)==="\\"){return m.replace("\\","");}
x.h=x.getHours;switch(m){case"hh":return p(x.h()<13?(x.h()===0?12:x.h()):(x.h()-12));case"h":return x.h()<13?(x.h()===0?12:x.h()):(x.h()-12);case"HH":return p(x.h());case"H":return x.h();case"mm":return p(x.getMinutes());case"m":return x.getMinutes();case"ss":return p(x.getSeconds());case"s":return x.getSeconds();case"yyyy":return p(x.getFullYear(),4);case"yy":return p(x.getFullYear());case"dddd":return $C.dayNames[x.getDay()];case"ddd":return $C.abbreviatedDayNames[x.getDay()];case"dd":return p(x.getDate());case"d":return x.getDate();case"MMMM":return $C.monthNames[x.getMonth()];case"MMM":return $C.abbreviatedMonthNames[x.getMonth()];case"MM":return p((x.getMonth()+1));case"M":return x.getMonth()+1;case"t":return x.h()<12?$C.amDesignator.substring(0,1):$C.pmDesignator.substring(0,1);case"tt":return x.h()<12?$C.amDesignator:$C.pmDesignator;case"S":return ord(x.getDate());default:return m;}}):this._toString();};}());
(function(){var $D=Date,$P=$D.prototype,$C=$D.CultureInfo,$N=Number.prototype;$P._orient=+1;$P._nth=null;$P._is=false;$P._same=false;$P._isSecond=false;$N._dateElement="day";$P.next=function(){this._orient=+1;return this;};$D.next=function(){return $D.today().next();};$P.last=$P.prev=$P.previous=function(){this._orient=-1;return this;};$D.last=$D.prev=$D.previous=function(){return $D.today().last();};$P.is=function(){this._is=true;return this;};$P.same=function(){this._same=true;this._isSecond=false;return this;};$P.today=function(){return this.same().day();};$P.weekday=function(){if(this._is){this._is=false;return(!this.is().sat()&&!this.is().sun());}
return false;};$P.at=function(time){return(typeof time==="string")?$D.parse(this.toString("d")+" "+time):this.set(time);};$N.fromNow=$N.after=function(date){var c={};c[this._dateElement]=this;return((!date)?new Date():date.clone()).add(c);};$N.ago=$N.before=function(date){var c={};c[this._dateElement]=this*-1;return((!date)?new Date():date.clone()).add(c);};var dx=("sunday monday tuesday wednesday thursday friday saturday").split(/\s/),mx=("january february march april may june july august september october november december").split(/\s/),px=("Millisecond Second Minute Hour Day Week Month Year").split(/\s/),pxf=("Milliseconds Seconds Minutes Hours Date Week Month FullYear").split(/\s/),nth=("final first second third fourth fifth").split(/\s/),de;$P.toObject=function(){var o={};for(var i=0;i<px.length;i++){o[px[i].toLowerCase()]=this["get"+pxf[i]]();}
return o;};$D.fromObject=function(config){config.week=null;return Date.today().set(config);};var df=function(n){return function(){if(this._is){this._is=false;return this.getDay()==n;}
if(this._nth!==null){if(this._isSecond){this.addSeconds(this._orient*-1);}
this._isSecond=false;var ntemp=this._nth;this._nth=null;var temp=this.clone().moveToLastDayOfMonth();this.moveToNthOccurrence(n,ntemp);if(this>temp){throw new RangeError($D.getDayName(n)+" does not occur "+ntemp+" times in the month of "+$D.getMonthName(temp.getMonth())+" "+temp.getFullYear()+".");}
return this;}
return this.moveToDayOfWeek(n,this._orient);};};var sdf=function(n){return function(){var t=$D.today(),shift=n-t.getDay();if(n===0&&$C.firstDayOfWeek===1&&t.getDay()!==0){shift=shift+7;}
return t.addDays(shift);};};for(var i=0;i<dx.length;i++){$D[dx[i].toUpperCase()]=$D[dx[i].toUpperCase().substring(0,3)]=i;$D[dx[i]]=$D[dx[i].substring(0,3)]=sdf(i);$P[dx[i]]=$P[dx[i].substring(0,3)]=df(i);}
var mf=function(n){return function(){if(this._is){this._is=false;return this.getMonth()===n;}
return this.moveToMonth(n,this._orient);};};var smf=function(n){return function(){return $D.today().set({month:n,day:1});};};for(var j=0;j<mx.length;j++){$D[mx[j].toUpperCase()]=$D[mx[j].toUpperCase().substring(0,3)]=j;$D[mx[j]]=$D[mx[j].substring(0,3)]=smf(j);$P[mx[j]]=$P[mx[j].substring(0,3)]=mf(j);}
var ef=function(j){return function(){if(this._isSecond){this._isSecond=false;return this;}
if(this._same){this._same=this._is=false;var o1=this.toObject(),o2=(arguments[0]||new Date()).toObject(),v="",k=j.toLowerCase();for(var m=(px.length-1);m>-1;m--){v=px[m].toLowerCase();if(o1[v]!=o2[v]){return false;}
if(k==v){break;}}
return true;}
if(j.substring(j.length-1)!="s"){j+="s";}
return this["add"+j](this._orient);};};var nf=function(n){return function(){this._dateElement=n;return this;};};for(var k=0;k<px.length;k++){de=px[k].toLowerCase();$P[de]=$P[de+"s"]=ef(px[k]);$N[de]=$N[de+"s"]=nf(de);}
$P._ss=ef("Second");var nthfn=function(n){return function(dayOfWeek){if(this._same){return this._ss(arguments[0]);}
if(dayOfWeek||dayOfWeek===0){return this.moveToNthOccurrence(dayOfWeek,n);}
this._nth=n;if(n===2&&(dayOfWeek===undefined||dayOfWeek===null)){this._isSecond=true;return this.addSeconds(this._orient);}
return this;};};for(var l=0;l<nth.length;l++){$P[nth[l]]=(l===0)?nthfn(-1):nthfn(l);}}());
(function(){Date.Parsing={Exception:function(s){this.message="Parse error at '"+s.substring(0,10)+" ...'";}};var $P=Date.Parsing;var _=$P.Operators={rtoken:function(r){return function(s){var mx=s.match(r);if(mx){return([mx[0],s.substring(mx[0].length)]);}else{throw new $P.Exception(s);}};},token:function(s){return function(s){return _.rtoken(new RegExp("^\s*"+s+"\s*"))(s);};},stoken:function(s){return _.rtoken(new RegExp("^"+s));},until:function(p){return function(s){var qx=[],rx=null;while(s.length){try{rx=p.call(this,s);}catch(e){qx.push(rx[0]);s=rx[1];continue;}
break;}
return[qx,s];};},many:function(p){return function(s){var rx=[],r=null;while(s.length){try{r=p.call(this,s);}catch(e){return[rx,s];}
rx.push(r[0]);s=r[1];}
return[rx,s];};},optional:function(p){return function(s){var r=null;try{r=p.call(this,s);}catch(e){return[null,s];}
return[r[0],r[1]];};},not:function(p){return function(s){try{p.call(this,s);}catch(e){return[null,s];}
throw new $P.Exception(s);};},ignore:function(p){return p?function(s){var r=null;r=p.call(this,s);return[null,r[1]];}:null;},product:function(){var px=arguments[0],qx=Array.prototype.slice.call(arguments,1),rx=[];for(var i=0;i<px.length;i++){rx.push(_.each(px[i],qx));}
return rx;},cache:function(rule){var cache={},r=null;return function(s){try{r=cache[s]=(cache[s]||rule.call(this,s));}catch(e){r=cache[s]=e;}
if(r instanceof $P.Exception){throw r;}else{return r;}};},any:function(){var px=arguments;return function(s){var r=null;for(var i=0;i<px.length;i++){if(px[i]==null){continue;}
try{r=(px[i].call(this,s));}catch(e){r=null;}
if(r){return r;}}
throw new $P.Exception(s);};},each:function(){var px=arguments;return function(s){var rx=[],r=null;for(var i=0;i<px.length;i++){if(px[i]==null){continue;}
try{r=(px[i].call(this,s));}catch(e){throw new $P.Exception(s);}
rx.push(r[0]);s=r[1];}
return[rx,s];};},all:function(){var px=arguments,_=_;return _.each(_.optional(px));},sequence:function(px,d,c){d=d||_.rtoken(/^\s*/);c=c||null;if(px.length==1){return px[0];}
return function(s){var r=null,q=null;var rx=[];for(var i=0;i<px.length;i++){try{r=px[i].call(this,s);}catch(e){break;}
rx.push(r[0]);try{q=d.call(this,r[1]);}catch(ex){q=null;break;}
s=q[1];}
if(!r){throw new $P.Exception(s);}
if(q){throw new $P.Exception(q[1]);}
if(c){try{r=c.call(this,r[1]);}catch(ey){throw new $P.Exception(r[1]);}}
return[rx,(r?r[1]:s)];};},between:function(d1,p,d2){d2=d2||d1;var _fn=_.each(_.ignore(d1),p,_.ignore(d2));return function(s){var rx=_fn.call(this,s);return[[rx[0][0],r[0][2]],rx[1]];};},list:function(p,d,c){d=d||_.rtoken(/^\s*/);c=c||null;return(p instanceof Array?_.each(_.product(p.slice(0,-1),_.ignore(d)),p.slice(-1),_.ignore(c)):_.each(_.many(_.each(p,_.ignore(d))),px,_.ignore(c)));},set:function(px,d,c){d=d||_.rtoken(/^\s*/);c=c||null;return function(s){var r=null,p=null,q=null,rx=null,best=[[],s],last=false;for(var i=0;i<px.length;i++){q=null;p=null;r=null;last=(px.length==1);try{r=px[i].call(this,s);}catch(e){continue;}
rx=[[r[0]],r[1]];if(r[1].length>0&&!last){try{q=d.call(this,r[1]);}catch(ex){last=true;}}else{last=true;}
if(!last&&q[1].length===0){last=true;}
if(!last){var qx=[];for(var j=0;j<px.length;j++){if(i!=j){qx.push(px[j]);}}
p=_.set(qx,d).call(this,q[1]);if(p[0].length>0){rx[0]=rx[0].concat(p[0]);rx[1]=p[1];}}
if(rx[1].length<best[1].length){best=rx;}
if(best[1].length===0){break;}}
if(best[0].length===0){return best;}
if(c){try{q=c.call(this,best[1]);}catch(ey){throw new $P.Exception(best[1]);}
best[1]=q[1];}
return best;};},forward:function(gr,fname){return function(s){return gr[fname].call(this,s);};},replace:function(rule,repl){return function(s){var r=rule.call(this,s);return[repl,r[1]];};},process:function(rule,fn){return function(s){var r=rule.call(this,s);return[fn.call(this,r[0]),r[1]];};},min:function(min,rule){return function(s){var rx=rule.call(this,s);if(rx[0].length<min){throw new $P.Exception(s);}
return rx;};}};var _generator=function(op){return function(){var args=null,rx=[];if(arguments.length>1){args=Array.prototype.slice.call(arguments);}else if(arguments[0]instanceof Array){args=arguments[0];}
if(args){for(var i=0,px=args.shift();i<px.length;i++){args.unshift(px[i]);rx.push(op.apply(null,args));args.shift();return rx;}}else{return op.apply(null,arguments);}};};var gx="optional not ignore cache".split(/\s/);for(var i=0;i<gx.length;i++){_[gx[i]]=_generator(_[gx[i]]);}
var _vector=function(op){return function(){if(arguments[0]instanceof Array){return op.apply(null,arguments[0]);}else{return op.apply(null,arguments);}};};var vx="each any all".split(/\s/);for(var j=0;j<vx.length;j++){_[vx[j]]=_vector(_[vx[j]]);}}());(function(){var $D=Date,$P=$D.prototype,$C=$D.CultureInfo;var flattenAndCompact=function(ax){var rx=[];for(var i=0;i<ax.length;i++){if(ax[i]instanceof Array){rx=rx.concat(flattenAndCompact(ax[i]));}else{if(ax[i]){rx.push(ax[i]);}}}
return rx;};$D.Grammar={};$D.Translator={hour:function(s){return function(){this.hour=Number(s);};},minute:function(s){return function(){this.minute=Number(s);};},second:function(s){return function(){this.second=Number(s);};},meridian:function(s){return function(){this.meridian=s.slice(0,1).toLowerCase();};},timezone:function(s){return function(){var n=s.replace(/[^\d\+\-]/g,"");if(n.length){this.timezoneOffset=Number(n);}else{this.timezone=s.toLowerCase();}};},day:function(x){var s=x[0];return function(){this.day=Number(s.match(/\d+/)[0]);};},month:function(s){return function(){this.month=(s.length==3)?"jan feb mar apr may jun jul aug sep oct nov dec".indexOf(s)/4:Number(s)-1;};},year:function(s){return function(){var n=Number(s);this.year=((s.length>2)?n:(n+(((n+2000)<$C.twoDigitYearMax)?2000:1900)));};},rday:function(s){return function(){switch(s){case"yesterday":this.days=-1;break;case"tomorrow":this.days=1;break;case"today":this.days=0;break;case"now":this.days=0;this.now=true;break;}};},finishExact:function(x){x=(x instanceof Array)?x:[x];for(var i=0;i<x.length;i++){if(x[i]){x[i].call(this);}}
var now=new Date();if((this.hour||this.minute)&&(!this.month&&!this.year&&!this.day)){this.day=now.getDate();}
if(!this.year){this.year=now.getFullYear();}
if(!this.month&&this.month!==0){this.month=now.getMonth();}
if(!this.day){this.day=1;}
if(!this.hour){this.hour=0;}
if(!this.minute){this.minute=0;}
if(!this.second){this.second=0;}
if(this.meridian&&this.hour){if(this.meridian=="p"&&this.hour<12){this.hour=this.hour+12;}else if(this.meridian=="a"&&this.hour==12){this.hour=0;}}
if(this.day>$D.getDaysInMonth(this.year,this.month)){throw new RangeError(this.day+" is not a valid value for days.");}
var r=new Date(this.year,this.month,this.day,this.hour,this.minute,this.second);if(this.timezone){r.set({timezone:this.timezone});}else if(this.timezoneOffset){r.set({timezoneOffset:this.timezoneOffset});}
return r;},finish:function(x){x=(x instanceof Array)?flattenAndCompact(x):[x];if(x.length===0){return null;}
for(var i=0;i<x.length;i++){if(typeof x[i]=="function"){x[i].call(this);}}
var today=$D.today();if(this.now&&!this.unit&&!this.operator){return new Date();}else if(this.now){today=new Date();}
var expression=!!(this.days&&this.days!==null||this.orient||this.operator);var gap,mod,orient;orient=((this.orient=="past"||this.operator=="subtract")?-1:1);if(!this.now&&"hour minute second".indexOf(this.unit)!=-1){today.setTimeToNow();}
if(this.month||this.month===0){if("year day hour minute second".indexOf(this.unit)!=-1){this.value=this.month+1;this.month=null;expression=true;}}
if(!expression&&this.weekday&&!this.day&&!this.days){var temp=Date[this.weekday]();this.day=temp.getDate();if(!this.month){this.month=temp.getMonth();}
this.year=temp.getFullYear();}
if(expression&&this.weekday&&this.unit!="month"){this.unit="day";gap=($D.getDayNumberFromName(this.weekday)-today.getDay());mod=7;this.days=gap?((gap+(orient*mod))%mod):(orient*mod);}
if(this.month&&this.unit=="day"&&this.operator){this.value=(this.month+1);this.month=null;}
if(this.value!=null&&this.month!=null&&this.year!=null){this.day=this.value*1;}
if(this.month&&!this.day&&this.value){today.set({day:this.value*1});if(!expression){this.day=this.value*1;}}
if(!this.month&&this.value&&this.unit=="month"&&!this.now){this.month=this.value;expression=true;}
if(expression&&(this.month||this.month===0)&&this.unit!="year"){this.unit="month";gap=(this.month-today.getMonth());mod=12;this.months=gap?((gap+(orient*mod))%mod):(orient*mod);this.month=null;}
if(!this.unit){this.unit="day";}
if(!this.value&&this.operator&&this.operator!==null&&this[this.unit+"s"]&&this[this.unit+"s"]!==null){this[this.unit+"s"]=this[this.unit+"s"]+((this.operator=="add")?1:-1)+(this.value||0)*orient;}else if(this[this.unit+"s"]==null||this.operator!=null){if(!this.value){this.value=1;}
this[this.unit+"s"]=this.value*orient;}
if(this.meridian&&this.hour){if(this.meridian=="p"&&this.hour<12){this.hour=this.hour+12;}else if(this.meridian=="a"&&this.hour==12){this.hour=0;}}
if(this.weekday&&!this.day&&!this.days){var temp=Date[this.weekday]();this.day=temp.getDate();if(temp.getMonth()!==today.getMonth()){this.month=temp.getMonth();}}
if((this.month||this.month===0)&&!this.day){this.day=1;}
if(!this.orient&&!this.operator&&this.unit=="week"&&this.value&&!this.day&&!this.month){return Date.today().setWeek(this.value);}
if(expression&&this.timezone&&this.day&&this.days){this.day=this.days;}
return(expression)?today.add(this):today.set(this);}};var _=$D.Parsing.Operators,g=$D.Grammar,t=$D.Translator,_fn;g.datePartDelimiter=_.rtoken(/^([\s\-\.\,\/\x27]+)/);g.timePartDelimiter=_.stoken(":");g.whiteSpace=_.rtoken(/^\s*/);g.generalDelimiter=_.rtoken(/^(([\s\,]|at|@|on)+)/);var _C={};g.ctoken=function(keys){var fn=_C[keys];if(!fn){var c=$C.regexPatterns;var kx=keys.split(/\s+/),px=[];for(var i=0;i<kx.length;i++){px.push(_.replace(_.rtoken(c[kx[i]]),kx[i]));}
fn=_C[keys]=_.any.apply(null,px);}
return fn;};g.ctoken2=function(key){return _.rtoken($C.regexPatterns[key]);};g.h=_.cache(_.process(_.rtoken(/^(0[0-9]|1[0-2]|[1-9])/),t.hour));g.hh=_.cache(_.process(_.rtoken(/^(0[0-9]|1[0-2])/),t.hour));g.H=_.cache(_.process(_.rtoken(/^([0-1][0-9]|2[0-3]|[0-9])/),t.hour));g.HH=_.cache(_.process(_.rtoken(/^([0-1][0-9]|2[0-3])/),t.hour));g.m=_.cache(_.process(_.rtoken(/^([0-5][0-9]|[0-9])/),t.minute));g.mm=_.cache(_.process(_.rtoken(/^[0-5][0-9]/),t.minute));g.s=_.cache(_.process(_.rtoken(/^([0-5][0-9]|[0-9])/),t.second));g.ss=_.cache(_.process(_.rtoken(/^[0-5][0-9]/),t.second));g.hms=_.cache(_.sequence([g.H,g.m,g.s],g.timePartDelimiter));g.t=_.cache(_.process(g.ctoken2("shortMeridian"),t.meridian));g.tt=_.cache(_.process(g.ctoken2("longMeridian"),t.meridian));g.z=_.cache(_.process(_.rtoken(/^((\+|\-)\s*\d\d\d\d)|((\+|\-)\d\d\:?\d\d)/),t.timezone));g.zz=_.cache(_.process(_.rtoken(/^((\+|\-)\s*\d\d\d\d)|((\+|\-)\d\d\:?\d\d)/),t.timezone));g.zzz=_.cache(_.process(g.ctoken2("timezone"),t.timezone));g.timeSuffix=_.each(_.ignore(g.whiteSpace),_.set([g.tt,g.zzz]));g.time=_.each(_.optional(_.ignore(_.stoken("T"))),g.hms,g.timeSuffix);g.d=_.cache(_.process(_.each(_.rtoken(/^([0-2]\d|3[0-1]|\d)/),_.optional(g.ctoken2("ordinalSuffix"))),t.day));g.dd=_.cache(_.process(_.each(_.rtoken(/^([0-2]\d|3[0-1])/),_.optional(g.ctoken2("ordinalSuffix"))),t.day));g.ddd=g.dddd=_.cache(_.process(g.ctoken("sun mon tue wed thu fri sat"),function(s){return function(){this.weekday=s;};}));g.M=_.cache(_.process(_.rtoken(/^(1[0-2]|0\d|\d)/),t.month));g.MM=_.cache(_.process(_.rtoken(/^(1[0-2]|0\d)/),t.month));g.MMM=g.MMMM=_.cache(_.process(g.ctoken("jan feb mar apr may jun jul aug sep oct nov dec"),t.month));g.y=_.cache(_.process(_.rtoken(/^(\d\d?)/),t.year));g.yy=_.cache(_.process(_.rtoken(/^(\d\d)/),t.year));g.yyy=_.cache(_.process(_.rtoken(/^(\d\d?\d?\d?)/),t.year));g.yyyy=_.cache(_.process(_.rtoken(/^(\d\d\d\d)/),t.year));_fn=function(){return _.each(_.any.apply(null,arguments),_.not(g.ctoken2("timeContext")));};g.day=_fn(g.d,g.dd);g.month=_fn(g.M,g.MMM);g.year=_fn(g.yyyy,g.yy);g.orientation=_.process(g.ctoken("past future"),function(s){return function(){this.orient=s;};});g.operator=_.process(g.ctoken("add subtract"),function(s){return function(){this.operator=s;};});g.rday=_.process(g.ctoken("yesterday tomorrow today now"),t.rday);g.unit=_.process(g.ctoken("second minute hour day week month year"),function(s){return function(){this.unit=s;};});g.value=_.process(_.rtoken(/^\d\d?(st|nd|rd|th)?/),function(s){return function(){this.value=s.replace(/\D/g,"");};});g.expression=_.set([g.rday,g.operator,g.value,g.unit,g.orientation,g.ddd,g.MMM]);_fn=function(){return _.set(arguments,g.datePartDelimiter);};g.mdy=_fn(g.ddd,g.month,g.day,g.year);g.ymd=_fn(g.ddd,g.year,g.month,g.day);g.dmy=_fn(g.ddd,g.day,g.month,g.year);g.date=function(s){return((g[$C.dateElementOrder]||g.mdy).call(this,s));};g.format=_.process(_.many(_.any(_.process(_.rtoken(/^(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|zz?z?)/),function(fmt){if(g[fmt]){return g[fmt];}else{throw $D.Parsing.Exception(fmt);}}),_.process(_.rtoken(/^[^dMyhHmstz]+/),function(s){return _.ignore(_.stoken(s));}))),function(rules){return _.process(_.each.apply(null,rules),t.finishExact);});var _F={};var _get=function(f){return _F[f]=(_F[f]||g.format(f)[0]);};g.formats=function(fx){if(fx instanceof Array){var rx=[];for(var i=0;i<fx.length;i++){rx.push(_get(fx[i]));}
return _.any.apply(null,rx);}else{return _get(fx);}};g._formats=g.formats(["\"yyyy-MM-ddTHH:mm:ssZ\"","yyyy-MM-ddTHH:mm:ssZ","yyyy-MM-ddTHH:mm:ssz","yyyy-MM-ddTHH:mm:ss","yyyy-MM-ddTHH:mmZ","yyyy-MM-ddTHH:mmz","yyyy-MM-ddTHH:mm","ddd, MMM dd, yyyy H:mm:ss tt","ddd MMM d yyyy HH:mm:ss zzz","MMddyyyy","ddMMyyyy","Mddyyyy","ddMyyyy","Mdyyyy","dMyyyy","yyyy","Mdyy","dMyy","d"]);g._start=_.process(_.set([g.date,g.time,g.expression],g.generalDelimiter,g.whiteSpace),t.finish);g.start=function(s){try{var r=g._formats.call({},s);if(r[1].length===0){return r;}}catch(e){}
return g._start.call({},s);};$D._parse=$D.parse;$D.parse=function(s){var r=null;if(!s){return null;}
if(s instanceof Date){return s;}
try{r=$D.Grammar.start.call({},s.replace(/^\s*(\S*(\s+\S+)*)\s*$/,"$1"));}catch(e){return null;}
return((r[1].length===0)?r[0]:null);};$D.getParseFunction=function(fx){var fn=$D.Grammar.formats(fx);return function(s){var r=null;try{r=fn.call({},s);}catch(e){return null;}
return((r[1].length===0)?r[0]:null);};};$D.parseExact=function(s,fx){return $D.getParseFunction(fx)(s);};}());
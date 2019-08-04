<!DOCTYPE html>
<html>
    <head>
        <title>Таблица экскурсий</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <script src="js/date-ru-RU.js"></script>
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>
        <script
          src="https://code.jquery.com/jquery-3.4.1.min.js"
          integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
          crossorigin="anonymous"></script>
    </head>
    <body>
        <style>
            .block{
                border-left: 20px solid #000;
                padding-left: 20px;
                margin-top: 5px;
            }
            .block.green{
                border-color: #a8ffa7;
            }
            .block.red{
                border-color: #ffc645;
            }
            .block.yellow{
                border-color: #ffb9b9;
            }

            .legend{
                border-bottom: 1px solid #333;
                padding-bottom: 10px;
            }

            .uk-table tbody tr.green{
                background: #a8ffa7;
            }
            .uk-table tbody tr.red{
                background: #ffc645;
            }
            .uk-table tbody tr.yellow{
                background: #ffb9b9;
            }
        </style>
        <div class="legend">
            <div class="block green">Экскурсия собрана</div>
            <div class="block red">До полного сбора осталось менее 4х человек, приоритет на сбор</div>
            <div class="block yellow">До экскурсии менее 3х дней и она собрана менее чем на 50%</div>
        </div>
        <input type="text" placeholder="Поиск" class="uk-input uk-width-1-1" id="search">
        <table class="uk-table uk-table-striped">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Дата</th>
                    <th>Время</th>
                    <th>Возраст</th>
                    <th>Вместимость детей</th>
                    <th>Вместимость взрослых</th>
                    <th>Остаток детей</th>
                    <th>Остаток взрослых</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script>
            $(document).ready(function(){
                $.get('/order/list.php',{},function(data){
                    console.log(data);
                    $('table tbody').html('');
                    for(var i in data)
                    {
                        for(var t in data[i].custom_fields)
                            data[i]['cf_'+data[i].custom_fields[t].name] = data[i].custom_fields[t].values[0].value;
                    }
                    data.sort(function(a,b){
                        if(Date.parse(a['cf_Дата']+' '+a['cf_Время экскурсии']).getTime()>Date.parse(b['cf_Дата']+' '+b['cf_Время экскурсии']).getTime())
                            return 1;
                        else
                            return -1;
                    })
                    for(var i in data)
                    {
                        if(data[i]['cf_Вместимость детей']==undefined)
                            data[i]['cf_Вместимость детей'] = '';
                        if(data[i]['cf_Вместимость взрослых']==undefined)
                            data[i]['cf_Вместимость взрослых'] = '';
                        if(data[i]['cf_Осталось мест(детей)']==undefined)
                            data[i]['cf_Осталось мест(детей)'] = '';
                        if(data[i]['cf_Осталось мест(взрослых)']==undefined)
                            data[i]['cf_Осталось мест(взрослых)'] = '';
                        if(data[i]['cf_Возраст(от-до)']==undefined)
                            data[i]['cf_Возраст(от-до)'] = '';

                        var cl = '';

                        if(parseInt(data[i]['cf_Осталось мест(детей)'])<=4)
                            cl = 'red';

                        if(parseInt(data[i]['cf_Осталось мест(детей)'])==0)
                            cl = 'green';

                        if((parseInt(data[i]['cf_Вместимость детей'])-parseInt(data[i]['cf_Осталось мест(детей)']))/parseInt(data[i]['cf_Вместимость детей'])<0.5)
                            if(Date.parse(data[i]['cf_Дата']+' '+data[i]['cf_Время экскурсии'])-Date.today()<1000*3600*24*3)
                                cl = 'yellow';

                        $('table tbody').append("<tr class='"+cl+"'><td>"+data[i].name+"</td><td>"+data[i]['cf_Дата']+"</td><td>"+data[i]['cf_Время экскурсии']+"</td><td>"+data[i]['cf_Возраст(от-до)']+"</td><td>"+data[i]['cf_Вместимость детей']+"</td><td>"+data[i]['cf_Вместимость взрослых']+"</td><td>"+data[i]['cf_Осталось мест(детей)']+"</td><td>"+data[i]['cf_Осталось мест(взрослых)']+"</td></tr>");
                    }
                    search();
                },'json')

                $('#search').keyup(search);
            })

            function search()
            {
                var v = $('#search').val().toLowerCase();
                $('table tbody tr').hide();
                if(v=='')
                {
                    $('table tbody tr').show();
                    return false;
                }

                $('table tbody tr').each(function(){
                    if($(this).html().toLowerCase().indexOf(v)+1)
                        $(this).show();
                })
            }
        </script>
    </body>
</html>
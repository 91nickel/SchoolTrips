<?
header('Access-Control-Allow-Origin: *'); 
?>
<script>
var kad_nova = {};

kad_nova.fori=0;
kad_nova.fori2=0;

kad_nova.data = [];
kad_nova.data_leads = [];

kad_nova.inter;
kad_nova.inter2;

kad_nova.start = function(){
	if(kad_nova.inter==undefined)
		kad_nova.inter = setInterval(kad_nova.start_merge,20000);
	kad_nova.start_merge();
}

kad_nova.start_merge = function(){
	$.get('/api/v2/contacts',{query:'удалить'},function(data){
		var ids = [];
		if(data==undefined)
			return false;
		for(var i in data._embedded.items)
			for(var t in data._embedded.items[i].tags)
				if(data._embedded.items[i].tags[t].name=='удалить')
					ids.push(data._embedded.items[i].id);
		console.log(ids);
		$.post('/ajax/v1/multiactions/set',{request:{multiactions:{add:[{entity_type:1,ids:ids,multiaction_type:4,data:{data:{ACTION:'DELETE'}}}]}}},function(data){console.log(data)})
	})
}

function showPreloaderNova()
{
	$('body').append('<div class="nova_preloader"><div class="text">Сохранение...</div></div>');
}

function hidePreloaderNova()
{
	$('.nova_preloader').hide();
}
</script>

<div class="kad_nova"></div>

<style>
	.nova_preloader{
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(255,255,255,.5);
		z-index: 9999999999999;
	}

	.nova_preloader .text{
		font-size: 40px;
		color: #333;
		text-align: center;
		position: absolute;
		width: 100%;
		left: 0;
		top: 50%;
	}

	/*#merge_frame2{
		display: block!important;
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 10000;
	}*/
</style>
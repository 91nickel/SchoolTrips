<?php
/**
 * Copyright 2018, Atnagulov Ruslan
 * atnagulov.r@gmail.com
 * Date: 14.12.2018
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
?>


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
	(function (m, e, t, r, i, k, a) {
		m[i] = m[i] || function () {
					(m[i].a = m[i].a || []).push(arguments)
				};
		m[i].l = 1 * new Date();
		k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
	})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	ym(39632640, "init", {
		id: 39632640,
		clickmap: true,
		trackLinks: true,
		accurateTrackBounce: true,
		webvisor: true
	});
</script>
<noscript>
	<div><img src="https://mc.yandex.ru/watch/39632640" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

<!--Счетчик карамели-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
	(function (m, e, t, r, i, k, a) {
		m[i] = m[i] || function () {
					(m[i].a = m[i].a || []).push(arguments)
				};
		m[i].l = 1 * new Date();
		k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
	})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	ym(46789143, "init", {
		id: 46789143,
		clickmap: true,
		trackLinks: true,
		accurateTrackBounce: true,
		webvisor: true,
		trackHash: true
	});
</script>
<noscript>
	<div><img src="https://mc.yandex.ru/watch/46789143" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

<script>
	(function (w, d, s, h, id) {
		w.roistatProjectId = id;
		w.roistatHost = h;
		var p = d.location.protocol == "https:" ? "https://" : "http://";
		var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init";
		var js = d.createElement(s);
		js.charset = "UTF-8";
		js.async = 1;
		js.src = p + h + u;
		var js2 = d.getElementsByTagName(s)[0];
		js2.parentNode.insertBefore(js, js2);
	})(window, document, 'script', 'cloud.roistat.com', 'edb0dcbb806ca8e8a872b99c4c78a84e');
</script>

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
	(function () {
		var widget_id = 'ASFOa0aJOy';
		var d = document;
		var w = window;

		function l() {
			var s = document.createElement('script');
			s.type = 'text/javascript';
			s.async = true;
			s.src = '//code.jivosite.com/script/widget/' + widget_id;
			var ss = document.getElementsByTagName('script')[0];
			ss.parentNode.insertBefore(s, ss);
		}

		if (d.readyState == 'complete') {
			l();
		} else {
			if (w.attachEvent) {
				w.attachEvent('onload', l);
			} else {
				w.addEventListener('load', l, false);
			}
		}
	})();
</script>
<!-- {/literal} END JIVOSITE CODE -->
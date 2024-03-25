# Сортировка материалов по дополнительным полям Joomla
## Плагин берет значение дополнительного поля и записывает в поле ordering материала. Для сортировки по ordering в пункте меню выставляем "Порядок материалов" в значение "Порядок материалов" или "материалы в обратном порядке"

Копируем этот код кнопки в нужное место (например, в шаблон нашего плагина)
&lt;?
if ($this-&gt;item-&gt;element === &quot;sortingfields&quot;) {?&gt;
&lt;p&gt;&lt;button class=&quot;sendSort btn btn-warning&quot; type=&quot;button&quot;&gt;Отсортировать&lt;/button&gt;&lt;/p&gt;
&lt;dialog id=&quot;success&quot; class=&quot;text-center&quot;&gt;
&lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot; onclick=&quot;window.success.close()&quot;&gt;Закрыть&lt;/button&gt;
&lt;/dialog&gt;
&lt;dialog id=&quot;errorSort&quot; class=&quot;text-center&quot;&gt;
&lt;p&gt;Ошибка сортировки&lt;/p&gt;
&lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot; onclick=&quot;window.errorSort.close()&quot;&gt;Закрыть&lt;/button&gt;
&lt;/dialog&gt;
&lt;script&gt;
jQuery(document).ready(function($){
jQuery(&quot;.sendSort&quot;).on(&quot;click&quot;, function(){
jQuery.ajax({
type: &quot;GET&quot;,
url: &quot;index.php?option=com_ajax&amp;plugin=sortingfields&amp;group=content&amp;format=json&amp;send=1&amp;categorysorting=&lt;?=$this-&gt;item-&gt;params[&apos;categorysorting&apos;]?&gt;&amp;idfield=&lt;?=$this-&gt;item-&gt;params[&apos;idfield&apos;]?&gt;&quot;,
success: function(data, status) {
let success = document.getElementById(&apos;success&apos;);
let $p1 = document.createElement(&apos;p&apos;);
$p1.textContent = &quot;Сортировка прошла успешно&quot;;
success.prepend($p1);

window.success.showModal();
},
error: function(data, status) {
window.errorSort.showModal();
}
});
});
});
&lt;/script&gt;
&lt;? } ?&gt;

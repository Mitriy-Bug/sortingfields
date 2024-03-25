# Сортировка материалов по дополнительным полям Joomla
## Плагин берет значение дополнительного поля и записывает в поле ordering материала. Для сортировки по ordering в пункте меню выставляем "Порядок материалов" в значение "Порядок материалов" или "материалы в обратном порядке"

Копируем этот код кнопки в нужное место (например, в шаблон нашего плагина)
```
<?
if ($this->item->element === "sortingfields") {?>
<p><button class="sendSort btn btn-warning" type="button">Отсортировать</button></p>
<dialog id="success" class="text-center">
<button type="button" class="btn btn-success" onclick="window.success.close()">Закрыть</button>
</dialog>
<dialog id="errorSort" class="text-center">
<p>Ошибка сортировки</p>
<button type="button" class="btn btn-warning" onclick="window.errorSort.close()">Закрыть</button>
</dialog>
<script>
jQuery(document).ready(function($){
jQuery(".sendSort").on("click", function(){
jQuery.ajax({
type: "GET",
url: "index.php?option=com_ajax&plugin=sortingfields&group=content&format=json&send=1&categorysorting=<?=$this->item->params['categorysorting']?>&idfield=<?=$this->item->params['idfield']?>",
success: function(data, status) {
let success = document.getElementById('success');
let $p1 = document.createElement('p');
$p1.textContent = "Сортировка прошла успешно";
success.prepend($p1);

window.success.showModal();
},
error: function(data, status) {
window.errorSort.showModal();
}
});
});
});
</script>
<? } ?>
```

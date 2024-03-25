<?php
/**
 * @package    Sortingfields
 * @author     Dmitry Denisov <info@codersite.ru>
 * @copyright  (C) 2024 codersite.ru. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
/**
 * Base plugin class.
 *
 * @since  1.0
 */
/**
 * Копируем этот код кнопи в нужное место (например, в шаблон нашего плагина)
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
*/
class PlgContentSortingfields extends CMSPlugin
{
    public function onAjaxSortingfields()
    {
        $app = Factory::getApplication();
        if ($app->input->getVar("send", "") === "1") {
            //Подключились к базе данных
            $db = Factory::getContainer()->get('DatabaseDriver');
            //Сразу ставим всем автосалонам ordering - 0
            $objectAllSalons = (object)[
                'catid' => $app->input->getVar("categorysorting"),
                'ordering' => 0,
            ];
            $db->updateObject('#__content', $objectAllSalons, 'catid');
            // Потом собираем все автосалоны с выставленным рейтингом и выставляем поле Ordering согласно ему
            $query = $db->getQuery(true);
            $query->select($db->quoteName('id'));
            $query->from($db->quoteName('#__content'));
            $query->where($db->quoteName('catid') . " = ".$app->input->getVar("categorysorting"));
            $db->setQuery($query);

            $idAll = $db->loadColumn(); // Массив ID автосалонов

            foreach ($idAll as $idSalon) {
                //Проверяем наличие итогового рейтинга. Если есть - обновляем ordering
                $query = $db->getQuery(true);
                $query->select($db->quoteName(['value']));
                $query->from($db->quoteName('#__fields_values'));
                $query->where($db->quoteName('item_id') . " = " . $idSalon);
                $query->where($db->quoteName('field_id') . " = ".$app->input->getVar("idfield")); //id пользовательского поля
                $db->setQuery($query);

                $rating = $db->loadResult(); // Значение поля с рейтингом Автосалона

                if (isset($rating)) {
                    $order = round(($rating * 10), 0); //умножаем на 10, чтобы ordering был более точным
                    $orderSalon = (object)[
                        'id' => $idSalon,
                        'ordering' => $order,
                    ];
                    $db->updateObject('#__content', $orderSalon, 'id');
                }
            }
        }
    }
}

<?
/* 
удобный вывод данных на экран
можно засунуть в init.php
из удобств:
- визуальное оформление (в табличке с цветной рамкой и цветным шрифтом - цвет можно менять в параметрах вызова)
- добавление заголовка, например, debmes($arResult, 'arResult');
- показ только для админа
*/
if ( !function_exists("debmes") ) {
    function debmes($message, $title = false, $color = "#008B8B")
    {
        global $USER;
        if (!$USER->IsAdmin()) {
            return "";
        }

        //if($_SERVER['REMOTE_ADDR']!="46.228.105.160") return "";

        echo '<table border="0" cellpadding="5" cellspacing="0" style="border:1px solid ' . $color . ';margin:2px;"><tr><td>';
        $textStyles = 'style="color: ' . $color . ';font-size:11px;font-family:Verdana;"';
        if (strlen($title) > 0) {
            echo '<p ' . $textStyles . '>[' . $title . ']</p>';
        }

        if (is_array($message) || is_object($message)) {
            echo '<pre ' . $textStyles . '>', print_r($message, 1), '</pre>';
        } else {
            echo '<p ' . $textStyles . '>' . $message . '</p>';
        }

        echo '</td></tr></table>';
    }
}
?>
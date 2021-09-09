<?php

class ViewUtil {

    private static $_SELECTED = "selected";
    private static $_CHECKED = "checked";
    private static $_OK = "X";

    public static function fillFieldSelectMultiple($valores, $item) {
        return ViewUtil::fillValues($valores, $item, ViewUtil::$_SELECTED);
    }

    public static function fillFieldCheckMultiple($valores, $item) {
        return ViewUtil::fillValues($valores, $item, ViewUtil::$_CHECKED);
    }

    public static function fillFieldCheckMultipleScreen($valores, $item) {
        return ViewUtil::fillValuesScreen($valores, $item, ViewUtil::$_OK);
    }

    public static function fillFieldSelect($valor, $item) {
        return ViewUtil::fillValue($valor, $item, ViewUtil::$_SELECTED);
    }

    public static function fillFieldCheck($valor, $item) {
        return ViewUtil::fillValue($valor, $item, ViewUtil::$_CHECKED);
    }

    private static function fillValuesScreen($valores, $item, $text_result) {
        if (isset($valores)) {
            foreach ($valores as $value) {
                if ($value == $item) {
                    return $text_result;
                }
            }
        }

        return "&nbsp;&nbsp";
    }

    private static function fillValues($valores, $item, $text_result) {
        if (isset($valores)) {
            foreach ($valores as $value) {
                if ($value == $item) {
                    return $text_result;
                }
            }
        }

        return "";
    }
    
     public static function fillValueZero($valor) {
        if (isset($valor) && $valor > 0) {
            return $valor;
        }

        return "";
    }

    private static function fillValue($valor, $item, $text_result) {
        if (isset($valor) && $valor == $item) {
            return $text_result;
        }

        return "";
    }

    public static function getOrderBy($field_title, $field, RepositoryOrder $repository_order = null) {
        if (!is_null($repository_order)) {

            $order = $repository_order->getOrderByField($field);

            if ($repository_order->isActive($field)) {
                $caret = ($order == 'DESC') ? 'caret' : 'caret-invert';
                $caret = '<span style="margin: 7px 0px 0px 0px; float: right;" class="' . $caret . '"></span>';
            }

            return '<a href="?sort=' . $field . '&amp;order=' . $order . $repository_order->getParamString() . '">' . $field_title . '</a>' . $caret;
        }



        return '<a href="?sort=' . $field . '&amp;order=DESC">' . $field_title . '</a>';
    }

    public static function decreaseText($text = "", $size = 100, $remove_tags = true) {
        if ($remove_tags)
            $text = strip_tags($text);

        $text_decrease = substr($text, 0, $size);

        if (strlen($text) > $size)
            $text_decrease .= " ...";

        return $text_decrease;
    }

    public static function disableLayout($layout) {
        if ($layout == 1) 
           return "layout_no_menu";
        
        return "layout";
    }

}

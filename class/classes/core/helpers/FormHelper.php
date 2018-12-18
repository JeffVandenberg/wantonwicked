<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 11:54 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


use Exception;

class FormHelper
{
    public static $_label;
    public static $_id;

    public static function text($name, $value, $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name);

        $input = <<<EOQ
<input type="text" name="$name" id="$id" value="$value"
EOQ;

        $input .= self::appendOptions($options);

        $input .= ' />';
        $input = self::createLabel($name) . $input;
        return $input;
    }

    private static function convertNameToID($name)
    {
        if (self::$_id !== null) {
            return self::$_id;
        }
        return str_replace('_', '-', preg_replace('/[\W]+/', '', $name));
    }

    private static function appendOptions($options): string
    {
        if ($options !== null) {
            if (\is_array($options)) {
                $translation = '';
                foreach ($options as $key => $value) {
                    $translation .= ' ' . $key . '="' . $value . '" ';
                }
                return $translation;
            }

            return $options;
        }
        return '';
    }

    public static function select($values, $name, $selectedValue = null, $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name);
        $input = <<<EOQ
<select name="$name" id="$id"
EOQ;

        $input .= self::appendOptions($options);
        $input .= ' >';

        $input .= self::appendOptionValues($values, array($selectedValue));

        $input .= '</select>';
        $input = self::createLabel($name) . $input;
        return $input;
    }

    private static function appendOptionValues($values, $selectedValues): string
    {
        if (!\is_array($values)) {
            throw new \RuntimeException('List of values must be an array');
        }

        $options = '';
        foreach ($values as $index => $value) {
            if (\is_array($value)) {
                $options .= '<optgroup label="' . $index . '">';
                $options .= self::appendOptionValues($value, $selectedValues);
                $options .= '</optgroup>';
            } else {
                $selected = \in_array($index, $selectedValues, false) ? 'selected' : '';
                $options .= '<option value="' . $index . '" ' . $selected . '>' . $value . '</option>';
            }
        }
        return $options;
    }

    public static function textarea($name, $value = '', $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name);
        $input = <<<EOQ
<textarea name="$name" id="$id"
EOQ;

        $input .= self::appendOptions($options);

        $input .= '>' . $value . '</textarea>';
        $input = self::createLabel($name) . $input;
        return $input;
    }

    public static function button($name, $value, $type = 'submit', $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name);

        $input = <<<EOQ
<input type="$type" name="$name" id="$id" value="$value"
EOQ;
        $input .= self::appendOptions($options);
        $input .= ' />';
        return $input;
    }

    public static function hidden($name, $value, $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name);
        $input = <<<EOQ
<input type="hidden" name="$name" id="$id" value="$value"
EOQ;

        $input .= self::appendOptions($options);

        $input .= ' />';

        return $input;
    }

    public static function checkbox($name, $value, $checked, $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name);
        $label = self::createLabel($name);
        $includeHidden = true;
        if (isset($options['include_hidden'])) {
            $includeHidden = $options['include_hidden'];
            unset($options['include_hidden']);
        }
        $checked = ($checked === true) ? 'checked' : '';

        $input = '';
        if ($includeHidden) {
            $input .= self::hidden($name, '0', array('id' => $id . '_'));
        }
        $input .= <<<EOQ
<input type="checkbox" name="$name" id="$id" value="$value" $checked
EOQ;

        $input .= self::appendOptions($options);

        $input .= ' />';
        $input = $label . $input;
        return $input;
    }


    public static function checkboxList($name, $options, $selected): string
    {
        self::checkOptions($options);

        $html = '<div class="checkboxlist">';
        foreach ($options as $id => $value) {
            $checked = \in_array($id, $selected, false);
            $options = array(
                'include_hidden' => false,
                'label' => $value,
                'id' => $name . $id
            );
            $html .=
                '<div class="item">' .
                self::checkbox($name, $id, $checked, $options) .
                '</div>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function multiselect($values, $name, $selectedValues, $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name);
        $input = <<<EOQ
<select name="$name" id="$id" multiple
EOQ;

        $input .= self::appendOptions($options);
        $input .= ' >';

        $input .= self::appendOptionValues($values, $selectedValues);

        $input .= '</select>';
        $input = self::createLabel($name) . $input;
        return $input;
    }

    private static function checkOptions(&$options): void
    {
        self::$_id = null;
        self::$_label = null;

        if ($options !== null && isset($options['id'])) {
            self::$_id = $options['id'];
            unset($options['id']);
        }
        if (isset($options['label'])) {
            self::$_label = $options['label'];
            unset($options['label']);
        }
    }

    public static function radio($name, $value, $checked, $options = null): string
    {
        self::checkOptions($options);
        $id = self::convertNameToID($name . $value);

        $checked = ($checked === true) ? 'checked' : '';

        $input = <<<EOQ
<input type="radio" name="$name" id="$id" value="$value" $checked
EOQ;

        $input .= self::appendOptions($options);

        $input .= ' />';
        $input = self::createLabel($name) . $input;

        return $input;
    }

    public static function dots($name, $value, $elementType, $characterType = 'mortal', $maxDots = 7, $edit = false, $updateTraits = false, $updateXp = false): string
    {
        $id = self::convertNameToID($name);
        $input = '';
        $character_type = preg_replace('/\s/', '_', strtolower($characterType));

        for ($i = 1; $i <= $maxDots; $i++) {
            $js = '';
            $dotName = $name . '_dot' . $i;
            $dotId = self::convertNameToID($dotName);

            if ($edit) {
                $js .= "changeDots($elementType, '" . $id . "', ${i}, $maxDots, true);";
            }

            if ($updateTraits) {
                $js .= 'updateTraits();';
            }

            if ($updateXp) {
                $js .= "updateXP($elementType);";
            }

            if ($js !== '') {
                $js = "onClick=\"$js\"";
            }

            if ($i <= $value) {
                $input .= <<<EOQ
<img src="img/{$character_type}_filled.gif" name="$dotName" id="$dotId" style="border:none;" $js />
EOQ;
            } else {
                $input .= <<<EOQ
<img src="img/empty.gif" name="$dotName" id="$dotId" style="border:none;" $js />
EOQ;
            }

            if (($i % 10) === 0) {
                $input .= '<br>';
            }
        }

        $input .= self::hidden($name, $value, array('class' => 'trait-value'));
        $input = self::createLabel($name) . $input;
        return $input;
    }

    public static function label($name, $text): string
    {
        $id = self::convertNameToID($name);

        return <<<EOQ
<label for="$id">$text</label>
EOQ;
    }

    private static function createLabel($fieldName): string
    {
        $label = '';
        if (self::$_label !== null) {
            if (self::$_label === true) {
                $labelText = SlugHelper::fromNameToLabel($fieldName);
            } else {
                $labelText = self::$_label;
            }
            $label = self::label($fieldName, $labelText) . ' ';
        }
        return $label;
    }
}

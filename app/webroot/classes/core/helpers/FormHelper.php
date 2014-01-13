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
    static $_label;
    static $_id;

    public static function Text($name, $value, $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name);

        $input = <<<EOQ
<input type="text" name="$name" id="$id" value="$value"
EOQ;

        $input .= self::AppendOptions($options);

        $input .= " />";
        $input = self::CreateLabel($name) . $input;
        return $input;
    }

    private static function ConvertNameToID($name)
    {
        if(self::$_id != null)
        {
            return self::$_id;
        }
        return str_replace('_', '-', preg_replace('/[^\d\w]+/', '', $name));
    }

    private static function AppendOptions($options)
    {
        if ($options != null) {
            if (is_array($options)) {
                $translation = '';
                foreach ($options as $key => $value) {
                    $translation .= ' ' . $key . '="' . $value . '" ';
                }
                return $translation;
            }
            else {
                return $options;
            }
        }
        return '';
    }

    public static function Select($values, $name, $selectedValue = null, $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name);
        $input = <<<EOQ
<select name="$name" id="$id"
EOQ;

        $input .= self::AppendOptions($options);
        $input .= ' >';

        $input .= self::AppendOptionValues($values, array($selectedValue));

        $input .= "</select>";
        $input = self::CreateLabel($name) . $input;
        return $input;
    }

    private static function AppendOptionValues($values, $selectedValues)
    {
        if (!is_array($values)) {
            throw new Exception("List of values must be an array");
        }

        $options = "";
        foreach ($values as $index => $value) {
            if (is_array($value)) {
                $options .= '<optgroup label="' . $index . '">';
                $options .= self::AppendOptionValues($value, $selectedValues);
                $options .= '</optgroup>';
            }
            else {
                $selected = in_array($index, $selectedValues) ? 'selected' : '';
                $options .= '<option value="' . $index . '" ' . $selected . '>' . $value . '</option>';
            }
        }
        return $options;
    }

    public static function Textarea($name, $value = '', $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name);
        $input = <<<EOQ
<textarea name="$name" id="$id"
EOQ;

        $input .= self::AppendOptions($options);

        $input .= '>' . $value . '</textarea>';
        $input = self::CreateLabel($name) . $input;
        return $input;
    }

    public static function Button($name, $value, $type = 'submit', $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name);

        $input = <<<EOQ
<input type="$type" name="$name" id="$id" value="$value"
EOQ;
        $input .= self::AppendOptions($options);
        $input .= ' />';
        return $input;
    }

    public static function Hidden($name, $value, $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name);
        $input = <<<EOQ
<input type="hidden" name="$name" id="$id" value="$value"
EOQ;

        $input .= self::AppendOptions($options);

        $input .= " />";

        return $input;
    }

    public static function Checkbox($name, $value, $checked, $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name);
        $label = self::CreateLabel($name);

        $checked = ($checked === true) ? 'checked' : '';

        $input = self::Hidden($name, '0', array('id' => $id . '_'));
        $input .= <<<EOQ
<input type="checkbox" name="$name" id="$id" value="$value" $checked
EOQ;

        $input .= self::AppendOptions($options);

        $input .= " />";
        $input = $label . $input;
        return $input;
    }

    public static function Multiselect($values, $name, $selectedValues, $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name);
        $input = <<<EOQ
<select name="$name" id="$id" multiple
EOQ;

        $input .= self::AppendOptions($options);
        $input .= ' >';

        $input .= self::AppendOptionValues($values, $selectedValues);

        $input .= "</select>";
        $input = self::CreateLabel($name) . $input;
        return $input;
    }

    private static function CheckOptions(&$options)
    {
        self::$_id = null;
        self::$_label = null;

        if($options !== null) {
            if(isset($options['id'])) {
                self::$_id = $options['id'];
                unset($options['id']);
            }
        }
        if (isset($options['label'])) {
            self::$_label = $options['label'];
            unset($options['label']);
        }
    }

    public static function Radio($name, $value, $checked, $options = null)
    {
        self::CheckOptions($options);
        $id = self::ConvertNameToID($name . $value);

        $checked = ($checked === true) ? 'checked' : '';

        $input = <<<EOQ
<input type="radio" name="$name" id="$id" value="$value" $checked
EOQ;

        $input .= self::AppendOptions($options);

        $input .= " />";
        $input = self::CreateLabel($name) . $input;

        return $input;
    }

    public static function Dots($name, $value, $elementType, $characterType = 'mortal', $maxDots = 7, $edit = false, $updateTraits = false, $updateXp = false)
    {
        $id = self::ConvertNameToID($name);
        $input = "";
        $character_type = strtolower($characterType);

        for($i = 1; $i <= $maxDots; $i++)
        {
            $js = "";
            $dotName = $name.'_dot'.$i;
            $dotId = self::ConvertNameToID($dotName);

            if($edit)
            {
                $js .= "changeDots($elementType, '".$id."', ${i}, $maxDots, true);";
            }

            if($updateTraits)
            {
                $js .= "updateTraits();";
            }

            if($updateXp)
            {
                $js .= "updateXP($elementType);";
            }

            if($js != "")
            {
                $js = "onClick=\"$js\"";
            }

            if($i <= $value)
            {
                $input .= <<<EOQ
<img src="img/{$character_type}_filled.gif" name="$dotName" id="$dotId" style="border:none;" $js />
EOQ;
            }
            else
            {
                $input .= <<<EOQ
<img src="img/empty.gif" name="$dotName" id="$dotId" style="border:none;" $js />
EOQ;
            }

            if(($i%10) == 0)
            {
                $input .= "<br>";
            }
        }

        $input .= self::Hidden($name, $value);
        $input = self::CreateLabel($name) . $input;
        return $input;
    }

    public static function Label($name, $text)
    {
        $id = self::ConvertNameToID($name);

        $label = <<<EOQ
<label for="$id">$text</label>
EOQ;

        return $label;
    }

    private static function CreateLabel($fieldName)
    {
        $label = '';
        if(self::$_label != null)
        {
            if(self::$_label === true)
            {
                $labelText = SlugHelper::FromNameToLabel($fieldName);
            }
            else
            {
                $labelText = self::$_label;
            }
            $label = self::Label($fieldName, $labelText);
        }
        return $label;
    }
}
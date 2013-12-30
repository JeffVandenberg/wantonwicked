<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 1:51 PM
 * @property HtmlHelper Html
 */

class MainMenuHelper extends AppHelper
{
    public $helpers = array(
        'Html'
    );

    public function Create($menu) {
        $renderedMenu = <<<EOQ
<div id='navmenu'>
EOQ;

        $renderedMenu .= $this->AppendLevel($menu, true);
        $renderedMenu .= <<<EOQ
</div>
EOQ;
        return $renderedMenu;
    }

    private function AppendLevel($menu)
    {
        $menuLevel = <<<EOQ
<ul>
EOQ;

        foreach($menu as $label => $item) {
            if($item !== 'break') {
                if(is_array($item)) {
                    if(isset($item['visible']) && $item['visible'] === false) {
                        continue;
                    }
                    $link = "#";
                    if(isset($item['link'])) {
                        if(is_array($item['link'])) {
                            $link = $this->Html->url($item['link']);
                        }
                        else {
                            $link = $item['link'];
                        }
                    }
                    $icon = (isset($item['icon'])) ? '<img src="' . $item['icon'] . '" />' : '';
                    $id = (isset($item['id'])) ? $item['id'] : null;
                    $class = (isset($item['class'])) ? $item['class'] : null;

                    $liTag = "<li ";
                    if($id != null) {
                        $liTag .= "id=\"$id\" ";
                    }

                    if($id != null) {
                        $liTag .="class=\"$class\"";
                    }
                    $liTag .= ">";

                    $menuLevel .= $liTag;

                    $text = '<span>' . $icon . $label . '</span>';

                    //if($link != '#') {
                        $text = '<a href="' . $link. '">'.$text.'</a>';
                    //}

                    $menuLevel .= $text;

                    if(isset($item['submenu'])) {
                        $menuLevel .= $this->AppendLevel($item['submenu']);
                    }
                    $menuLevel .= '</li>';
                }
                else {
                    $menuLevel .= '<li><a href="' . $item . '">' . $label . '</a></li>';
                }
            }
            else {
                $menuLevel .= "<li style=\"height: 4px;\"><hr style=\"height:4px;background-color:#003388;border:none;\"/></li>";
            }
        }

        $menuLevel .= "</ul>";

        return $menuLevel;
    }
}
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
<ul class='menu' id='main-menu'>
EOQ;

        $renderedMenu .= $this->AppendLevel($menu, true);
        $renderedMenu .= <<<EOQ
</ul>
EOQ;
        return $renderedMenu;
    }

    private function AppendLevel($menu, $firstLayer)
    {
        $menuLevel = '';

        if(!$firstLayer)
        {
            $menuLevel .= '<ul>';
        }

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

                    $liTag = "<li";
                    if(isset($item['id'])) {
                        $liTag .= ' id="' . $item['id'] . '" ';
                    }

                    if(isset($item['class'])) {
                        $liTag .= ' class="' . $item['class'] . '" ';
                    }
                    $liTag .= ">";

                    $menuLevel .= $liTag;

                    $text = '<span>' . $icon . $label . '</span>';

                    $link = '<a href="' . $link . '" ';

                    if(isset($item['target'])) {
                        $link .= ' target="' . $item['target'] . '" ';
                    }
                    $link .= '>';
                    $link .= $text . '</a>';

                    $menuLevel .= $link;

                    if(isset($item['submenu'])) {
                        $menuLevel .= $this->AppendLevel($item['submenu'], false);
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

        if(!$firstLayer)
        {
            $menuLevel .= '</ul>';
        }

        return $menuLevel;
    }
}
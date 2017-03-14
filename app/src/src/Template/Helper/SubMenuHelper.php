<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 1:51 PM
 * @property HtmlHelper Html
 */namespace app\Template\Helper;



class SubMenuHelper extends AppHelper
{
    public $helpers = array(
        'Html'
    );

    public function Create($menuItems) {
        $menuId = mt_rand(1000000, 9999999);

        $menu = <<<EOQ
<ul class="dropdown menu" id="menu-$menuId" data-dropdown-menu>
EOQ;
        if(is_array($menuItems))
        {
            $menu .= $this->AppendLevel($menuItems, true);
        }

        $menu .= <<<EOQ
</ul>
EOQ;
        return $menu;
    }

    private function AppendLevel($menuItems, $firstLayer = false)
    {
        $menuLevel = "";
        if(!$firstLayer) {
            $menuLevel .= <<<EOQ
<ul class="menu">
EOQ;
        }

        foreach($menuItems as $label => $item) {
            if($item !== 'break') {
                if(is_array($item)) {
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
                    $target = isset($item['target']) ? 'target="' . $item['target'] . '"': '';

                    $liTag = "<li ";
                    if($id !== null) {
                        $liTag .= "id=\"$id\" ";
                    }

                    if($class !== null) {
                        $liTag .="class=\"$class\"";
                    }
                    $liTag .= ">";

                    $menuLevel .= $liTag . '<a href="' . $link. '" ' . $target . '>' . $icon . $label . '</a>';

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

        if(!$firstLayer) {
            $menuLevel .= "</ul>";
        }

        return $menuLevel;
    }
}

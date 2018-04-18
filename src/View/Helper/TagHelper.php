<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 4/18/2018
 * Time: 9:00 AM
 */

namespace App\View\Helper;


use Tags\Model\Entity\Tag;

/**
 * Class TagHelper
 * @package App\View\Helper
 */
class TagHelper extends AppHelper
{
    /**
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * @param Tag[] $tags
     * @param array $baseUrl
     *
     * @return string
     */
    public function linkList(array $tags, array $baseUrl)
    {
        $htmlHelper  = $this->Html;
        return implode(', ', array_map(function(Tag $tag) use ($baseUrl, $htmlHelper) {
            return $this->Html->link(
                $tag->label,
                $baseUrl + [$tag->slug]
            );
        }, $tags));
    }
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

/**
 * Scene Entity
 *
 * @property int $id
 * @property string $name
 * @property string $summary
 * @property int $run_by_id
 * @property \Cake\I18n\Time $run_on_date
 * @property string $description
 * @property int $created_by_id
 * @property \Cake\I18n\Time $created_on
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated_on
 * @property string $slug
 * @property int $scene_status_id
 *
 * @property \App\Model\Entity\User $run_by
 * @property \App\Model\Entity\User $created_by
 * @property \App\Model\Entity\User $updated_by
 * @property \App\Model\Entity\SceneStatus $scene_status
 * @property \App\Model\Entity\SceneCharacter[] $scene_characters
 * @property \App\Model\Entity\SceneRequest[] $scene_requests
 */
class Scene extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    protected function _setName($value)
    {
        $slug = Text::slug(strtolower($value));
        $sceneTable = TableRegistry::get('Scenes');
        $query = $sceneTable
            ->find();
        $query
            ->select([
                'count' => $query->func()->count('*')
            ])
            ->where([
                'Scenes.slug LIKE' => $slug . '%'
            ]);
        $count = $query->first();

        if($count['count'] > 0) {
            $slug .= ($count['count'] + 1);
        }
        $this->set('slug', $slug);
        return $value;
    }
}

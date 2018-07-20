<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 * Plot Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string slug
 * @property string $admin_notes
 * @property int $plot_status_id
 * @property int run_by_id
 * @property int $created_by_id
 * @property \Cake\I18n\Time $created
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated
 *
 * @property \App\Model\Entity\PlotStatus $plot_status
 * @property \App\Model\Entity\PlotVisibility $plot_visibility
 * @property \App\Model\Entity\User $run_by
 * @property \App\Model\Entity\User $created_by
 * @property \App\Model\Entity\User $updated_by
 * @property \App\Model\Entity\PlotCharacter[] $plot_characters
 * @property \App\Model\Entity\PlotScene[] $plot_scenes
 */
class Plot extends Entity
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
        $sceneTable = TableRegistry::getTableLocator()->get('Plots');
        $query = $sceneTable
            ->find();
        $query
            ->select([
                'count' => $query->func()->count('*')
            ])
            ->where([
                'Plots.name LIKE' => $value . '%'
            ]);
        $count = $query->first();

        if($count['count'] > 0) {
            $slug .= ($count['count'] + 1);
        }
        $this->set('slug', $slug);
        return $value;
    }

}

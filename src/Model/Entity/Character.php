<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Character Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $character_name
 * @property string $show_sheet
 * @property string $view_password
 * @property string $character_type
 * @property string $city
 * @property int $age
 * @property string $sex
 * @property int $apparent_age
 * @property string $concept
 * @property string $description
 * @property string $url
 * @property string $safe_place
 * @property string $friends
 * @property string $exit_line
 * @property string $icon
 * @property string $is_npc
 * @property string $virtue
 * @property string $vice
 * @property string $splat1
 * @property string $splat2
 * @property string $subsplat
 * @property int $size
 * @property int $speed
 * @property int $initiative_mod
 * @property int $defense
 * @property string $armor
 * @property int $health
 * @property int $wounds_agg
 * @property int $wounds_lethal
 * @property int $wounds_bashing
 * @property int $willpower_perm
 * @property int $willpower_temp
 * @property int $power_stat
 * @property int $power_points
 * @property int $morality
 * @property string $merits
 * @property string $flaws
 * @property string $equipment_public
 * @property string $equipment_hidden
 * @property string $public_effects
 * @property string $history
 * @property string $goals
 * @property int $character_status_id
 * @property float $current_experience
 * @property float $total_experience
 * @property int $bonus_received
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated_on
 * @property string $gm_notes
 * @property string $sheet_update
 * @property string $hide_icon
 * @property string $helper
 * @property string $status
 * @property string $bonus_attribute
 * @property string $misc_powers
 * @property float $average_power_points
 * @property float $power_points_modifier
 * @property int $temporary_health_levels
 * @property bool $is_suspended
 * @property int $location_id
 * @property string $gameline
 * @property string $slug
 *
 * @property \App\Model\Entity\CharacterNote[] $character_notes
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\UpdatedBy $updated_by
 * @property \App\Model\Entity\Location[] $locations
 * @property \App\Model\Entity\CharacterBeatRecord[] $character_beat_records
 * @property \App\Model\Entity\CharacterBeat[] $character_beats
 * @property \App\Model\Entity\CharacterLogin[] $character_logins
 * @property \App\Model\Entity\CharacterPower[] $character_powers
 * @property \App\Model\Entity\CharacterUpdate[] $character_updates
 * @property \App\Model\Entity\LogCharacter[] $log_characters
 * @property \App\Model\Entity\RequestCharacter[] $request_characters
 * @property \App\Model\Entity\Request[] $requests
 * @property \App\Model\Entity\SceneCharacter[] $scene_characters
 * @property \App\Model\Entity\SupporterCharacter[] $supporter_characters
 * @property \App\Model\Entity\Territory[] $territories
 * @property \App\Model\Entity\L5rPlot[] $l5r_plots
 */
class Character extends Entity
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
}

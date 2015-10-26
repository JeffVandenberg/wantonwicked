<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/29/13
 * Time: 11:08 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\helper;


use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\character\sheet\WodSheet;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

class CharacterSheetHelper
{
    /**
     * @var CharacterRepository
     */
    private $repository;
    /**
     * @var WodSheet
     */
    private $sheet;

    function __construct()
    {
        $this->repository = new CharacterRepository();
        $this->sheet = new WodSheet();
    }

    public function MakeStView($stats, $userdata, $characterType)
    {
        $viewOptions = array();
        $viewed_sheet = false;
        if (UserdataHelper::IsAdmin($userdata)) {
            $viewed_sheet = true;
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'admin'
            );
        }

        if (!$viewed_sheet && UserdataHelper::IsHead($userdata)) {
            $viewed_sheet = true;
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'head'
            );
        }

        if (!$viewed_sheet && UserdataHelper::IsOnlySt($userdata)) {
            $viewed_sheet = true;
            // open update
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'st'
            );
        }

        if (!$viewed_sheet && UserdataHelper::IsAsst($userdata)) {
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'asst'
            );
        }

        if ($stats['is_sanctioned'] == '') {
            $viewOptions['xp_create_mode'] = true;
        }

        return $this->sheet->buildSheet($characterType, $stats, $viewOptions);
    }

    public function UpdateSt(array $newStats, Character $oldStats, array $userdata)
    {
        $viewOptions = array();
        $viewed_sheet = false;
        if (UserdataHelper::IsAdmin($userdata)) {
            $viewed_sheet = true;
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'admin'
            );
        }

        if (!$viewed_sheet && UserdataHelper::IsHead($userdata)) {
            $viewed_sheet = true;
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'head'
            );
        }

        if (!$viewed_sheet && UserdataHelper::IsOnlySt($userdata)) {
            $viewed_sheet = true;
            // open update
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'st'
            );
        }

        if (!$viewed_sheet && UserdataHelper::IsAsst($userdata)) {
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => true,
                'calculate_derived' => true,
                'user_type' => 'asst'
            );
        }

        if ($viewed_sheet) {
            if ($newStats['xp_spent'] > 0) {
                CharacterLog::LogAction($newStats['character_id'], ActionType::XPModification, 'Removed ' . $newStats['xp_gained'] . 'XP: ' . $newStats['xp_note'], $userdata['user_id']);
            }
            if ($newStats['xp_gained'] > 0) {
                CharacterLog::LogAction($newStats['character_id'], ActionType::XPModification, 'Added ' . $newStats['xp_gained'] . 'XP: ' . $newStats['xp_note'], $userdata['user_id']);
            }
            $error = $this->sheet->updateSheet($newStats, $viewOptions, $userdata);
            if ($error == '') {
                RepositoryManager::ClearCache();
                $newCharacter = $this->repository->getById($newStats['character_id']);
                /* @var Character $newCharacter */
                $this->LogChanges($newCharacter, $oldStats, $userdata);
            }
        }
    }

    public function MakeViewOwn($stats, $characterType)
    {
        if (($stats['asst_sanctioned'] == 'Y') || ($stats['is_sanctioned'] == 'Y')) {
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_description' => true,
                'edit_temp_traits' => true,
                'edit_goals' => true,
                'user_type' => 'player'
            );
        } else {
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => false,
                'calculate_derived' => true,
                'xp_create_mode' => ($stats['asst_sanctioned'] == '') && ($stats['is_sanctioned'] == ''),
                'user_type' => 'player'
            );
        }
        return $this->sheet->buildSheet($characterType, $stats, $viewOptions);
    }

    public function UpdateOwn(Character $oldCharacter, $newStats, $userdata)
    {
        if(($oldCharacter->AsstSanctioned == 'Y') || ($oldCharacter->IsSanctioned == 'Y')) {
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_description' => true,
                'edit_temp_traits' => true,
                'edit_goals' => true,
                'user_type' => 'player'
            );
        } else {
            $viewOptions = array(
                'edit_show_sheet' => true,
                'edit_name' => true,
                'edit_vitals' => true,
                'edit_is_dead' => true,
                'edit_concept' => true,
                'edit_description' => true,
                'edit_equipment' => true,
                'edit_public_effects' => true,
                'edit_group' => true,
                'edit_is_npc' => true,
                'edit_attributes' => true,
                'edit_skills' => true,
                'edit_perm_traits' => true,
                'edit_temp_traits' => true,
                'edit_powers' => true,
                'edit_history' => true,
                'edit_goals' => true,
                'edit_experience' => true,
                'show_st_notes' => false,
                'calculate_derived' => true,
                'xp_create_mode' => ($oldCharacter->AsstSanctioned == '') && ($oldCharacter->IsSanctioned == ''),
                'user_type' => 'player'
            );
        }
        $error = $this->sheet->updateSheet($newStats, $viewOptions, $userdata);
        if ($error == '') {
            RepositoryManager::ClearCache();
            $newCharacter = $this->repository->getById($newStats['character_id']);
            /* @var Character $newCharacter */
            $this->LogChanges($newCharacter, $oldCharacter, $userdata);
        }

        return $error;
    }

    public function MakeNewView($stats, $characterType)
    {
        $viewOptions = array(
            'edit_show_sheet' => true,
            'edit_name' => true,
            'edit_vitals' => true,
            'edit_is_dead' => true,
            'edit_concept' => true,
            'edit_description' => true,
            'edit_equipment' => true,
            'edit_public_effects' => true,
            'edit_group' => true,
            'edit_is_npc' => true,
            'edit_attributes' => true,
            'edit_skills' => true,
            'edit_perm_traits' => true,
            'edit_temp_traits' => true,
            'edit_powers' => true,
            'edit_history' => true,
            'edit_goals' => true,
            'edit_experience' => true,
            'show_st_notes' => false,
            'calculate_derived' => true,
            'xp_create_mode' => true,
            'user_type' => 'player'
        );
        return $this->sheet->buildSheet($characterType, $stats, $viewOptions);
    }

    public function UpdateNew($newStats, $userdata)
    {
        $viewOptions = array(
            'edit_show_sheet' => true,
            'edit_name' => true,
            'edit_vitals' => true,
            'edit_is_dead' => true,
            'edit_concept' => true,
            'edit_description' => true,
            'edit_equipment' => true,
            'edit_public_effects' => true,
            'edit_group' => true,
            'edit_is_npc' => true,
            'edit_attributes' => true,
            'edit_skills' => true,
            'edit_perm_traits' => true,
            'edit_temp_traits' => true,
            'edit_powers' => true,
            'edit_history' => true,
            'edit_goals' => true,
            'edit_experience' => true,
            'show_st_notes' => false,
            'calculate_derived' => true,
            'xp_create_mode' => true,
            'user_type' => 'player'
        );
        $error = $this->sheet->updateSheet($newStats, $viewOptions, $userdata);
        return $error;
    }


    public function MakeLockedView($stats, $characterType)
    {
        $viewOptions = array();
        return $this->sheet->buildSheet($characterType, $stats, $viewOptions);
    }

    private function LogChanges(Character $newCharacter, Character $oldCharacter, $userdata)
    {
        if ($newCharacter->IsSanctioned != $oldCharacter->IsSanctioned) {
            if ($newCharacter->IsSanctioned == 'Y') {
                CharacterLog::LogAction($newCharacter->Id, ActionType::Sanctioned, 'ST Sanctioned Character', $userdata['user_id']);
            }
            if ($newCharacter->IsSanctioned == 'N') {
                CharacterLog::LogAction($newCharacter->Id, ActionType::Desanctioned, 'ST Desanctioned Character', $userdata['user_id']);
            }
        }

        $excludedProperties = array(
//            'IsSanctioned',
            'SheetUpdate',
            'GmNotes'
        );

        $changedProperties = array();
        foreach ($newCharacter as $property => $value) {
            if (!in_array($property, $excludedProperties)) {
                if ($newCharacter->$property != $oldCharacter->$property) {
                    $changedProperties[] = $property;
                }
            }
        }

        $note = "";
        if (count($changedProperties) > 0) {
            foreach ($changedProperties as $property) {
                $note .= $property . ' changed from ' . $oldCharacter->$property . ' to ' . $newCharacter->$property . "\n";
            }
        }

        $newPowerList = $newCharacter->CharacterPower;
        $oldPowerList = $oldCharacter->CharacterPower;

        $changedPowerList = array();

        foreach ($newCharacter->CharacterPower as $i => $newPower) {
            foreach ($oldCharacter->CharacterPower as $j => $oldPower) {
                // if they are the same
                if ($newPower->Id == $oldPower->Id) {
                    if (($newPower->PowerName != $oldPower->PowerName)
                        || ($newPower->PowerNote != $oldPower->PowerNote)
                        || ($newPower->PowerLevel != $oldPower->PowerLevel)
                    ) {
                        $changedPowerList[] = array(
                            'old' => $oldPower,
                            'new' => $newPower
                        );
                    }
                    unset($newPowerList[$i]);
                    unset($oldPowerList[$j]);
                }
            }
        }

        foreach ($newPowerList as $newPower) {
            $note .= 'Added Power: ' . $newPower->PowerType .
                ' Name: ' . $newPower->PowerName .
                ' Note: ' . $newPower->PowerNote .
                ' Level: ' . $newPower->PowerLevel . " \n";
        }

        foreach ($oldPowerList as $oldPower) {
            $note .= 'Removed Power: ' . $oldPower->PowerType .
                ' Name: ' . $oldPower->PowerName .
                ' Note: ' . $oldPower->PowerNote .
                ' Level: ' . $oldPower->PowerLevel . " \n";
        }

        foreach ($changedPowerList as $power) {
            $note .= 'Changed Power: ' . $power['new']->PowerType .
                ' OLD: ' .
                ' Name: ' . $power['old']->PowerName .
                ' Note: ' . $power['old']->PowerNote .
                ' Level: ' . $power['old']->PowerLevel .
                ' NEW ' .
                ' Name: ' . $power['new']->PowerName .
                ' Note: ' . $power['new']->PowerNote .
                ' Level: ' . $power['new']->PowerLevel . " \n";
        }

        CharacterLog::LogAction($newCharacter->Id, ActionType::UpdateCharacter, str_replace("\n", "<br/>", $note),
            $userdata['user_id']);
    }

}
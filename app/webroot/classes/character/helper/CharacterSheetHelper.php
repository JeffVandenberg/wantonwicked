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

    public function MakeViewOwn($stats, $characterType)
    {
        $viewOptions = array();

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
                'user_type' => 'player'
            );
        }
        return $this->sheet->buildSheet($characterType, $stats, $viewOptions);
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

    public function MakeLockedView($stats, $characterType)
    {
        $viewOptions = array();
        return $this->sheet->buildSheet($characterType, $stats, $viewOptions);
    }

    public function UpdateOwnLimited(Character $oldCharacter, $newStats)
    {
        $edit_show_sheet = true;
        $edit_name = false;
        $edit_vitals = false;
        $edit_is_npc = false;
        $edit_is_dead = false;
        $edit_location = false;
        $edit_concept = false;
        $edit_description = true;
        $edit_url = true;
        $edit_equipment = false;
        $edit_public_effects = false;
        $edit_group = false;
        $edit_exit_line = true;
        $edit_attributes = false;
        $edit_skills = false;
        $edit_perm_traits = false;
        $edit_temp_traits = true;
        $edit_powers = false;
        $edit_history = false;
        $edit_goals = true;
        $edit_login_note = false;
        $edit_experience = false;
        $show_st_notes = false;
        $view_is_asst = false;
        $view_is_st = false;
        $view_is_head = false;
        $view_is_admin = false;
        $may_edit = true;
        $edit_cell = false;

        $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
            $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
            $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
            $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
            $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
            $view_is_head, $view_is_admin, $may_edit, $edit_cell);
        if ($error == '') {
            RepositoryManager::ClearCache();
            $newCharacter = $this->repository->getById($newStats['character_id']);
            /* @var Character $newCharacter */
            $this->LogChanges($newCharacter, $oldCharacter);
        }

        return $error;
    }

    public function UpdateOwnFull(Character $oldCharacter, $newStats)
    {
        $edit_show_sheet = true;
        $edit_name = true;
        $edit_vitals = true;
        $edit_is_npc = true;
        $edit_is_dead = true;
        $edit_location = true;
        $edit_concept = true;
        $edit_description = true;
        $edit_url = true;
        $edit_equipment = true;
        $edit_public_effects = true;
        $edit_group = true;
        $edit_exit_line = true;
        $edit_attributes = true;
        $edit_skills = true;
        $edit_perm_traits = true;
        $edit_temp_traits = true;
        $edit_powers = true;
        $edit_history = true;
        $edit_goals = true;
        $edit_login_note = false;
        $edit_experience = false;
        $show_st_notes = false;
        $view_is_asst = false;
        $view_is_st = false;
        $view_is_head = false;
        $view_is_admin = false;
        $may_edit = true;
        $edit_cell = true;

        $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
            $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
            $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
            $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
            $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
            $view_is_head, $view_is_admin, $may_edit, $edit_cell);
        if ($error == '') {
            RepositoryManager::ClearCache();
            $newCharacter = $this->repository->getById($newStats['character_id']);
            /* @var Character $newCharacter */
            $this->LogChanges($newCharacter, $oldCharacter);
        }

        return $error;
    }

    private function LogChanges(Character $newCharacter, Character $oldCharacter)
    {
        global $userdata;

        if ($newCharacter->IsSanctioned != $oldCharacter->IsSanctioned) {
            if ($newCharacter->IsSanctioned == 'Y') {
                CharacterLog::LogAction($newCharacter->Id, ActionType::Sanctioned, 'ST Sanctioned Character', $userdata['user_id']);
            }
            if ($newCharacter->IsSanctioned == 'N') {
                CharacterLog::LogAction($newCharacter->Id, ActionType::Desanctioned, 'ST Desanctioned Character', $userdata['user_id']);
            }
        }

        $excludedProperties = array(
            'IsSanctioned',
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
                ' Level: ' . $power['new']->PowerLevel . " \n";;
        }

        CharacterLog::LogAction($newCharacter->Id, ActionType::UpdateCharacter, str_replace("\n", "<br/>", $note),
            $userdata['user_id']);
    }

    public function UpdateNew($newStats)
    {
        $edit_show_sheet = true;
        $edit_name = true;
        $edit_vitals = true;
        $edit_is_npc = true;
        $edit_is_dead = true;
        $edit_location = true;
        $edit_concept = true;
        $edit_description = true;
        $edit_url = true;
        $edit_equipment = true;
        $edit_public_effects = true;
        $edit_group = true;
        $edit_exit_line = true;
        $edit_attributes = true;
        $edit_skills = true;
        $edit_perm_traits = true;
        $edit_temp_traits = true;
        $edit_powers = true;
        $edit_history = true;
        $edit_goals = true;
        $edit_login_note = false;
        $edit_experience = false;
        $show_st_notes = false;
        $view_is_asst = false;
        $view_is_st = false;
        $view_is_head = false;
        $view_is_admin = false;
        $may_edit = true;
        $edit_cell = true;

        $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
            $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
            $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
            $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
            $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
            $view_is_head, $view_is_admin, $may_edit, $edit_cell);
        if ($error == '') {
            //$this->LogChanges($newStats, array());
        }
        return $error;
    }

    public function UpdateSt(array $newStats, Character $oldStats, array $userdata)
    {
        $viewed_sheet = false;
        $edit_show_sheet = false;
        $edit_name = false;
        $edit_vitals = false;
        $edit_is_npc = false;
        $edit_is_dead = false;
        $edit_location = false;
        $edit_concept = false;
        $edit_description = false;
        $edit_url = false;
        $edit_equipment = false;
        $edit_public_effects = false;
        $edit_group = false;
        $edit_exit_line = false;
        $edit_attributes = false;
        $edit_skills = false;
        $edit_perm_traits = false;
        $edit_temp_traits = false;
        $edit_powers = false;
        $edit_history = false;
        $edit_goals = false;
        $edit_login_note = false;
        $edit_experience = false;
        $show_st_notes = false;
        $view_is_asst = false;
        $view_is_st = false;
        $view_is_head = false;
        $view_is_admin = false;
        $may_edit = false;
        $edit_cell = false;

        if (UserdataHelper::IsAdmin($userdata)) {
            $viewed_sheet = true;
            $edit_show_sheet = true;
            $edit_name = true;
            $edit_vitals = true;
            $edit_is_npc = true;
            $edit_is_dead = true;
            $edit_location = true;
            $edit_concept = true;
            $edit_description = true;
            $edit_url = true;
            $edit_equipment = true;
            $edit_public_effects = true;
            $edit_group = true;
            $edit_exit_line = true;
            $edit_attributes = true;
            $edit_skills = true;
            $edit_perm_traits = true;
            $edit_temp_traits = true;
            $edit_powers = true;
            $edit_history = true;
            $edit_goals = true;
            $edit_login_note = true;
            $edit_experience = true;
            $show_st_notes = true;
            $view_is_asst = true;
            $view_is_st = true;
            $view_is_head = true;
            $view_is_admin = true;
            $may_edit = true;
            $edit_cell = true;
        }

        if (!$viewed_sheet && UserdataHelper::IsHead($userdata)) {
            $viewed_sheet = true;
            $edit_name = true;
            $edit_vitals = true;
            $edit_is_npc = true;
            $edit_is_dead = true;
            $edit_location = true;
            $edit_concept = true;
            $edit_description = true;
            $edit_url = true;
            $edit_equipment = true;
            $edit_public_effects = true;
            $edit_group = true;
            $edit_exit_line = true;
            $edit_attributes = true;
            $edit_skills = true;
            $edit_perm_traits = true;
            $edit_temp_traits = true;
            $edit_powers = true;
            $edit_history = true;
            $edit_goals = true;
            $edit_login_note = true;
            $edit_experience = true;
            $show_st_notes = true;
            $view_is_asst = true;
            $view_is_st = true;
            $view_is_head = true;
            $may_edit = true;
            $edit_cell = true;
        }

        if (!$viewed_sheet && UserdataHelper::IsOnlySt($userdata)) {
            $viewed_sheet = true;
            // open update
            $edit_show_sheet = false;
            $edit_name = true;
            $edit_vitals = true;
            $edit_is_npc = true;
            $edit_is_dead = true;
            $edit_location = true;
            $edit_concept = true;
            $edit_description = true;
            $edit_url = true;
            $edit_equipment = true;
            $edit_public_effects = true;
            $edit_group = true;
            $edit_exit_line = true;
            $edit_attributes = true;
            $edit_skills = true;
            $edit_perm_traits = true;
            $edit_temp_traits = true;
            $edit_powers = true;
            $edit_history = true;
            $edit_goals = true;
            $edit_login_note = true;
            $edit_experience = true;
            $show_st_notes = true;
            $view_is_st = true;
            $may_edit = true;
            $edit_cell = true;
        }

        if (!$viewed_sheet && UserdataHelper::IsAsst($userdata)) {
            $viewed_sheet = true;
            $edit_name = true;
            $edit_vitals = true;
            $edit_is_npc = true;
            $edit_is_dead = true;
            $edit_location = true;
            $edit_concept = true;
            $edit_description = true;
            $edit_url = true;
            $edit_equipment = true;
            $edit_public_effects = true;
            $edit_group = true;
            $edit_exit_line = true;
            $edit_attributes = true;
            $edit_skills = true;
            $edit_perm_traits = true;
            $edit_temp_traits = true;
            $edit_powers = true;
            $edit_history = true;
            $edit_goals = true;
            $edit_login_note = true;
            $edit_experience = true;
            $show_st_notes = true;
            $view_is_asst = true;
            $may_edit = true;
            $edit_cell = true;
        }
        if ($viewed_sheet) {
            if ($newStats['xp_spent'] > 0) {
                CharacterLog::LogAction($newStats['character_id'], ActionType::XPModification, 'Removed ' . $newStats['xp_gained'] . 'XP: ' . $newStats['xp_note'], $userdata['user_id']);
            }
            if ($newStats['xp_gained'] > 0) {
                CharacterLog::LogAction($newStats['character_id'], ActionType::XPModification, 'Added ' . $newStats['xp_gained'] . 'XP: ' . $newStats['xp_note'], $userdata['user_id']);
            }
            $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
                $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
                $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
                $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
                $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
                $view_is_head, $view_is_admin, $may_edit, $edit_cell);
            if ($error == '') {
                RepositoryManager::ClearCache();
                $newCharacter = $this->repository->getById($newStats['character_id']);
                /* @var Character $newCharacter */
                $this->LogChanges($newCharacter, $oldStats);
            }
        }
    }
}
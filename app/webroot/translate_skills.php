<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 8/29/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */

use classes\character\data\CharacterPower;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;

require 'cgi-bin/start_of_page.php';

$db = new Database();
$skillSql = <<<EOQ
SELECT
    *
FROM
    wod_characters
WHERE
    character_id = 8551
EOQ;

$attributes = array(
    "Strength",
    "Dexterity",
    "Stamina",
    "Presence",
    "Manipulation",
    "Composure",
    "Intelligence",
    "Wits",
    "Resolve"
);

$skills = array(
    "Academics",
    "Animal_Ken",
    "Athletics",
    "Brawl",
    "Computer",
    "Crafts",
    "Drive",
    "Empathy",
    "Expression",
    "Firearms",
    "Intimidation",
    "Investigation",
    "Larceny",
    "Medicine",
    "Occult",
    "Persuasion",
    "Politics",
    "Science",
    "Socialize",
    "Stealth",
    "Streetwise",
    "Subterfuge",
    "Survival",
    "Weaponry"
);

$characterPowerRepository = RepositoryManager::GetRepository('classes\character\data\CharacterPower');

foreach($db->Query($skillSql)->All() as $character) {
    // insert Attributes
    foreach($attributes as $attribute) {
        $newAttribute = new CharacterPower();
        $newAttribute->CharacterId = $character['Character_ID'];
        $newAttribute->PowerType = 'Attribute';
        $newAttribute->PowerName = $attribute;
        $newAttribute->PowerLevel = $character[$attribute];
        $characterPowerRepository->Save($newAttribute);
    }

    // insert Skills
    foreach($skills as $skill) {
        $newAttribute = new CharacterPower();
        $newAttribute->CharacterId = $character['Character_ID'];
        $newAttribute->PowerType = 'Skill';
        $newAttribute->PowerName = $skill;
        $newAttribute->PowerLevel = $character[$skill];
        $characterPowerRepository->Save($newAttribute);
    }
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 8/29/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */

die('do not do this');
use classes\character\data\CharacterPower;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;

require 'cgi-bin/start_of_page.php';

$db = new Database();
// clean existing skills and attributes
$cleanSql = <<<EOQ
DELETE FROM
    character_powers
WHERE
    power_type IN ('Attribute', 'Skill');
EOQ;

$db->Query($cleanSql)->Execute();

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

$currentRow = 0;
$step = 100;
$hasMoreRows = true;

while ($hasMoreRows) {
    $skillSql = <<<EOQ
SELECT
    *
FROM
    characters
LIMIT
    $currentRow, $step
EOQ;

    $rows = $db->Query($skillSql)->All();
    $hasMoreRows = count($rows) > 0;
    $currentRow += $step;

    $sql = <<<EOQ
INSERT INTO
    character_powers
    (
        character_id,
        power_type,
        power_name,
        power_level
    )
VALUES
    (
        ?,
        ?,
        ?,
        ?
    )
EOQ;
    $db->Query($sql);

    foreach ($rows as $character) {
        // insert Attributes
        foreach ($attributes as $attribute) {
            $params = array(
                $character['id'],
                'Attribute',
                $attribute,
                $character[$attribute]
            );
            $db->Execute($params);
        }

        // insert Skills
        foreach ($skills as $skill) {
            $params = array(
                $character['id'],
                'Skill',
                str_replace('_', ' ', $skill),
                $character[$skill]
            );
            $db->Execute($params);
        }

        echo "Finished $character[character_name]<br />";
    }
}
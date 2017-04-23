<?php

use classes\core\repository\Database;

die('nope');
require_once 'cgi-bin/start_of_page.php';

$characterList = [
    'Abdul al-Basir el-Hashem',
    'Abigail Shaw',
    'Adam -Lugh- Woodburn',
    'Aerin Moss',
    'Agatha Apocalypse',
    'Akiko Saito',
    'Alan -NiK- Deckard',
    'Alex Kingsman',
    'Alexander Hayes',
    'Alexander Vans',
    'Alyssa Noapte',
    'Amy Valheim',
    'Anastasia Goodrum',
    'Andrew -Enki- Smith',
    'Angel Liu',
    'Annabella -Pachelbelle- Lee',
    'Aoife Kinsella',
    'Ash Dalton',
    'Ashton Goodrum',
    'Aubrey -Tears of Night- Wystan',
    'Baxter Patterson',
    'Blair Bell',
    'Breken Wilder',
    'Caine -0311- Roman',
    'Cassandra -Fam- Miller',
    'Cassidy -Harlequin- Malone',
    'Cassie Stevens',
    'Cecily -Renard- Rousseau',
    'Charlie Rose',
    'Chloe Stedman',
    'Christine Yorecaster',
    'Claire -Guthespar- Lowell',
    'Conall Kinsella',
    'Daniel Renatus',
    'Daphne-Risque-Crimmons',
    'Darcy -Relentless- Murphy',
    'Darius Sands',
    'David -Epimetheus- Thatcher',
    'David -Virus- Fan',
    'Deidre Kinsella',
    'Devin Warrick',
    'Dominic Sandice',
    'Donovan -Discord- Abakumov',
    'Dr. Ethan -Epilogue- Rivers',
    'Dr. Victoria Frank',
    'Dustin -Myth- Ward',
    'Eidechse Niemand',
    'Elizabeth Evans',
    'Elizabeth Ward',
    'Elliot Bain',
    'Elliot -Ricardo- Moore',
    'Emmalee Couverden',
    'Ethan Black',
    'Faith Cody',
    'Fan -Ji Ren- Zhangwei',
    'Fiona Kinsella',
    'Franky Stine',
    'Gwendolyn Morgan',
    'Harvey Wallson',
    'Haydan Sinclair',
    'Ismael Lobo',
    'Ivan -The Terrible- Reznikov',
    'Jack -Chosin- Maheux',
    'Jack -Titan- Cross',
    'Jackie Valentine',
    'Jackson Dane',
    'James Berdicio',
    'Jason Steggar',
    'Jax -Blood Howler- Carson',
    'Jefferson -Shanti- Jones',
    'Jennifer -Isadora- Crawford',
    'Jess -Spark-of-Truth- Lepage',
    'Jesus -Bad Hombre- Santiago',
    'John Warwick',
    'Jonathan Cross',
    'Joseph -Ozymandias- Ratcliff',
    'Joshua -Mammon- Tilden',
    'Julian -Tybalt- Park',
    'Juneau -Feodorovna- Klichka',
    'Karin -Dreyrugr- Volsung',
    'Kate Stevens',
    'Keigan -War Path- Dewitt',
    'Kitty Croix',
    'Kylie -Marionette- Daughtry',
    'Lauren Hearst',
    'Leon -Spirit Song- Burton',
    'Lidia Cross',
    'Lucas Marshall',
    'Lunarie -Lulu- Murphy',
    'Lynette Kinsella',
    'Madison Roth',
    'Mallory -Flux- Keen',
    'Marcus Armitage',
    'Marcus Shepard',
    'Mark Wright',
    'Matthew -Wraith- Greywater',
    'Maya Shaw',
    'Melissa Hawthorne',
    'Mick Westbay',
    'Micky DiAngelo',
    'Mustang',
    'Nixie -Rogue- Roth',
    'Parker -Catalyst- Castle',
    'Pierre -Dot- Small',
    'Poppet',
    'Red Sariel',
    'Regis -Fluke- Lockheart',
    'Ren J. Morrison',
    'Ricard -Frey- Fortineau',
    'Richard -Crossroads- Valentine',
    'Rick -Connor- Turner',
    'Robert -Psychopomp- Etmire',
    'Rodina Koldunya',
    'Rory Jackson',
    'Rosa Maria Reyes',
    'Ruby Gold',
    'Ryuko Saito',
    'Samuel Caine',
    'Shannon Thorne',
    'Sinead Kinsella',
    'Sir Yes Sir',
    'Sophie -Calypso- Bourbon',
    'Spencer Reid',
    'Stanley -Seg:Fault- Adams',
    'Sylvester Eldridge',
    'Tabitha Underwood',
    'Tegan -Blades- Foster',
    'Thelonious Seidel',
    'Uriel Beauregard',
    'Vanukas Parker',
    'Varaa Kapoor',
    'Veronica Snow',
    'Vrys Sarkhet',
    'Wagner Voight',
    'Wesley -Erebus- Waite',
    'William -Agni- Bain',
    'Yokai Ishida',
    'Zachary-Nerzul-Mason',
    'Zee Rios',
];

$placeHolders = implode(',', array_fill(0, count($characterList), ' ? '));
$db = Database::getInstance();
$db->startTransaction();

try {
    $sql = <<<SQL
CREATE TEMPORARY TABLE IF NOT EXISTS jan_characters AS (select id from characters where character_name IN ($placeHolders));
SQL;
    $db->query($sql)->execute($characterList);

    $sql = <<<SQL
UPDATE
  characters
SET
  current_experience = current_experience + 5,
  total_experience = total_experience + 5
WHERE
  id IN (
    select id from jan_characters
  )
SQL;

    $db->query($sql)->execute($characterList);
    echo "Granting XP to January<br />";

    $sql = <<<SQL
INSERT INTO
  log_characters (character_id, action_type_id, note, reference_id, created_by_id, created)
SELECT
  id,
  11,
  'Manually Award 5 XP for January sanction',
  null,
  8,
  NOW()
FROM
  jan_characters
SQL;

    $db->query($sql)->execute($characterList);
    echo "Adding XP Log to January<br />";

    $sql = <<<SQL
UPDATE
  characters
SET
  current_experience = current_experience + 5,
  total_experience = total_experience + 5
WHERE
  id NOT IN (
    select id from jan_characters
  )
  AND city = 'portland'
  AND is_sanctioned = 'Y'
  AND is_deleted = 'N'
  AND is_npc = 'N'
SQL;

    $db->query($sql)->execute($characterList);
    echo "Granting XP to February<br />";

    $sql = <<<SQL
INSERT INTO
  log_characters (character_id, action_type_id, note, reference_id, created_by_id, created)
SELECT
  id,
  11,
  'Manually Award 2 XP for February sanction',
  null,
  8,
  NOW()
FROM
  characters
WHERE
  id NOT IN (
    select id from jan_characters
  )
  AND city = 'portland'
  AND is_sanctioned = 'Y'
  AND is_deleted = 'N'
  AND is_npc = 'N'
SQL;
    $db->query($sql)->execute($characterList);
    echo "Adding XP Log to February<br />";
    echo "Done<br />";
    $db->commitTransaction();
} catch (Exception $e) {
    echo $e->getMessage();
    print_r($e);
    die('error handling');
}

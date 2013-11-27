<?php


use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;

include 'cgi-bin/start_of_page.php';
echo date('U')*1000;
die();
$repository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $repository */

$characters = $repository->ListSupporterCharacters();

foreach($characters as $character) {
    echo $character['bonus_received'] . ' : ' .
        $character['Total_Experience'] . ' : ' .
        $character['Current_Experience'] . ' : ' .
        $character['Character_Name'] . '<br /><br />';
}

die();
$character = $repository->GetById(8551);
/* @var Character $character */

//$attribute = $character->Attributes[0];
//$attribute->Character;
debug($character->CharacterName);

debug($character->getAttribute('Intelligence'));

foreach($character->Attributes as $attribute) {
    echo $character->CharacterName . ' has ' . $attribute->PowerName . ' at ' . $attribute->PowerLevel . '<br />';
}
echo '<br />';
foreach($character->Skills as $skill) {
    echo $character->CharacterName . ' has ' . $skill->PowerName . ' at ' . $skill->PowerLevel . '<br />';
}
echo '<br />';
foreach ($character->Specialties as $specialty) {
    echo $character->CharacterName . ' has specialty ' . $specialty->PowerName . ' for ' . $specialty->PowerNote . '<br />';
}
echo '<br />';
foreach ($character->Merits as $merit) {
    echo $character->CharacterName . ' has ' . $merit->PowerName . ' (' . $merit->PowerNote . ') at ' . $merit->PowerLevel . '<br />';
}
echo '<br />';
foreach ($character->Flaws as $merit) {
    echo $character->CharacterName . ' has ' . $merit->PowerName . '<br />';
}
echo '<br />';
foreach ($character->InClanDisciplines as $discipline) {
    echo $character->CharacterName . ' has ' . $discipline->PowerName . ' at ' . $discipline->PowerLevel . '<br />';
}
echo '<br />';
foreach ($character->OutOfClanDisciplines as $discipline) {
    echo $character->CharacterName . ' has ' . $discipline->PowerName . ' at ' . $discipline->PowerLevel . '<br />';
}
echo '<br />';
foreach ($character->Devotions as $discipline) {
    echo $character->CharacterName . ' has ' . $discipline->PowerName . '<br />';
}

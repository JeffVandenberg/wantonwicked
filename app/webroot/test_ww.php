<?php

use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\repository\Database;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\data\RequestStatus;
use classes\request\data\RequestType;
use classes\request\RequestMailer;

require_once 'cgi-bin/start_of_page.php';

$messages = array();
if(Request::IsPost())
{
    $characterRepository = new CharacterRepository();
//    $historyRepository = new Histo
    $file = fopen($_FILES['characters']['tmp_name'], 'r');

    while($row = fgetcsv($file))
    {
        $character = $characterRepository->FindByCharacterName($row[0]);
        /* @var Character $character */

//        var_dump($character);
        if($character->Id)
        {
            $messages[] = 'Award ' . $row[1] . ' xp to ' . $character->CharacterName . '<br />';
            $character->TotalExperience += $row[1];
            $character->CurrentExperience += $row[1];
            $characterRepository->Save($character);

            CharacterLog::LogAction($character->Id, ActionType::XPModification, 'Awarded ' . $row[1] . ' for ' . $row[2]);
        }
        else
        {
            $messages[] = 'unable to find ' . $row[0];
        }
    }

    fclose($file);
}

?>

<?php foreach($messages as $message): ?>
    <?php echo $message; ?><br />
<?php endforeach; ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
    <input type="file" name="characters" />
    <input type="submit" value="Upload Characters" />
</form>
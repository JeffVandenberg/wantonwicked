<?php
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;

$characterId = Request::getValue('character_id', 11552);

$characterRepository = new CharacterRepository();
$character = $characterRepository->getById($characterId);

if(Request::isPost()) {
    $action = Request::getValue('action');
    if($action == 'Punch the guy') {
        SessionHelper::setFlashMessage("(If Wrath) You feel a bit better. Get a Willpower");
    }
    if($action == 'Ignore and walk by') {
        if(mt_rand(1,10) < 6) {
            SessionHelper::setFlashMessage('Just another bum on the street');
        }
        else {
            SessionHelper::setFlashMessage('You\'re attacked from behind. It was a stabbin\' hobo.');
        }
    }
    if($action == 'Offer a bit of charity') {
        if(mt_rand(1,10) == 1) {
            SessionHelper::setFlashMessage('He gives you a smile and a wink. When you get home, you find a present waiting for you.');
        }
        else {
            SessionHelper::setFlashMessage('You watch him stumble off to get a bottle of something to make him forget his misery.');
        }
    }
    if($action == 'Take a drink') {
        if(mt_rand(1,10) < 5) {
            SessionHelper::setFlashMessage('You accidentally drain him dry. You don\'t feel bad. (-1 Humanity)');
        }
        else {
            SessionHelper::setFlashMessage('It is not the best blood, but easy hunting. (3 blood)');
        }
    }
}

$page_title = $contentHeader = 'Encounter on the Street';
ob_start();
?>

    <div class="paragraph">
        Bruce encounters a homeless man clad in beaten clothing: faded jeans, an old red jacket,
        facial hair that's not seen a razor in a couple of years, and his breath reeks as he
        comes up to Bruce and asks, "Hey.. got something for a fellow human?"
    </div>

    <form method="post">
        <?php echo FormHelper::button('action', 'Punch the guy'); ?>
        <?php echo FormHelper::button('action', 'Ignore and walk by'); ?>
        <?php echo FormHelper::button('action', 'Offer a bit of charity'); ?>
        (Vamp Only) <?php echo FormHelper::button('action', 'Take a drink'); ?>
    </form>
<?php
$page_content = ob_get_clean();

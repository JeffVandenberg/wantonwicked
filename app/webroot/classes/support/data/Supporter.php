<?php
namespace classes\support\data;

use classes\support\repository\SupporterRepository;
use classes\character\data\Character;
use classes\core\data\DataModel;
use classes\core\data\User;
use classes\core\repository\RepositoryManager;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/25/13
 * Time: 3:56 PM
 */


/**
 * @property User User
 * @property User UpdatedBy
 * @property Character[] Characters
 */
class Supporter extends DataModel
{
    public $Id;
    public $UserId;
    public $ExpiresOn;
    public $NumberOfCharacters;
    public $AmountPaid;
    public $CharactersAwarded;
    public $UpdatedById;
    public $UpdatedOn;

    public $BelongsTo = array(
        'User' => 'classes\core\data\User',
        'UpdatedBy' => 'classes\core\data\User'
    );

    function __construct()
    {
        parent::__construct('', '');
        $this->SortColumn = 'expires_on';
    }

    function __get($propertyName)
    {
        if ($propertyName == 'Characters') {
            if (!isset($this->Characters)) {
                $supporterRepository = RepositoryManager::GetRepository('classes\support\data\Supporter');
                /* @var SupporterRepository $supporterRepository */
                $this->Characters = $supporterRepository->ListSelectedCharactersForSupporter($this->Id);
            }
            return $this->Characters;
        } else {
            return parent::__get($propertyName);
        }
    }
}
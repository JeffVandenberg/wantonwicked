<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 10/16/13
 * Time: 4:42 PM
 */

namespace classes\support;


use classes\character\repository\CharacterRepository;
use classes\core\repository\RepositoryManager;
use classes\support\repository\SupporterRepository;

/**
 * Class EnrollmentManager
 * @package classes\enrollment
 */
class SupportManager
{
    /**
     * @var SupporterRepository
     */
    private $SupporterRepository;

    /**
     *
     */
    function __construct() {
        $this->SupporterRepository = RepositoryManager::GetRepository('classes\support\data\Supporter');
    }

    /**
     *
     */
    public function SendReminderEmails() {
        $users = $this->SupporterRepository->ListUsersTwoWeeksFromExpiring();

        foreach($users as $user) {
            $message = <<<EOQ
$user[username],

Your supporter status on WantonWicked is set to expire in two weeks. This is a friendly reminder to
update your status by visiting http://wantonwicked.gamingsandbox.com/support.php?action=contribute.

Your support for the site has been appreciated and has contributed towards server costs, advertising,
and other incidental costs that allow me to be more productive. :)


Thanks,
Jeff Vandenberg
EOQ;

            mail($user['user_email'], 'Your Supporter Status Expires in 2 weeks', $message, 'from: support@gamingsandbox.com');
        }
    }

    /**
     *
     */
    public function AwardBonusXP() {
        $characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');

        /* @var CharacterRepository $characterRepository */
        $characterRepository->ClearBonusXP();
        $this->SupporterRepository->ClearAwardedCharacters();
        $characterRepository->AwardSupporterBonusXP();

    }

    /**
     *
     */
    public function ExpireSupporterStatus() {
        $this->SupporterRepository->RemoveSupporterStatusFromExpiredSupporters();
    }

    /**
     * @param $userId
     */
    public function GrantSupporterStatus($userId) {
        $this->SupporterRepository->GrantedSupporterStatus($userId);
    }
} 
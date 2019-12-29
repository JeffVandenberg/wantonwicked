<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/5/2018
 * Time: 9:09 PM
 */

namespace App\Controller\Component;

use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Exception;
use function compact;

/**
 * Class RequestEmailComponent
 * @package App\Controller\Component
 */
class RequestEmailComponent extends Component
{
    /**
     * @param Request $request Request to notify staff about
     * @return bool
     */
    public function newRequestSubmission(Request $request): bool
    {
        $email = new Email();

        $userTable = TableRegistry::getTableLocator()->get('Users');
        /* @var UsersTable $userTable */
        $users = $userTable->listUsersInGroup($request->group_id);
        /* @var User[] $users */

        foreach ($users as $user) {
            $email = $email->addTo($user->user_email);
        }

        try {
            $email->viewBuilder()->setLayout('wantonwicked')
                ->setTemplate('new_request');

            $email->setFrom('wantonwicked@gamingsandbox.com')
                ->setSubject('Request Submitted: ' . $request->title)
                ->setEmailFormat('html')
                ->setViewVars(compact('request'))
                ->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $email player Email
     * @param string $username staff user name
     * @param string $state new state
     * @param string $note staff note
     * @param Request $request player's request
     * @return bool
     */
    public function notificationToPlayer($email, $username, $state, $note, Request $request): bool
    {
        $statePast = RequestStatus::getPastTenseForState($state);
        try {
            $message = new Email();

            $message->viewBuilder()->setLayout('wantonwicked')
                ->setTemplate('player_request_notification');

            $message->addTo($email)
                ->setSubject('Request ' . $request->title . ' was ' . $statePast)
                ->setEmailFormat('html')
                ->setViewVars(compact('username', 'statePast', 'note', 'request'))
                ->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param  string $email player email
     * @param  string $username player username
     * @param  string $note staff note
     * @param  Request $request player request
     * @return bool
     */
    public function assignedRequest($email, $username, $note, Request $request): bool
    {
        try {
            $message = new Email();
            $message->viewBuilder()->setLayout('wantonwicked')
                ->setTemplate('assigned_request_notification');

            $message->addTo($email)
                ->setSubject('Request ' . $request->title . ' was assigned to you.')
                ->setEmailFormat('html')
                ->setViewVars(compact('username', 'note', 'request'))
                ->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

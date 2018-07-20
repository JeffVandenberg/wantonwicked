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
use function compact;

class RequestEmailComponent extends Component
{
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
            $result = $email->setFrom('wantonwicked@gamingsandbox.com')
                ->setSubject('Request Submitted: ' . $request->title)
                ->setEmailFormat('html')
                ->setLayout('wantonwicked')
                ->setTemplate('new_request')
                ->setViewVars(compact('request'))
                ->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function notificationToPlayer($email, $username, $state, $note, Request $request): bool
    {
        $statePast = RequestStatus::getPastTenseForState($state);
        try {
            $email = (new Email())
                ->addTo($email)
                ->setSubject('Request ' . $request->title . ' was ' . $statePast)
                ->setEmailFormat('html')
                ->setLayout('wantonwicked')
                ->setTemplate('player_request_notification')
                ->setViewVars(compact('username', 'statePast', 'note', 'request'))
                ->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function assignedRequest($email, $username, $note, Request $request): bool
    {
        try {
            $email = (new Email())
                ->addTo($email)
                ->setSubject('Request ' . $request->title . ' was assigned to you.')
                ->setEmailFormat('html')
                ->setLayout('wantonwicked')
                ->setTemplate('assigned_request_notification')
                ->setViewVars(compact('username', 'note', 'request'))
                ->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

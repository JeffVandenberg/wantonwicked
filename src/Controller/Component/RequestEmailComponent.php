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

        $userTable = TableRegistry::get('Users');
        /* @var UsersTable $userTable */
        $users = $userTable->listUsersInGroup($request->group_id);
        /* @var User[] $users */

        foreach($users as $user) {
            $email->addTo($user->user_email);
        }

        $result = $email->setFrom('wantonwicked@gamingsandbox.com')
            ->setSubject('Request Submitted: ' . $request->title)
            ->setEmailFormat('html')
            ->setLayout('wantonwicked')
            ->setTemplate('new_request')
            ->setViewVars(compact('request'))
            ->send();
        return true;
    }

    public function notificationToPlayer($email, $username, $state, $note, Request $request): bool
    {
        $statePast = RequestStatus::getPastTenseForState($state);
        $email = (new Email())
            ->addTo($email)
            ->setSubject('Request ' . $request->title . ' was ' . $statePast)
            ->setEmailFormat('html')
            ->setLayout('wantonwicked')
            ->setTemplate('player_request_notification')
            ->setViewVars(compact('username', 'statePast', 'note', 'request'))
            ->send();
        return true;
    }
}

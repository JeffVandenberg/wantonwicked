<?php

use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;

require_once 'cgi-bin/start_of_page.php';

SessionHelper::SetFlashMessage('Test message generated at: ' . date('Y-m-d H:i:s'));

Response::redirect('/');

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 9:54 AM
 * To change this template use File | Settings | File Templates.
 */

use classes\core\helpers\Response;


include 'cgi-bin/start_of_page.php';

Response::redirect('/requests');

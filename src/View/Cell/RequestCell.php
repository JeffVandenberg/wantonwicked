<?php
namespace App\View\Cell;

use App\Model\Entity\Request;
use Cake\View\Cell;
use function compact;

/**
 * Request cell
 */
class RequestCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @param Request $request
     * @param $adminView
     * @return void
     */
    public function display(Request $request, $adminView)
    {
        $this->set(compact('request', 'adminView'));
    }
}

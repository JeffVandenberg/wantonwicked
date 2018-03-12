<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/30/13
 * Time: 11:02 AM
 */

namespace classes\core\helpers;

/**
 * Class Pagination
 * @package classes\core\helpers
 *
 * TODO: Remove this class after refactoring out bluebooks
 * @deprecated Remove this class from the code base
 */
class Pagination
{
    private $SortColumn;
    private $SortDirection;
    private $Parameters;

    public function SetSort($sort)
    {
        $parts = explode(' ', $sort);
        $this->SortColumn = $parts[0];
        if(isset($parts[1])) {
            $this->SortDirection = $parts[1];
        }
        else {
            $this->SortDirection = 'ASC';
        }
    }

    public function GetSort()
    {
        return $this->SortColumn . ' ' . $this->SortDirection;
    }

    public function SortColumn($columnName)
    {
        $direction = 'ASC';
        if($columnName == $this->SortColumn) {
            $direction = ($this->SortDirection == 'ASC') ? 'DESC' : 'ASC';
        }

        return $columnName . ' ' . $direction;
    }

    public function SetParameters($parameters)
    {
        $this->Parameters = $parameters;
    }

    public function GetPrev()
    {
        $tempParameters = $this->Parameters;
        $tempParameters['page'] = $tempParameters['page'] - 1;
        return http_build_query($tempParameters);
    }

    public function GetNext()
    {
        $tempParameters = $this->Parameters;
        $tempParameters['page'] = $tempParameters['page'] + 1;
        return http_build_query($tempParameters);
    }

    public function GetSortLink($columnName)
    {
        $direction = 'ASC';
        if($columnName == $this->SortColumn) {
            $direction = ($this->SortDirection == 'ASC') ? 'DESC' : 'ASC';
        }
        $tempParameters = $this->Parameters;
        $tempParameters['sort'] = $columnName . ' ' . $direction;
        return http_build_query($tempParameters);
    }
}

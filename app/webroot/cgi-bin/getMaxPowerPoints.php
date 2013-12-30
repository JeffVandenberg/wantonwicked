<?
function getMaxPowerPoints($power_rank = 1)
{
  $points = 0;
  switch($power_rank)
  {
    case 1:
      $points = 10;
      break;
    case 2:
      $points = 11;
      break;
    case 3:
      $points = 12;
      break;
    case 4:
      $points = 13;
      break;
    case 5:
      $points = 14;
      break;
    case 6:
      $points = 15;
      break;
    case 7:
      $points = 20;
      break;
    case 8:
      $points = 30;
      break;
    case 9:
      $points = 50;
      break;
    case 10:
      $points = 100;
      break;
    default:
      $points = 10;
      break;
  }
  return $points;
}
?>
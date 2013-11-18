<?
$dst_mod = 0;
if(date("I") == 0)
{
  $dst_mod = 1;
}
$timezone_adjustment = 1;//(-substr(date('O'), 2, strlen(date('O')))/100 - 4 - $dst_mod);
?>
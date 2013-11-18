<?
$java_script .= <<<EOQ
<script language="Javascript">
function doSort( col_name )
{
  window.document.sort_form.last_order_by.value = window.document.sort_form.this_order_by.value;
  window.document.sort_form.this_order_by.value = col_name;
  window.document.sort_form.submit();
}
</script>
EOQ;
?>
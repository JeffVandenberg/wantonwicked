<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/9/2015
 * Time: 11:57 PM
 */

namespace classes\abp;


class Rules
{
    public static function CreateRuleList($rules, $mayManage = true)
    {
        $ruleList = "";
        $ruleList .= <<<EOQ
<div class="tableRowHeader" style="width:752px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:220px;">
		Rule Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:100px;">
		Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:130px;">
		Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:130px;">
		Note
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		Mult.
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		Mod.
	</div>
	<div class="tableRowHeaderCell cell" style="width:70px;">
		&nbsp;
	</div>
</div>
EOQ;

        if (count($rules) > 0) {
            $row = 0;

            foreach ($rules as $ruleDetail) {
                $rowAlt = (($row++) % 2) ? "Alt" : "";

                $powerNote = ($ruleDetail['power_note'] != '') ? $ruleDetail['power_note'] : "&nbsp;";

                $ruleList .= <<<EOQ
<div class="tableRow$rowAlt" style="clear:both;width:752px;" id="ruleRow$ruleDetail[id]">
	<div class="firstCell cell" style="width:220px;">
		$ruleDetail[rule_name]
	</div>
	<div class="cell" style="width:100px;">
		$ruleDetail[power_type]
	</div>
	<div class="cell" style="width:130px;">
		$ruleDetail[power_name]
	</div>
	<div class="cell" style="width:130px;">
		$powerNote
	</div>
	<div class="cell" style="width:30px;">
		$ruleDetail[multiplier]
	</div>
	<div class="cell" style="width:30px;">
		$ruleDetail[modifier]
	</div>
EOQ;
                if ($mayManage) {
                    $ruleList .= <<<EOQ
	<div class="cell" style="width:70px;">
		<a href="abp.php?action=edit_rule&id=$ruleDetail[id]" class="overlayPanelAction">Edit</a>
		<a href="abp.php?action=delete_rule" id="$ruleDetail[id]" ruleName="$ruleDetail[rule_name]" class="actionLink">Delete</a>
	</div>
EOQ;
                }

                $ruleList .= <<<EOQ
</div>
EOQ;
            }
        } else {
            $ruleList .= <<<EOQ
<div style="clear:both;">
	No rules defined.
</div>
EOQ;
        }

        return $ruleList;
    }
}

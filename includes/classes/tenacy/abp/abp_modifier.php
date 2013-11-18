<?php
class ABPModifier
{
	private $modifierName;
	private $modifierValue;
	
	public function ABPModifier($name, $value)
	{
		$this->modifierName = $name;
		$this->modifierValue = $value;
	}
	
	public function GetModifierName()
	{
		return $this->modifierName;
	}
	public function SetModifierName($value)
	{
		$this->modifierName = $value;
	}
	public function GetModifierValue()
	{
		return $this->modifierValue;
	}
	public function SetModifierValue($value)
	{
		$this->modifierValue = $value;
	}
}
?>
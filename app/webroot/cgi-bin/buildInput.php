<?php
function BuildInput($options) {
	if($options === null) {
		return "";
	}
	
	if(!is_array($options)) {
		return "";
	}
	
	switch($options['type']) {
		case 'select':
			return BuildSelectInput($options);
			break;
		case 'text':
			return BuildTextInput($options);
			break;
		case 'textarea';
			return BuildTextareaInput($options);
			break;
		case 'radio':
			break;
		case 'dots':
			return BuildDotsInput($options);
			break;
	}
	return "";
}

function BuildSelectInput($options) {
	$tag = "<select>";
	
	$tag = SetupInput($tag, $options);
	
	if($options['values'] && is_array($options['values'])) {
		foreach($options['values'] as $key => $value) {
			$selected = ($options['value'] == $value) ? "checked" : "";
			$tag .= "<option value='$key' $selected>$value</option>";
		}
	}
	
	$tag .= "</select>";
	return $tag;
}

function BuildTextInput($options) {
	$tag = "<input type='text'>";
	
	$tag = SetupInput($tag, $options);
	
	if($options['value']) {
		$tag = str_replace(">", " value='$options[value]'>", $tag);
	};
	
	return $tag;
}

function BuildTextareaInput($options) {
	$tag = "<textarea>";
	
	$tag = SetupInput($tag, $options);
	
	if($options['value']) {
		$tag .= $options['value'];
	};
	
	$tag .= "</textarea>";
	
	return $tag;
}

function BuildDotsInput($options) {
	$dots = "";
	
	$id = "";
	if($options['id']) {
		$id = $options['id'];
	} 
	else if($options['name']) {
		$id = ConvertNameToId($options['name']) . $i;
	}
	$name = $options['name'];
		
	for($i = 1; $i <= $options['numberOfDots']; $i++) {
		$src = "img/empty.gif";
		
		if($i <= $options['value']) {
			$src = "img/mortal_filled.gif";
		}
		
		$classes = "";
		
		if($options['editable']) {
			$classes .= "clickable-dot ";
		}
		
		$dotId = $id . $i;
		$dot = <<<EOQ
<img src="$src" class="$classes" id="$dotId" group="$options[group]" stat="$id" value="$i" />
EOQ;

		$dots .= $dot;
	}
	
	if($options['editable']) {
		if(isset($options['minValue']) && ($options['minValue']) == 0) {
			$dots .= <<<EOQ
<img src="img/red_x.png" class="clickable-dot" stat="$id" group="$options[group]" stat="$id" value="0"/>
EOQ;
		}
		
		$dots .= <<<EOQ
<input type="text" name="$name" id="$id" value="$options[value]" class="short-text hidden-input $options[group]" />
EOQ;
	}
	
	return $dots;
}

function ConvertNameToId($name) {
	$id = "";
	for($index = 0; $index < strlen($name); $index++) {
		$character = $name[$index];
		if(ctype_upper($character)){
			if($index === 0) {
				$id .= strtolower($character);
			}
			else {
				if(ctype_upper($name[$index-1])) {
					$id .= strtolower($character);
				}
				else {
					$id .= "-" . strtolower($character);
				}
			}
		}
		else {
			$id .= $character;
		}
	}
	return $id;
}

function SetupInput($tag, $options) {
	if($options['options'] && is_array($options['options'])) {
		foreach($options['options'] as $key => $value) {
			$tag = str_replace(">", " $key='$value'>", $tag);
		}
	}
	
	if($options['name']) {
		$tag = str_replace(">", " name='$options[name]'>", $tag);
		$id = ConvertNameToId($options['name']);
		$tag = str_replace(">", " id='$id' >", $tag);
	}
	
	return $tag;
}
?>
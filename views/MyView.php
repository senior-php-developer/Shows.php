<?php

class MyView extends View {

	function cb($matches) {
		if (array_key_exists($matches[1], $this->data))
		return $this->data[$matches[1]];
	}
	
	public function render($template) {
		$contents = file_get_contents($this->templatesDirectory() . 'header.tpl');
		$contents .= file_get_contents($this->templatesDirectory() . $template);
		$pattern = '/\{(\w+)\}/i';
		echo preg_replace_callback($pattern, array($this,'cb'), $contents);
	}
	

}


?>
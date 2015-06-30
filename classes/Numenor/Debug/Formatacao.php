<?php

namespace Numenor\Debug;

class Formatacao {
	
	const TEMPLATE_HTML_RAW = '<pre>{{VALOR}}</pre>';
	const TEMPLATE_HTML_FANCY = '<div class="output output-fancy">{{VALOR}}</div>';
	const TEMPLATE_HTML_COMENT = '<!-- ' . "\n" . '{{VALOR}}' . "\n" . ' -->';
	const TEMPLATE_RAW = '================================' . "\n" . '{{VALOR}}' . "\n"; 
	
	public static function htmlRaw($valor) {
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_HTML_RAW);
	}
	
	public static function htmlFancy($valor) {
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_HTML_FANCY);
	}
	
	public static function htmlComentario($valor) {
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_HTML_COMENT);
	}
	
	public static function raw($valor) {
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_RAW);
	}
}
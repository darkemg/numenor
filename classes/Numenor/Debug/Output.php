<?php

namespace Numenor\Debug;

class Output {
	
	public static function htmlRaw($valor, $interromperScript = false) {
		echo Formatacao::htmlRaw($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	public static function htmlFancy($valor, $interromperScript = false) {
		echo Formatacao::htmlFancy($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	public static function htmlComentario($valor, $interromperScript = false) {
		echo Formatacao::htmlComentario($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	public static function raw($valor, $interromperScript = false) {
		echo Formatacao::raw($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	public static function arquivoRaw($arquivo, $valor, $append = false, $interromperScript = false) {
		$fp = ($append)
				? fopen($arquivo, 'a+')
				: fopen($arquivo, 'w+');
		fwrite($fp, Formatacao::raw($valor));
		if ($interromperScript) {
			exit();
		}
	}
}
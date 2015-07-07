<?php
/**
 * Realiza a saída de informações de debug, formatadas de acordo com o template
 * correspondente ao formato escolhido.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Debug
 */
namespace Numenor\Debug;

class Output {
	
	/**
	 * Exibe o debug no formato HTML cru.
	 * 
	 * @access public
	 * @static
	 * @param string $valor Valor a ser depurado
	 * @param boolean $interromperScript Indica se o script deve ser interrompido após 
	 * a exibição do valor. 
	 */
	public static function htmlRaw($valor, $interromperScript = false) {
		echo Formatacao::htmlRaw($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	/**
	 * Exibe o debug no formato HTML decorado.
	 *
	 * @access public
	 * @static
	 * @param string $valor Valor a ser depurado
	 * @param boolean $interromperScript Indica se o script deve ser interrompido após
	 * a exibição do valor.
	 */
	public static function htmlFancy($valor, $interromperScript = false) {
		echo Formatacao::htmlFancy($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	/**
	 * Exibe o debug no formato de comentário HTML.
	 *
	 * @access public
	 * @static
	 * @param string $valor Valor a ser depurado
	 * @param boolean $interromperScript Indica se o script deve ser interrompido após
	 * a exibição do valor.
	 */
	public static function htmlComentario($valor, $interromperScript = false) {
		echo Formatacao::htmlComentario($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	/**
	 * Exibe o debug no formato texto cru.
	 *
	 * @access public
	 * @static
	 * @param string $valor Valor a ser depurado
	 * @param boolean $interromperScript Indica se o script deve ser interrompido após
	 * a exibição do valor.
	 */
	public static function raw($valor, $interromperScript = false) {
		echo Formatacao::raw($valor);
		if ($interromperScript) {
			exit();
		}
	}
	
	/**
	 * Faz a depuração do valor para um arquivo de texto.
	 * Se o arquivo não existir, ele é criado. Se ele já existir, o arquivo é aberto.
	 *
	 * @access public
	 * @static
	 * @param string $arquivo Endereço físico do arquivo.
	 * @param string $valor Valor a ser depurado.
	 * @param boolean $append Indica se o arquivo deve ser aberto para anexação (preservando
	 * o conteúdo existente) ou não (o arquivo é apagado antes da escrita dos dados).
	 * @param boolean $interromperScript Indica se o script deve ser interrompido após
	 * a exibição do valor.
	 */
	public static function arquivoRaw($arquivo, $valor, $append = false, $interromperScript = false) {
		$fp = ($append)
				? fopen($arquivo, 'a+')
				: fopen($arquivo, 'w+');
		fwrite($fp, Formatacao::raw($valor));
		fclose($fp);
		if ($interromperScript) {
			exit();
		}
	}
}
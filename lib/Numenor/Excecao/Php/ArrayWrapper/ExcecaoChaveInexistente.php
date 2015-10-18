<?php
/**
 * Exceção levantada pelo sistema quando um método da classe \Numenor\Php\ArrayWrapper é invocado com uma chave que não existe
 * no array.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoChaveInexistente extends \Numenor\Excecao\ExcecaoErroUso {
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada).
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('Não existe nenhum item no array que corresponda à chave informada.', 
				self::CODE_ARRAYWRAPPER_CHAVEINEXISTENTE, $previous);
	}
}
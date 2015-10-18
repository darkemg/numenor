<?php
/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::fill() é invocado com o
 * parâmetro $startIndex negativo.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoArrayFillIndiceInvalido extends \Numenor\Excecao\ExcecaoErroUso {

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O parâmetro $startIndex não pode ser negativo.', self::CODE_FN_ARRAYFILL_INVALIDINDEX, $previous);
	}
}
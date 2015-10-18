<?php
/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::getColumnValues é invocado com o
 * parâmetro $numberItems negativo.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoArrayFillTamanhoInvalido extends \Numenor\Excecao\ExcecaoErroUso {

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O parâmetro $numberItems não pode ser menor do que 1.', self::CODE_FN_ARRAYFILL_NUMITEMS, $previous);
	}
}
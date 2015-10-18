<?php
/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::filter é invocado com o
 * parâmetro $flag inválido (nenhum dos seguintes valores: 0, ARRAY_FILTER_USE_KEY, ARRAY_FILTER_USE_BOTH)
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoArrayFilterFlagInvalida extends \Numenor\Excecao\ExcecaoErroUso {

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O método filter requer que o parâmetro $flag, caso informado, seja igual a uma das constantes: ARRAY_FILTER_USE_KEY ou ARRAY_FILTER_USE_BOTH.',
			self::CODE_FN_ARRAYFILTER_INVALIDFLAG);
	}
}
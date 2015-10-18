<?php
/**
 * Exceção levantada pelo sistema quando os arrays informados para o método \Numenor\Php\ArrayWrapper::combine
 * têm tamanhos diferentes.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoArrayCombineTamanhosIncompativeis extends \Numenor\Excecao\ExcecaoErroUso {
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O método combine requer que dois arrays com o mesmo tamanho.', self::CODE_FN_ARRAYCOMBINE_INVALIDSIZE, $previous);
	}
}
<?php
/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::changeCaseKey é invocado com o
 * parâmetro $case diferente de um dos valores permitidos (as constantes CASE_LOWER e CASE_UPPER).
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoArrayChangeCaseKeyInvalido extends \Numenor\Excecao\ExcecaoErroUso {
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O método changeCaseKey requer que o parâmetro $case seja igual auma das constantes: CASE_LOWER ou CASE_UPPER.', 
				self::CODE_FN_ARRAYCHANGECASEKEY_INVALID, $previous);
	}
}
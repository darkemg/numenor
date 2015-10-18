<?php
/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::getColumnValues é invocado com o
 * parâmetro $columnKey de um tipo inválido (deve ser string ou integer)
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoArrayColumnChaveInvalida extends \Numenor\Excecao\ExcecaoErroUso {
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O método getColumnValues requer que o parâmetro $columnKey seja do tipo {String} ou {Integer}.', self::CODE_FN_ARRAYCOLUMN_INVALIDKEY, $previous);
	}
}
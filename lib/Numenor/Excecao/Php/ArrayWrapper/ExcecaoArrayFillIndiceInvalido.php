<?php
namespace Numenor\Excecao\Php\ArrayWrapper;

/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::criar() é invocado com o parâmetro
 * $startIndex negativo.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao\Php\ArrayWrapper
 */
class ExcecaoArrayFillIndiceInvalido extends ExcecaoArray
{

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null)
	{
		parent::__construct('O índice de início do array não pode ser negativo.', static::DEFAULT_CODE, $previous);
	}
}
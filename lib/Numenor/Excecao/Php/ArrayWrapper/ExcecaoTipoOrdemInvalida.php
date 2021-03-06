<?php
namespace Numenor\Excecao\Php\ArrayWrapper;

/**
 * Exceção levantada pelo sistema quando um método de ordenação da classe \Numenor\Php\ArrayWrapper é invocado com o
 * parâmetro $tipoOrdem de um valor não reconhecido (uma das constantes SORT_REGULAR, SORT_NUMERIC, SORT_STRING,
 * SORT_STRING_LOCALE ou SORT_NATURAL).
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao\Php\ArrayWrapper
 */
class ExcecaoTipoOrdemInvalida extends ExcecaoArray
{
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null)
	{
		parent::__construct(
			'O método de ordenação requer que o $tipoOrdem informado seja um dos valores padrão de ordenação.', 
			static::DEFAULT_CODE,
			$previous
		);
	}
}
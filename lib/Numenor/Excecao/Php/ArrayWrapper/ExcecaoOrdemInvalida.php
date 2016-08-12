<?php
namespace Numenor\Excecao\Php\ArrayWrapper;

/**
 * Exceção levantada pelo sistema quando um método de ordenação da classe \Numenor\Php\ArrayWrapper é invocado com o
 * parâmetro $ordenacao de um valor não reconhecido ('ASC' ou 'DESC').
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao\Php\ArrayWrapper
 */
class ExcecaoOrdemInvalida extends ExcecaoArray
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
			'O método de ordenação requer que a $ordem informada seja crescente ou decrescente.', 
			static::DEFAULT_CODE,
			$previous
		);
	}
}
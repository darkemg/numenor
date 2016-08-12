<?php
namespace Numenor\Excecao\Php\ArrayWrapper;

/**
 * Exceção levantada pelo sistema quando um método da classe \Numenor\Php\ArrayWrapper é invocado com uma chave de um
 * tipo inválido como chave de um array (ou seja, não é um inteiro, nem uma string, nem um objeto que implementa o
 * método __toString()).
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao\Php\ArrayWrapper
 */
class ExcecaoChaveInvalida extends ExcecaoArray
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
		    'O método invocado requer que o parâmetro $chave seja de um tipo válido: {String}, {Integer}, ou um objeto representável como uma string.', 
			static::DEFAULT_CODE,
			$previous
	    );
	}
}
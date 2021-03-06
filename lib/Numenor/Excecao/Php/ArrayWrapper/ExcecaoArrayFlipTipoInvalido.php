<?php
namespace Numenor\Excecao\Php\ArrayWrapper;

/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::flip() é invocado em um array cujos valores
 * não podem ser convertidos para string ou inteiros (os tipos de valores aceitos como chaves de um array pela função
 * array_flip()).
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao\Php\ArrayWrapper
 */
class ExcecaoArrayFlipTipoInvalido extends ExcecaoArray
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
		    'O método ArrayWrapper::flip() não pode ser invocado sobre arrays com valores que não pode ser convertidos para {String} ou {Integer}.',
			static::DEFAULT_CODE,
			$previous
	    );
	}
}
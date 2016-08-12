<?php
namespace Numenor\Excecao\Php\ArrayWrapper;

/**
 * Exceção levantada pelo sistema quando o método \Numenor\Php\ArrayWrapper::preencher() é invocado com o parâmetro
 * $novoTamanho inválido (um número cujo valor absoluto é menor ou igual ao tamanho atual do array).
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao\Php\ArrayWrapper
 */
class ExcecaoArrayPadTamanhoInvalido extends ExcecaoArray
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
		    'O novo tamanho do array informado para o método ArrayWrapper::preencher() não pode ser menor do que o tamanho atual do array.',
			static::DEFAULT_CODE,
			$previous
	    );
	}
}
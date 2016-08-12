<?php
namespace Numenor\Excecao\Php\ArrayWrapper;

/**
 * Exceção levantada pelo sistema quando os arrays informados para o método \Numenor\Php\ArrayWrapper::combinar()
 * têm tamanhos diferentes.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao\Php\ArrayWrapper
 */
class ExcecaoArrayCombineTamanhosIncompativeis extends ExcecaoArray 
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
		    'O método ArrayWrapper::combinar() requer dois arrays com o mesmo tamanho.', 
			static::DEFAULT_CODE, 
			$previous
	    );
	}
}
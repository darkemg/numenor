<?php
/**
 * Exceção levantada pelo método \Numenor\Php\ArrayWrapper::encontrarItem() quando o item informado não é encontrado 
 * no array.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/ArrayWrapper
 */
namespace Numenor\Excecao\Php\ArrayWrapper;
class ExcecaoItemNaoEncontrado extends \Numenor\Excecao\ExcecaoErroLogico {
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O item informado não foi encontrado no array.', 
			static::DEFAULT_CODE,
			$previous);
	}
}
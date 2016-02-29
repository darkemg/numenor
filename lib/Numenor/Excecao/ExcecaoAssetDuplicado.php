<?php
/**
 * Exceção levantada pelo sistema um asset já existente é adicionado à controladora de assets.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;

class ExcecaoAssetDuplicado extends ExcecaoErroUso {
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O algoritmo de hash informado é inválido ou não está disponível nesta versão do PHP.', static::DEFAULT_CODE, $previous);
	}
}
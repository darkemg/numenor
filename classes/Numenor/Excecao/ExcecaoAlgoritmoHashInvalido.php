<?php
/**
 * Exceção levantada pelo sistema quando o nome do algoritmo de hash informado para a classe Checksum é inválido ou
 * não está instalado na versão atual do PHP.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;

class ExcecaoAlgoritmoHashInvalido extends ExcecaoErroUso {
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O algoritmo de hash informado é inválido ou não está disponível nesta versão do PHP.', self::CODE_ALGORITMO_HASH_INVALIDO, $previous);
	}
}
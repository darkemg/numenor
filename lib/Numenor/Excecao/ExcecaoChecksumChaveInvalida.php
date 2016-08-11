<?php
/**
 * Exceção levantada pelo sistema quando a chave informada para a classe Checksum é nula ou inválida.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;

class ExcecaoChecksumChaveInvalida extends ExcecaoErroUso
{
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null)
	{
		parent::__construct('A chave informada para o gerador de checksums é inválida', 401, $previous);
	}
}
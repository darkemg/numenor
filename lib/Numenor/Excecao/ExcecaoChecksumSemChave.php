<?php
namespace Numenor\Excecao;

/**
 * Exceção levantada pelo sistema quando a geração de um checksum é solicitada sem que a chave tenha sido
 * definida anteriormente.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao
 */
class ExcecaoChecksumSemChave extends ExcecaoErroUso
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
			'A chave do gerador de checksums ainda não foi definida. Você deve informá-la através do método Checksum::setChave',
			static::DEFAULT_CODE,
			$previous
		);
	}
}
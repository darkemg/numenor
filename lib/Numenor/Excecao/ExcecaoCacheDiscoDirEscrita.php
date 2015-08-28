<?php
/**
 * Exceção levantada pelo sistema quando o adaptador de cache da classe CacheDisco é criado, mas o diretório
 * de cache informado não possui permissão de escrita
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;
class ExcecaoCacheDiscoDirEscrita extends ExcecaoErroUso {

	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('O diretório de cache para este adaptador existe ou não possui permissão de escrita.', self::CODE_CACHE_DISCO_DIR_ESCRITA, $previous);
	}
}
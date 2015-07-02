<?php
/**
 * Exceção levantada pelo sistema quando o adaptador de cache da classe CacheDisco é criado, mas o diretório
 * de cache informado não possui permissão de escrita
 *
 * @author Darke M. Goulart <darke.goulart@gmail.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;
class ExcecaoCacheDiscoDirEscrita extends ExcecaoErroUso {

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct('O diretório de cache para este adaptador existe ou não possui permissão de escrita.', self::CODE_CACHE_DISCO_DIR_ESCRITA, $previous);
	}
}
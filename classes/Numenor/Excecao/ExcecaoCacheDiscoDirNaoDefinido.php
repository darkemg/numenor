<?php
/**
 * Exceção levantada pelo sistema quando o adaptador de cache da classe CacheDisco é criado, mas o diretório
 * de cache não foi definido (seja pelo bootstrap da aplicação, seja pelo parâmetro $diretorio no construtor do
 * adaptador de cache)
 * 
 * @author Darke M. Goulart <darke.goulart@gmail.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;
class ExcecaoCacheDiscoDirNaoDefinido extends ExcecaoErroUso {
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 */
	public function __construct() {
		parent::__construct('O diretório de cache para este adaptador não foi definido (nem no boostrap, nem na instanciação).', self::CODE_CACHE_DISCO_DIR_NAO_DEFINIDO, $previous);
	}
}
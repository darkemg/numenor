<?php

namespace Numenor\Excecao;

/**
 * Exceção levantada pelo sistema quando o adaptador de cache da classe CacheDisco é criado, mas o diretório
 * de cache não foi definido (seja pelo bootstrap da aplicação, seja pelo parâmetro $diretorio no construtor do
 * adaptador de cache)
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao
 */
class ExcecaoCacheDiscoDirNaoDefinido extends ExcecaoErroUso
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
			'O diretório de cache para este adaptador não foi definido (nem no boostrap, nem na instanciação).',
			static::DEFAULT_CODE,
			$previous
		);
	}
}
<?php
/**
 * Exceção levantada pelo sistema quando um asset já existente é adicionado à controladora de assets.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;

use Numenor\Html\Asset;

class ExcecaoAssetDuplicado extends ExcecaoErroUso
{
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param \Numenor\Html\Asset $asset Asset referenciado que causou o erro.
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada).
	 */
	public function __construct(Asset $asset, \Exception $previous = null)
	{
		parent::__construct(
			'Asset duplicado: ' . $asset->getCaminhoArquivo() . ' já foi adicionado ao controlador',
			static::DEFAULT_CODE,
			$previous
		);
	}
}
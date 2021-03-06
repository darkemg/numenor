<?php
namespace Numenor\Html;

/**
 * Classe representando um arquivo Javascript incluído em uma página HTML.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Html
 */
class Javascript extends Asset
{
	
	/**
	 * {@inheritDoc}
	 */
	public function __construct(string $caminhoArquivo, bool $compactavel = true, bool $concatenavel = true)
	{
		parent::__construct($caminhoArquivo, $compactavel, $concatenavel);
	}
}
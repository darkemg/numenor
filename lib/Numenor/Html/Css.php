<?php
/**
 * Classe representando um arquivo CSS incluído em uma página HTML.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;

class Css extends Asset
{
	
	/**
	 * {@inheritDoc}
	 */
	public function __construct(string $caminhoArquivo, bool $compactavel = true, bool $concatenavel = true)
	{
		parent::__construct($caminhoArquivo, $compactavel, $concatenavel);
	}
}
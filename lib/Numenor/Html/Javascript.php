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
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param string $caminhoArquivo URL do arquivo do asset.
	 * @param boolean $compactavel Indica se o asset pode ser compactado através de um minificador. 
	 * @param boolean $concatenavel Infica se o asset pode ser concatenado com outros assets que possuem a mesma
	 * propriedade.
	 */
	public function __construct(string $caminhoArquivo, bool $compactavel = true, bool $concatenavel = true)
	{
		parent::__construct($caminhoArquivo, $compactavel, $concatenavel);
	}
}
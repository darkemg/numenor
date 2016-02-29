<?php
/**
 * Classe representando um arquivo Javascript incluído em uma página HTML.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;

class Javascript extends Asset {
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param string $caminhoArquivo Caminho físico do arquivo Javascript
	 * @param boolean $compactavel Indica se o arquivo Javascript deve ser minificado.
	 * @param boolean $concatenavel Indica se o arquivo Javascript deve ser concatenado.
	 */
	public function __construct(string $caminhoArquivo, bool $compactavel = true, bool $concatenavel = true) {
		parent::__construct($caminhoArquivo, $compactavel, $concatenavel);
	}
}
<?php
/**
 * Classe representando um arquivo CSS incluído em uma página HTML.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;

class Css extends Asset {
	
	/**
	 * Método construtor da classe.
	 *
	 * @access public
	 * @param string $url URL do arquivo CSS.
	 * @param boolean $compactavel Indica se o arquivo CSS deve ser minificado.
	 * @param boolean $concatenavel Indica se o arquivo CSS deve ser concatenado.
	 */
	public function __construct($url, $compactavel = true, $concatenavel = true) {
		parent::__construct($url, $compactavel, $concatenavel);
	}
}
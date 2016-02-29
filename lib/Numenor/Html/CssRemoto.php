<?php
/**
 * Classe representando um arquivo CSS remoto incluído em uma página HTML.
 * 
 * Arquivos CSS remotos não são minificaveis nem concatenáveis, e podem definir um snippet de fallback para ser
 * executado caso o arquivo não possa ser incluído a partir da origem remota.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;
class CssRemoto extends Css {
	
	use Remoto;
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param string $url URL do arquivo Javascript.
	 * @param string $integrityCheck Informação para checagem de integridade do asset.
	 * @param string $crossorigin Identificação do tipo de requisição crossorigin do asset.
	 */
	public function __construct($url, $integrityCheck = '', $crossorigin = 'anonymous') {
		parent::__construct(null, false, false);
		$this->url = $url;
		$this->integrityCheck = $integrityCheck;
		$this->crossorigin = $crossorigin;
	}
	
	/**
	 * Método mágico de conversão do objeto para string.
	 *
	 * @access public
	 * @return string A representação do objeto como string.
	 */
	public function __toString() {
		$link = '<link rel="stylesheet" href="'. $this->url .'"';
		if (!empty($this->integrityCheck)) {
			$link .= ' integrity="'. $this->integrityCheck . '"';
		}
		if (!empty($this->crossorigin)) {
			$link .= ' crossorigin="' . $this->crossorigin . '"';
		}
		$link .= '>' . \PHP_EOL;
		return $link;
	}
}
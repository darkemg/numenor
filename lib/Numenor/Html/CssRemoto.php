<?php
namespace Numenor\Html;

/**
 * Classe representando um arquivo CSS remoto incluído em uma página HTML.
 *
 * Arquivos CSS remotos não são minificaveis nem concatenáveis, e podem definir um snippet de fallback para ser
 * executado caso o arquivo não possa ser incluído a partir da origem remota.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Html
 */
class CssRemoto extends Css
{
	
	use Remoto;
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param string $url URL do arquivo Javascript.
	 * @param string $integrityCheck Informação para checagem de integridade do asset.
	 * @param string $crossorigin Identificação do tipo de requisição crossorigin do asset.
	 */
	public function __construct(string $url, string $integrityCheck = '', string $crossorigin = 'anonymous')
	{
		parent::__construct('', false, false);
		$this->url = $url;
		$this->integrityCheck = $integrityCheck;
		$this->crossorigin = $crossorigin;
	}
	
	/**
	 * Método mágico de conversão do objeto para string.
	 * 
	 * Gera o elemento HTML para inclusão do arquivo CSS no documento.
	 *
	 * @access public
	 * @return string A representação do objeto como string.
	 */
	public function __toString() : string
	{
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
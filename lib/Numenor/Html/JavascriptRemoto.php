<?php
/**
 * Classe representando um arquivo Javascript remoto incluído em uma página HTML.
 *
 * Arquivos Javascript remoto não são minificaveis nem concatenáveis, e podem definir um script de fallback para ser
 * executado caso o arquivo não possa ser incluído a partir da origem remota.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;
class JavascriptRemoto extends Javascript {
	
	/**
	 * Snippet de fallback do script remoto.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $fallback;
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param string $url URL do arquivo Javascript.
	 * @param string $fallback Snippet de fallback do script remoto.
	 */
	public function __construct($url, $fallback) {
		parent::__construct($url, false, false);
		$this->fallback = $fallback;
	}
	
	/**
	 * Gera o snippet de inclusão do script na página.
	 * 
	 * @access public
	 * @return string O snippet de código para inclusão do arquivo na página.
	 */
	public function gerarSnippetInclusao() {
		$script = '<script src="'. $this->url .'"></script>' . \PHP_EOL;
		$script .= $this->fallback;
		return $script;
	}
}
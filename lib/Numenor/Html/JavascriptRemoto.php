<?php
namespace Numenor\Html;

/**
 * Classe representando um arquivo Javascript remoto incluído em uma página HTML.
 *
 * Arquivos Javascript remotos não são minificáveis nem concatenáveis, e podem definir um script de fallback para ser
 * executado caso o arquivo não possa ser incluído a partir da origem remota.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Html
 */
class JavascriptRemoto extends Javascript
{
	
	use Remoto;
	
	/**
	 * Snippet de fallback do asset remoto.
	 * 
	 * Esta informação normalmente é utilizada para executar alguma instrução caso não seja possível carregar o arquivo 
	 * remoto. 
	 * 
	 * @access protected
	 * @var string
	 */
	protected $fallback;
	
	/**
	 * {@inheritDoc}
	 * @param string $integrityCheck Informação para checagem de integridade do script remoto.
	 * @param string $crossorigin Identificação do tipo de requisição crossorigin do script remoto.
	 */
	public function __construct(string $url, string $fallback = '', string $integrityCheck = '', string $crossorigin = 'anonymous')
	{
		parent::__construct('', false, false);
		$this->url = $url;
		$this->fallback = $fallback;
		$this->integrityCheck = $integrityCheck;
		$this->crossorigin = $crossorigin;
	}
	
	/**
	 * Método mágico de conversão do objeto para string.
	 * 
	 * Gera o elemento HTML para inclusão do arquivo JS no documento.
	 *
	 * @access public
	 * @return string A representação do objeto como string.
	 */
	public function __toString() : string
	{
		$script = '<script src="'. $this->url .'"';
		if (!empty($this->integrityCheck)) {
			$script .= ' integrity="'. $this->integrityCheck . '"';
		}
		if (!empty($this->crossorigin)) {
			$script .= ' crossorigin="' . $this->crossorigin . '"';
		}
		$script .= '></script>' . \PHP_EOL;
		if (!empty($this->fallback)) {
			$script .= $this->fallback . \PHP_EOL;
		}
		return $script;
	}
}

<?php

namespace Numenor\Html;

/**
 * Trait de arquivos de asset remoto.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Html
 */
trait Remoto
{
	
	/**
	 * URL remota de origem do arquivo do asset.
	 *
	 * @access protected
	 * @var string
	 */
	protected $url;
	/**
	 * Informação para checagem de integridade do asset, segundo a specificação de Subresource Integrity
	 * (https://www.w3.org/TR/SRI/).
	 *
	 * @access protected
	 * @var string
	 */
	protected $integrityCheck;
	/**
	 * Identificação do tipo de requisição crossorigin do asset ('anonymous' ou 'use-credentials').
	 *
	 * @access protected
	 * @var string
	 */
	protected $crossorigin;
	
	/**
	 * Método getter da URL de origem do asset.
	 *
	 * @access public
	 * @return string URL de origem do asset.
	 */
	public function getCaminhoArquivo() : string
	{
		return $this->url;
	}
	
	/**
	 * Gera o snippet de inclusão do asset remoto na página.
	 *
	 * @access public
	 * @return string O snippet de código para inclusão do arquivo na página.
	 */
	public function gerarSnippetInclusao() : string
	{
		$snippet = (string) $this;
		return $snippet;
	}
}
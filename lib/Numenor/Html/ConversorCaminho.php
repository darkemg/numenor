<?php
/**
 * Conversor de diretórios relativos contidos em arquivos CSS.
 *
 * Exemplo:
 *     ../../images/icon.jpg (relativo a /css/imports/icons.css)
 * se torna
 *     ../images/icon.jpg (relative a /css/minified.css)
 *
 * Esta classe estende a funcionalidade da superclasse, oferecendo uma interface para a mudança dos diretórios de
 * origem e destino entre uma operação de conversão e outra, permitindo que o objeto possa ser reaproveitado para
 * processar diversos arquivos separadamente.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 * @see \MatthiasMullie\PathConverter\Converter
 */
namespace Numenor\Html;

use MatthiasMullie\PathConverter\Converter;

class ConversorCaminho extends Converter
{
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param string $origem O diretório de origem dos links.
	 * @param string $destino O diretório de destino dos links.
	 */
	public function __construct(string $origem = '', string $destino = '')
	{
		parent::__construct($origem, $destino);
	}
	
	/**
	 * Define o diretório de origem dos links relativos.
	 * 
	 * @access public
	 * @param string $origem O diretório de origem dos links.
	 * @returns \Numenor\Html\ConversorCaminho Instância do próprio objeto para encadeamento
	 */
	public function setOrigem(string $origem) : self
	{
		$origem = $this->normalize($origem);
        $origem = $this->dirname($origem);
		$this->from = $origem;
		return $this;
	}
	
	/**
	 * Define o diretório de destino dos links relativos.
	 *
	 * @access public
	 * @param string $origem O diretório de origem dos links.
	 * @returns \Numenor\Html\ConversorCaminho Instância do próprio objeto para encadeamento
	 */
	public function setDestino(string $destino) : self
	{
		$destino = $this->normalize($destino);
		$destino = $this->dirname($destino);
		$this->to = $destino;
		return $this;
	}
}

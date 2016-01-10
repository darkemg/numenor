<?php
/**
 * Classe abstrata representando um assets para inclusão em uma página HTML.
 *
 * @abstract
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;
abstract class Asset {
	
	/**
	 * URL do arquivo do asset.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $url;
	/**
	 * Indica se o asset é minificável.
	 * 
	 * @access protected
	 * @var boolean
	 */
	protected $compactavel;
	/**
	 * Indica se o asset é concatenável.
	 * 
	 * @access protected
	 * @var boolean
	 */
	protected $concatenavel;
	
	/**
	 * Método construtor da classe
	 * @param unknown $url
	 * @param string $compactavel
	 * @param string $concatenavel
	 */
	public function __construct($url, $compactavel = true, $concatenavel = true) {
		$this->url = $url;
		$this->compactavel = $compactavel;
		$this->concatenavel = $concatenavel;
	}
	
	/**
	 * Método mágico de conversão do objeto para string.
	 * 
	 * @access public
	 * @return string A representação do objeto como string.
	 */
	public function __toString() {
		return $this->url;
	}
	
	/**
	 * Método getter da URL do asset.
	 * 
	 * @access public
	 * @return string URL do asset.
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * Indica se o asset é minificável.
	 * 
	 * @access public
	 * @return boolean O asset é minificável?
	 */
	public function isCompactavel()  {
		return $this->compactavel;
	}
	
	/**
	 * Indica se o asset é concatenável.
	 * 
	 * @access public
	 * @return boolean O asset é concatenável?
	 */
	public function isConcatenavel() {
		return $this->concatenavel;
	}	
}
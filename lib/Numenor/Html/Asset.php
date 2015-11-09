<?php
namespace Numenor\Html;
abstract class Asset {
	
	protected $url;
	protected $prioridade;
	protected $compactavel;
	protected $concatenavel;
	protected $remoto;
	
	public function __construct($url, $prioridade, Remoto $remoto = null, $compactavel = true, $concatenavel = true) {
		$this->url = $url;
		$this->prioridade = $prioridade;
		$this->compactavel = $compactavel;
		$this->concatenavel = $concatenavel;
	}
	
	public function __toString() {
		return $this->url;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getPrioridade() {
		return $this->prioridade;
	}
	
	public function isCompactavel()  {
		return $this->compactavel;
	}
	
	public function isConcatenavel() {
		return $this->concatenavel;
	}
	
	public function isRemoto() {
		return !is_null($this->remoto);
	}
	
	public function getUrlFallback() {
		if ($this->isRemoto()) {
			$retorno = $this->remoto->getUrlFallback();
		} else {
			$retorno = '';
		}
		return $retorno;
	}
	
}
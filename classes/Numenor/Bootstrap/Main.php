<?php
/**
 * Classe singleton de boostrap da biblioteca Numenor.
 * 
 * Expõe publicamente apenas o método estático de inicialização dos demais componentes da biblioteca.
 */
namespace Numenor\Bootstrap;
use Numenor\Seguranca\Checksum;

class Main {
		
	private static $main = null;
	
	protected function __construct() {
		
	}
	
	private function __clone() {
		
	}
	
	private function __wakeup() {
		
	}
	
	protected function configurarArray(array $config = array()) {
		$dados = new \stdClass();
		// 
		if (array_key_exists('idioma', $config)) {
				
		}
		// 
		if (array_key_exists('chaveChecksum', $config)) {
			$dados->chaveChecksum = $config['chaveChecksum'];
		}
		return $dados;
	}
	
	protected function configurarYaml($config) {
		
	}
	
	public static function getInstance() {
		if (static::$main === null) {
			static::$main = new static();
		}
		return static::$main;
	}
	
	/**
	 * Realiza a inicialização prévia dos componentes da biblioteca Numenor que precisam
	 * de algum tipo de bootstrap configurável.
	 * 
	 *  @access public
	 *  @static
	 * 	@param array $config Array associativo com os pares chave => valor de configuração
	 * da biblioteca
	 */
	public static function init($config = null) {
		if ($config === null) {
			return;
		}
		if (is_array($config)) {
			$dados = static::configurarArray($config);
		}
		// inicializa a classe de geração de checksums com a chave da aplicação
		if (isset($dados->chaveChecksum)) {
			Checksum::setChave($dados->chaveChecksum);
		}
	}
	
}
<?php
/**
 * Classe de controle do adaptador de cache Zend\Cache\Storage\Adapter\Memory, que utiliza a própria memória RAM do
 * servidor para armazenar o cache como um array.
 * 
 * Este adaptador de cache é volátil; o cache é persistido apenas até o final da execução do script, como qualquer
 * variável do PHP, sendo recomendado apenas para armazenar dados que não necessitem de persistência.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Cache
 */
namespace Numenor\Cache;
use Zend\Cache\StorageFactory;

class cacheMemoria extends CacheAbstrato {
	
	/**
	 * Limite de memória, em bytes, que o cache pode atingir.
	 * 
	 * Este valor não é apenas o valor do cache em si, e sim a soma de toda a memória consumida pelo script PHP.
	 * 
	 * @access private
	 * @var int
	 */
	private $limiteMemoria;
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param string $namespace Identificador de namespace do cache
	 * @param int $duracao Validade do cache, em segundos (0 = cache não expira)
	 * @param int $limiteMemoria Limite de memória, em bytes, que o cache pode fazer o script PHP atingir.
	 */
	public function __construct($namespace, $duracao, $limiteMemoria) {
		parent::__construct($namespace, $duracao);
		$this->limiteMemoria = $limiteMemoria;
		$this->adapter = StorageFactory::factory([
				'adapter' => [
						'name' => 'memory',
						'options' => [
								'namespace' => $this->namespace,
								'ttl' => $this->duracao,
								'namespace_separator' => '$',
								'memory_limit' => $limiteMemoria
						]
				],
				'plugins' => [
						'exception_handler' => [
								'throw_exceptions' => true
						]
				]
		]);
	}
	
	
}
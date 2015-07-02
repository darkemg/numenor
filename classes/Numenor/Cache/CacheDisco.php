<?php
/**
 * Classe de controle do adaptador de cache Zend\Cache\Storage\Adapter\Filesystem,
 * que utiliza o sistema de arquivos do servidor para armazenar o cache em disco.
 * Embora este adaptador de cache seja o mais comumente utilizado pela conveniência
 * (os registros do cache ficam armazenados em disco, e não é necessário instalar
 * nenhuma extensão para que funcione), tenha em mente que operações de I/O
 * (leitura/escrita em disco) podem ser demoradas em caso de demanda simultânea muito 
 * grande.
 * 
 *  @author Darke M. Goulart <darkemg@users.noreply.github.com>
 *  @package Numenor/Cache
 */
namespace Numenor\Cache;
use Numenor\Excecao\ExcecaoCacheDiscoDirNaoDefinido;
use Zend\Cache\StorageFactory;
use Numenor\Excecao\ExcecaoCacheDiscoDirEscrita;

class CacheDisco {
	
	/**
	 * Diretório padrão de armazenamento dos arquivos de cache.
	 * As instâncias da classe CacheDisco podem sobrescrever este diretório em seus
	 * construtores.
	 * @access private
	 * @static
	 * @var string
	 */
	private static $diretorioPadrao;
	/**
	 * Identificador de namespace do cache (para evitar colisão com outras instâncias).
	 * @access private
	 * @var string
	 */
	private $namespace;
	/**
	 * Duração da validade do cache, em segundos. O valor 0 indica que o cache não expira.
	 * @access private
	 * @var int
	 */
	private $duracao;
	/**
	 * Modo de acesso ao cache ('r' = somente leitura, 'w' = somente escrita, 'rw' = leitura e 
	 * escrita).
	 * @access private
	 * @var string
	 */
	private $modo;
	/**
	 * Diretório onde os arquivos de cache serão armazeados.
	 * O diretório deve ter permissão de leitura, execução e escrita para o usuário do PHP 
	 * (normalmente 0700, mas nesse caso não será possível remover os arquivos de cache via
	 * FTP ou SSH a menos que se tenha acesso como super-usuário).
	 * @access private
	 * @var string
	 */
	private $diretorio;
	/**
	 * Em quantos subníveis de diretórios os arquivos de cache deverão ser subdivididos (espelhados)
	 * dentro do diretório de cache.
	 * Quando há muitos registros de cache diferentes (namespace + id + tags), pode ser necessário subdividir
	 * os mesmos em 2 ou mais subníveis de subdiretórios para não causar problemas na estrutura de
	 * arquivos do sistema operacional. No entanto, a divisão em subníveis torna a operação de 
	 * remover os registros por tag bastante demorada.
	 * @access private
	 * @var int
	 */
	private $nivel;
	/**
	 * Instância do objeto adaptador de cache.
	 * @access private
	 * @var \Zend\Cache\Storage\Adapter
	 */
	private $adapter;
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param string $namespace Identificador de namespace do cache
	 * @param int $duracao Validade do cache, em segundos (0 = cache não expira)
	 * @param string|null $diretorio Diretório onde os arquivos de cache serão armazenados
	 * @param int $nivel Em quantos subníveis de diretórios os arquivos de cache serão divididos
	 * (1 = não há subdivisão)
	 * @param string $modo Modo de acesso do cache ('r' = somente leitura, 'w' = somente escrita, 'rw' = 
	 * leitura e escrita) 
	 * @throws Numenor\Excecao\ExcecaoCacheDiscoDirNaoDefinido se tanto o parâmetro $diretorio quanto a propriedade 
	 * estática self::$diretorioPadrao não estiverem definidos
	 * @throws Numenor\Excecao\ExcecaoCacheDiscoDirEscrita se o diretório de cache informado não existir ou não 
	 * tiver permissão de escrita
	 */
	public function __construct($namespace, $duracao, $diretorio = null, $nivel = 1, $modo = 'rw') {
		$this->namespace = $namespace;
		$this->duracao = $duracao;
		$this->diretorio = !empty($diretorio)
				? $diretorio 
				: static::$diretorioPadrao;
		if (empty($this->diretorio)) {
			throw new ExcecaoCacheDiscoDirNaoDefinido();
		}
		if (!is_writable($this->diretorio)) {
			throw new ExcecaoCacheDiscoDirEscrita();
		}
		$this->nivel = $nivel;
		switch ($modo) {
			case 'rw':
				$readable = true;
				$writable = true;
			break;
			case 'r':
				$readable = true;
				$writable = false;
			break;
			case 'w':
				$readable = false;
				$writable = true;
			break;
			default:
				$readable = false;
				$writable = false;
		}
		$this->adapter = StorageFactory::factory(array(
				'adapter' => array(
						'name' => 'filesystem',
						'options' => array(
								'namespace' => $this->namespace,
								'ttl' => $this->duracao,
								'readable' => $readable,
								'writable' => $writable,
								'namespace_separator' => '$',
								'cache_dir' => $this->diretorio,
								'clear_stat_cache' => true,
								'dir_level' => $nivel,
								'dir_permission' => 0700,
								'file_locking' => true,
								'file_permission' => 0600,
								'key_pattern' => '/^[a-z0-9_\+\-]*$/Di',
								'no_atime' => true,
								'no_ctime' => true
						)
				),
				'plugins' => array(
						'exception_handler' => array(
								'throw_exceptions' => true
						),
						'Serializer'
				)
		));
	}
	
	/**
	 * Define o diretório padrão de armazenamento do cache em disco.
	 * 
	 * @access public
	 * @static
	 * @param string $diretorioPadrao Diretório de armazenamento do cache em disco
	 * @throws Numenor\Excecao\ExcecaoCacheDiscoDirEscrita se o diretório padrão informado
	 * não existir ou não tiver permissão de escrita 
	 */
	public static function setDiretorioPadrao($diretorioPadrao) {
		if (!is_writable($diretorioPadrao)) {
			throw new ExcecaoCacheDiscoDirEscrita();
		}
		static::$diretorioPadrao = $diretorioPadrao;
	}
	
	/**
	 * Recupera um item do cache, identificado pela chave.
	 * 
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @return mixed|null Item carregado do cache, ou NULL caso o item não exista
	 */
	public function getItem($key) {
		return $this->adapter->getItem($key);
	}
	
	/**
	 * Retorna a metadata de um item do cache, identificado pela chave
	 * 
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @return array|boolean Array associativo contendo os dados de metadata do item do cache,
	 * ou FALSE caso o item não exista
	 */
	public function getMetadata($key) {
		return $this->adapter->getMetadata($key);
	}
	
	/**
	 * Verifica se um item existe e está ativo no cache, identificado pela chave.
	 * 
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @return boolean O item existe e está ativo no cache?
	 */
	public function hasItem($key) {
		return $this->adapter->hasItem($key);
	}
	
	/**
	 * Armazena um item no cache, identificado por uma chave
	 * 
	 * @access public
	 * @param string $key Chave de identificação do item do cache 
	 * @param mixed $value Valor armazenado
	 * @return boolean O item foi armazenado no cache com sucesso? 
	 */
	public function setItem($key, $value) {
		return $this->adapter->setItem($key, $value);
	}
	
	/**
	 * Renova a validade do item do cache, identificado por uma chave.
	 * 
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @return boolean O item deve sua validade renovada com sucesso?
	 */
	public function touchItem($key) {
		return $this->adapter->touchItem($key);
	}
}
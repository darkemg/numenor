<?php
/**
 * Classe de controle do adaptador de cache Zend\Cache\Storage\Adapter\Filesystem, que utiliza o sistema de arquivos do 
 * servidor para armazenar o cache em disco.
 * 
 * Embora este adaptador de cache seja o mais comumente utilizado pela conveniência (os registros do cache ficam 
 * armazenados em disco, e não é necessário instalar nenhuma extensão para que funcione), tenha em mente que operações 
 * de I/O (leitura/escrita em disco) podem ser demoradas em caso de demanda simultânea muito grande.
 * 
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Cache
 */
namespace Numenor\Cache;
use Numenor\Excecao\ExcecaoCacheDiscoDirEscrita;
use Numenor\Excecao\ExcecaoCacheDiscoDirNaoDefinido;
use Zend\Cache\StorageFactory;

class CacheDisco extends CacheAbstrato
{
	
	/**
	 * Diretório padrão de armazenamento dos arquivos de cache.
	 * 
	 * As instâncias da classe CacheDisco podem sobrescrever este diretório em seus construtores.
	 * 
	 * @access private
	 * @static
	 * @var string
	 */
	private static $diretorioPadrao;
	/**
	 * Modo de acesso ao cache ('r' = somente leitura, 'w' = somente escrita, 'rw' = leitura e escrita).
	 * 
	 * @access private
	 * @var string
	 */
	private $modo;
	/**
	 * Diretório onde os arquivos de cache serão armazeados.
	 * 
	 * O diretório deve ter permissão de leitura, execução e escrita para o usuário do PHP (normalmente 0700, mas nesse
	 * caso não será possível remover os arquivos de cache via FTP ou SSH a menos que se tenha acesso como super-
	 * usuário).
	 * 
	 * @access private
	 * @var string
	 */
	private $diretorio;
	/**
	 * Em quantos subníveis de diretórios os arquivos de cache deverão ser subdivididos (espelhados) dentro do diretório
	 * de cache.
	 * 
	 * Quando há muitos registros de cache diferentes (namespace + id + tags), pode ser necessário subdividir os mesmos 
	 * em 2 ou mais subníveis de subdiretórios para não causar problemas na estrutura de arquivos do sistema 
	 * operacional. No entanto, a divisão em subníveis torna a operação de remover os registros por tag mais demorada.
	 * 
	 * @access private
	 * @var int
	 */
	private $nivel;
	
	/** 
	 * {@inheritDoc}
	 * @param string|null $diretorio Diretório onde os arquivos de cache serão armazenados.
	 * @param int $nivel Em quantos subníveis de diretórios os arquivos de cache serão divididos (1 = não há subdivisão).
	 * @param string $modo Modo de acesso do cache ('r' = somente leitura, 'w' = somente escrita, 'rw' = leitura e e
	 * scrita).
	 * @throws \Numenor\Excecao\ExcecaoCacheDiscoDirNaoDefinido se tanto o parâmetro $diretorio quanto a propriedade 
	 * estática self::$diretorioPadrao não estiverem definidos.
	 * @throws \Numenor\Excecao\ExcecaoCacheDiscoDirEscrita se o diretório de cache informado não existir ou não tiver 
	 * permissão de escrita.
	 */
	public function __construct(string $namespace, int $duracao, string $diretorio = '', int $nivel = 1, string $modo = 'rw')
	{
		parent::__construct($namespace, $duracao);
		$this->diretorio = $diretorio !== ''
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
		$this->adapter = StorageFactory::factory([
			'adapter' => [
				'name' => 'filesystem',
				'options' => [
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
				]
			],
			'plugins' => [
				'exception_handler' => [
					'throw_exceptions' => true
				],
				'Serializer'
			]
		]);
	}
	
	/**
	 * Define o diretório padrão de armazenamento do cache em disco.
	 * 
	 * @access public
	 * @static
	 * @param string $diretorioPadrao Diretório de armazenamento do cache em disco
	 * @throws \Numenor\Excecao\ExcecaoCacheDiscoDirEscrita se o diretório padrão informado não existir ou não tiver 
	 * permissão de escrita.
	 */
	public static function setDiretorioPadrao(string $diretorioPadrao)
	{
		if (!is_writable($diretorioPadrao)) {
			throw new ExcecaoCacheDiscoDirEscrita();
		}
		static::$diretorioPadrao = $diretorioPadrao;
	}
}
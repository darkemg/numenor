<?php
namespace Numenor\Cache;

/**
 * Classe abstrata de controle do adaptador de cache do framework Zend2.
 *
 * Implementa métodos comuns de acesso a propriedades e operações definidas para todos os adaptadores de cache do
 * framework.
 *
 * @abstract
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Cache
 */
abstract class CacheAbstrato
{
	
	/**
	 * Identificador de namespace do cache (para evitar colisão com outras instâncias)
	 * 
	 * @access protected
	 * @var string
	 */
	protected $namespace;
	/**
	 * Duração da validade do cache, em segundos
	 * 
	 * O valor 0 indica que o cache não expira por duração (mas pode expirar por outras razões, de acordo com as regras
	 * do adaptador de cache escolhido).
	 * 
	 * @access protected
	 * @var int
	 */
	protected $duracao;
	/**
	 * Instância do objeto adaptador de cache
	 * 
	 * @access protected
	 * @var \Zend\Cache\Storage\Adapter
	 */
	protected $adapter;
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param string $namespace Identificador de namespace do cache
	 * @param int $duracao Validade do cache, em segundos (0 = cache não expira)
	 */ 
	public function __construct(string $namespace, int $duracao)
	{
		$this->namespace = $namespace;
		$this->duracao = $duracao;
	}
	
	/**
	 * Recupera um item do cache, identificado pela chave
	 *
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @return mixed|null Item carregado do cache, ou NULL caso o item não exista
	 */
	public function getItem(string $key)
	{
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
	public function getMetadata(string $key)
	{
		return $this->adapter->getMetadata($key);
	}
	
	/**
	 * Armazena um item no cache, identificado por uma chave
	 *
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @param mixed $value Valor armazenado
	 * @return boolean O item foi armazenado no cache com sucesso?
	 */
	public function setItem(string $key, $value) : bool
	{
		return $this->adapter->setItem($key, $value);
	}
	
	/**
	 * Verifica se um item existe e está ativo no cache, identificado pela chave.
	 *
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @return boolean O item existe e está ativo no cache?
	 */
	public function verificarItem(string $key) : bool
	{
		return $this->adapter->hasItem($key);
	}
	
	/**
	 * Renova a validade do item do cache, identificado por uma chave.
	 *
	 * @access public
	 * @param string $key Chave de identificação do item do cache
	 * @return boolean O item deve sua validade renovada com sucesso?
	 */
	public function renovarItem(string $key) : bool
	{
		return $this->adapter->touchItem($key);
	}
}
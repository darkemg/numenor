<?php
/**
 * Classe singleton de boostrap da biblioteca Numenor.
 * 
 * Expõe publicamente apenas o método estático de inicialização dos demais componentes da biblioteca.
 */
namespace Numenor\Bootstrap;
use Numenor\Autenticacao\Checksum;
use Numenor\Cache\CacheDisco;

class Main {
		
	/**
	 * Instância singleton da classe
	 * @access private
	 * @static
	 * @var \Numenor\Bootstrap\Main
	 */
	private static $main = null;
	
	/**
	 * Método construtor da classe.
	 * O construtor é declarado privado para evitar que instâncias adicionais da classe sejam criadas.
	 * 
	 * @access private
	 */
	protected function __construct() {
		
	}
	
	/**
	 * Método de clonagem da classe, declarado privado para que não se possa criar clones da instância
	 * singleton.
	 * 
	 * @access private
	 */
	private function __clone() {
		
	}
	
	/**
	 * Método de wakeup da classe após serialização, declarado privado para que não se possa realizar 
	 * nenhuma operação na desserialização da classe singleton.
	 *
	 * @access private
	 */
	private function __wakeup() {
		
	}
	
	/**
	 * Gera o objeto normalizado de configuração do bootstrap a partir de um array de configuração no
	 * formato chave => valor.
	 * 
	 * @access public
	 * @param array $config Array de configuração do bootstrap
	 * @return \stdClass Objeto de configuração normalizado utilizado para configurar o bootstrap
	 * 
	 */
	//TODO: adicionar o tratamento do idioma padrão
	public function configurarArray(array $config = array()) {
		$dados = new \stdClass();
		// Verifica quais propriedades de configuração foram informadas, e pula a inicialização das que não foram.
		// Configuração do idioma (locale) padrão
		if (isset($config['locale'])) {
			$dados->locale = $config['locale'];	
		}
		// Configuração da chave utilizada na geração dos checksums
		if (isset($config['chaveChecksum'])) {
			$dados->chaveChecksum = $config['chaveChecksum'];
		}
		// Configuração do diretório padrão de cache do adaptador CacheDisco (cache geral)
		if (isset($config['cache']) && isset($config['cache']['geral'])) {
			$dados->cache = (object) array(
					'geral' => (object) array(
							'ativo' => isset($config['cache']['geral']['ativo']) 
									? $config['cache']['geral']['ativo']
									: null,
							'configuracao' => (object) array(
									'dir' => isset($config['cache']['geral']['configuracao']['dir'])
									? DIR_ROOT . $config['cache']['geral']['configuracao']['dir']
									: null,
									'duracao' => isset($config['cache']['geral']['configuracao']['duracao'])
									? $config['cache']['geral']['configuracao']['duracao']
									: null
							)
					)
			);
		}
		return $dados;
	}
	
	/**
	 * Gera o objeto normalizado de configuração do bootstrap a partir de um arquivo de configuração no
	 * formato YAML.
	 * Numenor pode ser configurada a partir de um arquivo próprio ou de uma subseção dentro de um arquivo
	 * de configuração já utilizado pelo sistema; a subseção deve ser chamada numenor e conter as chaves e
	 * valores específicos da biblioteca. 
	 * 
	 * @access public
	 * @param string $config Caminho do arquivo YAML de configuração do bootstrap
	 */
	//TODO: implementação
	public function configurarYaml($config) {
		
	}
	
	/**
	 * Retorna a instância singleton da classe.
	 * 
	 * @access public
	 * @static
	 * @return \Numenor\Bootstrap\Main
	 */
	public static function getInstance(\stdClass $config = null) {
		if (static::$main === null) {
			static::$main = new static();
		}
		return static::$main;
	}
	
	/**
	 * Realiza a inicialização prévia dos componentes da biblioteca Numenor.
	 * 
	 * @access public
	 * @static
	 * @param mixed $config Origem das configurações da biblioteca, em um dos formatos suportados:
	 * 		- array 
	 * 		- arquivo YAML
	 */
	public static function init($config = null) {
		// Se nenhuma configuração foi informada, simplesmente não faz nada.
		// Isso evita que seja necessário configurar a biblioteca para utilizar componentes que não precisem
		// de nenhum tipo de inicialização prévia.
		// Ao utilizar um componente que precisa de inicialização, uma exceção será levantada.
		if ($config === null) {
			return;
		}
		$bootstrap = static::getInstance();
		// Trata a entrada das configurações de acordo com o tipo de origem informada
		if (is_array($config)) {
			$dados = $bootstrap->configurarArray($config);
		}
		// Inicializa a classe de geração de checksums com a chave da aplicação
		if (isset($dados->chaveChecksum)) {
			Checksum::setChavePadrao($dados->chaveChecksum);
		}
		//
		if (isset($dados->cache->geral->configuracao->dir)) {
			CacheDisco::setDiretorioPadrao($dados->cache->geral->configuracao->dir);
		}
	}
	
}
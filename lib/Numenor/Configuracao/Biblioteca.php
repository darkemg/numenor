<?php
/**
 * Classe de boostrap da biblioteca Numenor.
 * 
 * A biblioteca pode ser configurada a partir de um único método estático, ou ter suas dependências injetadas 
 * manualmente.
 * 
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Configuracao
 */
namespace Numenor\Configuracao;

use Numenor\Autenticacao\Checksum;
use Numenor\Cache\CacheDisco;
use Numenor\Excecao\ExcecaoArquivoConfiguracaoInvalido;
use Zend\Config\Config;
use Zend\Config\Exception as ConfigException;
use Zend\Config\Factory as ConfigFactory;
use Numenor\Excecao\Numenor\Excecao;

class Biblioteca
{
	
	/**
	 * Configurações oriundas do servidor. Por exemplo, diretório raiz da aplicação, versão do sistema operacional
	 * do servidor, etc.
	 * 
	 * @access private
	 * @var \Zend\Config\Config
	 */
	private $configuracaoServidor;
	/**
	 * Configurações específicas da biblioteca. Por exemplo, configurações do sistema de cache, geração de checksums,
	 * etc.
	 * 
	 * @access private 
	 * @var \Zend\Config\Config
	 */
	private $configuracaoBiblioteca;
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param \Zend\Config\Config $configuracaoServidor Objeto de configuração do servidor.
	 * @param \Zend\Config\Config $configuracaoBiblioteca Objeto de configuração da biblioteca.
	 */
	public function __construct(Config $configuracaoServidor = null, Config $configuracaoBiblioteca = null) 
	{
		$this->configuracaoServidor = $configuracaoServidor;
		$this->configuracaoBiblioteca = $configuracaoBiblioteca;
	}
	
	/**
	 * Método de acesso à configuração do servidor.
	 * 
	 * @access public
	 * @return \Zend\Config\Config Objeto de configuração do servidor.
	 */
	public function getConfiguracaoServidor() : Config 
	{
		return $this->configuracaoServidor;
	}
	
	/**
	 * Método de acesso à configuração da biblioteca. 
	 * 
	 * @access public
	 * @return \Zend\Config\Config Objeto de configuração da biblioteca.
	 */
	public function getConfiguracaoBiblioteca() : Config 
	{
		return $this->configuracaoBiblioteca;
	}
	
	/**
	 * Realiza a inicialização prévia dos componentes da biblioteca Numenor, permitindo que os mesmos
	 * estejam prontos para usar.
	 * 
	 * @access public
	 * @static
	 * @param mixed $configuracao Origem das configurações da biblioteca, em um dos formatos suportados
	 * pela classe Zend\Config\Config
	 * @return \Numenor\Bootstrap\Biblioteca Objeto de bootstrap da biblioteca.
	 * @throws \Numenor\Excecao\ExcecaoArquivoConfiguracaoInvalido se o arquivo de configuração informado 
	 * não existir, não puder ser aberto, ou estiver em um formato inválido.
	 */
	public static function init($configuracao = null) : self
	{
		// Instancia a configuração do servidor.
		$configuracaoServidor = new Config([
			'diretorioRaiz' => $_SERVER['DOCUMENT_ROOT'] . '/',
			'enderecoIp' => $_SERVER['SERVER_ADDR'],
			'https' => (boolean) $_SERVER['HTTPS'],
			'ipUsuario' => $_SERVER['REMOTE_ADDR'],
			'portaUsuario' => $_SERVER['REMOTE_PORT']
		]);
		// Instancia a configuração da biblioteca, de acordo com a origem dos parâmetros enviados:
		// - caso $configuracao seja nulo, então instancia a classe Zend\Config\Config diretamente sem nenhum conteúdo. 
		//   Isto permite que os componentes da biblioteca Numenor que dispensam configuração possam ser utilizados de 
		//   maneira independente.
		// - caso $configuracao seja um array, instancia a classe Zend\Config\Config diretamente, passando o array de 
		//   configuração para o construtor.
		// - caso $configuracao seja um arquivo, chama a classe de factory do pacote Zend\Config para criar o objeto de 
		//   configuração a partir deste arquivo.
		if ($configuracao === null) {
			return new self($configuracaoServidor);
		}
		if (is_array($configuracao)) {
			$biblioteca =  new self($configuracaoServidor, new Config($configuracao));
		} else {
			try {
				$arquivoConfiguracao = $configuracaoServidor->diretorioRaiz . $configuracao;
				$configuracaoBiblioteca = ConfigFactory::fromFile($arquivoConfiguracao, true);
				$biblioteca = new self($configuracaoServidor, $configuracaoBiblioteca);
			} catch (ConfigException\RuntimeException $e) {
				throw new ExcecaoArquivoConfiguracaoInvalido($e);
			}
		}
		// Inicializa estaticamente as outras classes da biblioteca que precisam de inicialização prévia, caso a chave
		// da configuração correspondente tenha sido encontrada.
		$dados = $biblioteca->getConfiguracaoBiblioteca();
		// Inicializa a classe de geração de checksums com a chave da aplicação
		if (isset($dados->chaveChecksum)) {
			Checksum::setChavePadrao($dados->chaveChecksum);
		}
		// Inicializa a classe de cache em disco
		if (isset($dados->cache->geral->configuracao)) {
			CacheDisco::setDiretorioPadrao($configuracaoServidor->diretorioRaiz . $dados->cache->geral->configuracao->dir);
		}
		return $biblioteca;
	}
}
<?php
/**
 * Classe abstrata de controle para inclusão de assets em páginas HTML.
 *
 * Além de gerar o snippet de código adequado para a inclusão dos assets em uma página HTML, as subclasses desta classe
 * providenciam métodos para minificação e concatenação de assets de texto (como JS e CSS), bem como a geração de 
 * sprites para imagens.
 * 
 * @abstract
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;
use Numenor\Php\ArrayWrapper;
use Numenor\Php\StringWrapper;

abstract class Controle {
	
	/**
	 * Instância do objeto de encapsulamento das operações de array.
	 * 
	 * @access protected
	 * @var \Numenor\Php\ArrayWrapper;
	 */
	protected $arrayWrapper;
	/**
	 * Instância do objeto de encapsulamento das operações de string.
	 * 
	 * @access protected
	 * @var \Numenor\Php\StringWrapper;
	 */
	protected $stringWrapper;
	/**
	 * Diretório onde os arquivos processados (minificados, concatenados, gzipados, etc.) serão salvos.
	 * 
	 * Este diretório DEVE ter o acesso público liberado (normalmente, algo como <DIR_ROOT>/public/assets/<TIPO>/), ou
	 * os arquivos não funcionarão ao serem incluídos como assets na página.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $diretorioOutput;
	/**
	 * URL base de inclusão dos assets.
	 * 
	 * Este valor será utilizado para gerar os snippets de inclusão dos assets (tags <script>, <link>, <img>, etc.), e
	 * normalmente reflete o diretório de output dos assets processados.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $urlBase;
	/**
	 * Lista de arquivos que serão efetivamente incluídos na página.
	 *
	 * @access protected
	 * @var array
	 */
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $arrayWrapper Instância do objeto de encapsulamento das operações de array.
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de string.
	 * @param string $diretorioOutput Diretório onde os arquivos processados serão salvos.
	 * @param string $urlBase URL base de inclusão dos assets.
	 */
	public function __construct(ArrayWrapper $arrayWrapper, StringWrapper $stringWrapper, $diretorioOutput, $urlBase) {
		$this->arrayWrapper = $arrayWrapper;
		$this->stringWrapper = $stringWrapper;
		$this->diretorioOutput = $diretorioOutput;
		$this->urlBase = $urlBase;
	}
	
	/**
	 * Gera a lista de arquivos de assets que devem ser minificados e concatenados.
	 *
	 * @access protected
	 * @param array $listaAssets Lista de assets analisados.
	 * @return array Lista de arquivos de assets que devem ser minificados e concatenados.
	 */
	protected function gerarListaConcatCompact(array $listaAssets) {
		$lista = [];
		foreach ($listaAssets as $asset) {
			if ($asset->isCompactavel() && $asset->isConcatenavel()) {
				$lista[] = (string) $asset;
			}
		}
		return $lista;
	}
	
	/**
	 * Gera a lista de arquivos de assets que devem ser apenas concatenados, sem minificação.
	 *
	 * @access protected
	 * @param array $listaAssets Lista de assets analisados.
	 * @return array Lista de arquivos de assets que devem ser concatenados.
	 */
	protected function gerarListaConcat(array $listaAssets) {
		$lista = [];
		foreach ($listaAssets as $asset) {
			if ($asset->isConcatenavel() && !$asset->isCompactavel()) {
				$lista[] = (string) $asset;
			}
		}
		return $lista;
	}
	
	/**
	 * Gera a lista de arquivos de assets que devem ser apenas minificados, sem concatenação.
	 *
	 * @access protected
	 * @param array $listaAssets Lista de assets analisados.
	 * @return array Lista de arquivos de assets que devem ser minificados.
	 */
	protected function gerarListaCompact(array $listaAssets) {
		$lista = [];
		foreach ($listaAssets as $asset) {
			if ($asset->isCompactavel() && !$asset->isConcatenavel()) {
				$lista[] = (string) $asset;
			}
		}
		return $lista;
	}
	
	/**
	 * Gera a lista de arquivos de assets que devem ser incluídos sem nenhum processamento adicional.
	 *
	 * @access protected
	 * @param array $listaAssets Lista de assets analisados.
	 * @return array Lista de arquivos de assets que devem ser incluídos sem nenhum processamento adicional.
	 */
	protected function gerarListaNormal(array $listaAssets) {
		$lista = [];
		foreach ($listaAssets as $asset) {
			// Inclui os assets marcados como não concatenáveis, não compactáveis, e que não são remotos.
			$traits = class_uses($asset);
			try {
				$isRemoto = $this->arrayWrapper->encontrarItem($traits, Remoto::class);
			} catch (\Throwable $e) {
				$isRemoto = false;
			}
			if (!$isRemoto && !$asset->isCompactavel() && !$asset->isConcatenavel()) {
				$lista[] = (string) $asset;
			}
		}
		return $lista;
	}
	
	/**
	 * Gera um nome único para uma lista de assets, criando um hash a partir da concatenação dos nomes individuais.
	 * 
	 * @access protected
	 * @param array $listaAssets Lista de nomes dos assets.
	 * @return string Hash gerado a partir dos nomes dos assets listados.
	 */
	protected function gerarNome(array $listaAssets) {
		return hash('sha1', $this->stringWrapper->unir('', $listaAssets));
	}
	
	/**
	 * Gera o snippet do código de inclusão dos assets adicionados à página.
	 *
	 * @access public
	 * @return string O snippet de inclusão dos arquivos processados.
	 */
	public function gerarCodigoInclusao() {
		$this->minificar();
		return $this->stringWrapper->unir('', $this->listaArquivosIncluir);
	}
}
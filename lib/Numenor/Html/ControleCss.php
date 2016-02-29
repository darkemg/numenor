<?php
/**
 * Classe de controle para inclusão de arquivos CSS em páginas HTML.
 *
 * Arquivos CSS podem ser concatenados, minificados e gzipados para melhorar o desempenho da página onde são carregados.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;
use MatthiasMullie\Minify\CSS as MinifyCss;
class ControleCss extends Controle {
	
	/**
	 * Componente de minificação dos assets CSS.
	 *
	 * @access protected
	 * @var \MatthiasMullie\Minify\CSS
	 */
	protected $minificadorCss;
	/**
	 * Lista de arquivos CSS adicionados para processamento na página.
	 *
	 * @access protected
	 * @var \Numenor\Html\Css[]
	 */
	protected $listaCss;
	
	/**
	 * Gera a lista de arquivos de assets de inclusão remota.
	 *
	 * @access protected
	 * @param array $listaAssets Lista de assets analisados.
	 * @return array Lista de arquivos de assets de inclusão remota.
	 */
	protected function gerarListaRemoto(array $listaAssets) {
		$lista = [];
		foreach ($listaAssets as $asset) {
			if ($asset instanceof CssRemoto) {
				$lista[] = (string) $asset;
			}
		}
		return $lista;
	}
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $arrayWrapper Instância do objeto de encapsulamento das operações de array.
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de string.
	 * @param string $diretorioOutput Diretório onde os arquivos processados serão salvos.
	 * @param string $urlBase URL base de inclusão dos assets.
	 */
	public function __construct($arrayWrapper, $stringWrapper, $diretorioOutput, $urlBase) {
		parent::__construct($arrayWrapper, $stringWrapper, $diretorioOutput, $urlBase);
		$this->listaCss = [];
		$this->listaArquivosIncluir = [];
	}
	
	/**
	 * Processa a lista de arquivos CSS incluídos, alterando-os conforme necessário (minificação, concatenação,
	 * etc.) e gerando os snippets de inclusão dos mesmos.
	 *
	 * @access protected
	 */
	protected function minificar() {
		// Reseta a lista de arquivos a serem incluídos
		$this->listaArquivosIncluir = [];
		// Adiciona os scripts remotos
		$listaRemoto = $this->gerarListaRemoto($this->listaCss);
		if (count($listaRemoto) > 0) {
			$this->listaArquivosIncluir = $listaRemoto;
		}
		// Adiciona os arquivos que devem ser minificados e concatenados em um só arquivo
		$listaConcatCompact = $this->gerarListaConcatCompact($this->listaCss);
		if (count($listaConcatCompact) > 0) {
			$nomeConcatCompact = $this->gerarNome($listaConcatCompact);
			$minificadorConcatCompact = clone $this->minificadorCss;
			$outputConcatCompact = $this->diretorioOutput . $nomeConcatCompact . '.css';
			if (!file_exists($outputConcatCompact)) {
				foreach ($listaConcatCompact as $css) {
					$minificadorConcatCompact->add($css);
				}
				$minificadorConcatCompact->minify($outputConcatCompact);
			}
			$this->listaArquivosIncluir[] = '<link rel="stylesheet" href="' . $this->urlBase . $nomeConcatCompact . '.css">' . \PHP_EOL;
		}
		unset($minificadorConcatCompact);
		// Adiciona os arquivos que devem ser concatenados em um só arquivo, sem minificação
		$listaConcat = $this->gerarListaConcat($this->listaCss);
		if (count($listaConcat) > 0) {
			$nomeConcat = $this->gerarNome($listaConcat);
			$outputConcat = $this->diretorioOutput . $nomeConcat . '.css';
			if (!file_exists($outputConcat)) {
				foreach ($listaConcat as $css) {
					file_put_contents(
							$outputConcat, 
							file_get_contents($css) . \PHP_EOL, 
							\FILE_APPEND);
				}
			}
			$this->listaArquivosIncluir[] = '<link rel="stylesheet" href="' . $this->urlBase . $nomeConcat . '.css">' . \PHP_EOL;
		}
		// Adiciona os arquivos que devem ser minificados, sem concatenação
		$listaCompact = $this->gerarListaCompact($this->listaCss);
		if (count($listaCompact) > 0) {
			foreach ($listaCompact as $css) {
				$minificadorCompact = clone $this->minificadorCss;
				$nomeCompact = $this->gerarNome([$css]);
				$outputCompact = $this->diretorioOutput . $nomeCompact . '.css';
				if (!file_exists($outputCompact)) {
					$minificadorCompact->add($css);
					$minificadorCompact->minify($outputCompact);
				}
				$this->listaArquivosIncluir[] = '<link rel="stylesheet" href="' . $this->urlBase . $nomeCompact . '.css">' . \PHP_EOL;
				unset($minificadorCompact);
			}
		}
		// Adiciona os arquivos que devem ser processados sem minificação ou concatenação.
		$listaNormal = $this->gerarListaNormal($this->listaCss);
		if (count($listaNormal) > 0) {
			foreach ($listaNormal as $css) {
				$this->listaArquivosIncluir[] = '<link rel="stylesheet" href="'. $css . '">' . \PHP_EOL;
			}
		}
	}
	
	/**
	 * Adiciona um arquivo CSS à lista de arquivos incluídos na página.
	 *
	 * @access public
	 * @param \Numenor\Html\Css $css Novo arquivo incluído na página.
	 * @return \Numenor\Html\ControleCss Instância do próprio objeto para encadeamento.
	 * @throws \Numenor\Excecao\ExcecaoAssetDuplicado se o asset informado já foi incluído anteriormente.
	 */
	public function adicionarCss(Css $css) {
		try {
			// Se o asset já existe, o método ArrayWrapper::encontrarItem retorna o índice do mesmo.
			$indice = $this->arrayWrapper->encontrarItem($this->listaCss, $css);
		} catch (\Throwable $e) {
			// Se o asset não foi encontrado, o método ArrayWrapper::encontrarItem levanta uma exceção.
			// Nesse caso é necessário ignorá-la.
		} finally {
			// Por fim, verifica o valor de retorno da busca pelo asset.
			// Se ele já existe, levanta-se a exceção
			if (!is_null($indice)) {
				throw new ExcecaoAssetDuplicado();
			}
		}
		$this->listaCss[] = $css;
		return $this;
	}
	
	/**
	 * Define o minificador utilizado pelo sistema para processar e compactar os arquivos.
	 *
	 * @access public
	 * @param MatthiasMullie\Minify\CSS $minificador Instância do minificador.
	 * @return \Numenor\Html\ControleCss Instância do próprio objeto para encadeamento.
	 */
	public function setMinificadorCss(MinifyCss $minificador) {
		$this->minificadorCss = $minificador;
		return $this;
	}
}
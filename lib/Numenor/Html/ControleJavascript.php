<?php
/**
 * Classe de controle para inclusão de arquivos Javascript em páginas HTML.
 *
 * Arquivos JS podem ser concatenados, minificados e gzipados para melhorar o desempenho da página onde são carregados.
 * Também é possível controlar a inclusão de arquivos de script originados em servidores externos (CDNs), que não são
 * pré-processados da mesma maneira que os scripts armazenados localmente no servidor.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Html
 */
namespace Numenor\Html;
use MatthiasMullie\Minify\JS as MinifyJs;
use Numenor\Excecao\ExcecaoAssetDuplicado;
use Numenor\Excecao\ExcecaoAssetNaoExiste;
use Numenor\Php\ArrayWrapper;
use Numenor\Php\StringWrapper;
class ControleJavascript extends Controle {
	
	/**
	 * Componente de minificação dos assets Javascript.
	 * 
	 * @access protected
	 * @var \MatthiasMullie\Minify\JS
	 */
	protected $minificadorJs;
	/**
	 * Lista de arquivos Javascript adicionados para processamento na página.
	 * 
	 * @access protected
	 * @var \Numenor\Html\Javascript[]
	 */
	protected $listaJs;
	/**
	 * Lista de arquivos Javascript que serão efetivamente incluídos na página.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $listaArquivosIncluir;
	
	/**
	 * Método construtor da classe.
	 *
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $arrayWrapper Instância do objeto de encapsulamento das operações de array.
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de string.
	 * @param string $diretorioOutput Diretório onde os arquivos processados serão salvos.
	 * @param string $urlBase URL base de inclusão dos assets.
	 */
	public function __construct(ArrayWrapper $arrayWrapper, StringWrapper $stringWrapper, string $diretorioOutput, string $urlBase) {
		parent::__construct($arrayWrapper, $stringWrapper, $diretorioOutput, $urlBase);
		$this->listaJs = [];
		$this->listaArquivosIncluir = [];
	}
	
	/**
	 * Gera a lista de arquivos de assets de inclusão remota.
	 *
	 * @access protected
	 * @param array $listaAssets Lista de assets analisados.
	 * @return array Lista de arquivos de assets de inclusão remota.
	 */
	protected function gerarListaRemoto(array $listaAssets) : array {
		$lista = [];
		foreach ($listaAssets as $asset) {
			if ($asset instanceof JavascriptRemoto) {
				$lista[] = (string) $asset;
			}
		}
		return $lista;
	}
	
	/**
	 * Processa a lista de arquivos Javascript incluídos, alterando-os conforme necessário (minificação, concatenação,
	 * etc.) e gerando os snippets de inclusão dos mesmos.
	 * 
	 * @access protected
	 */
	protected function minificar() {
		// Reseta a lista de arquivos a serem incluídos
		$this->listaArquivosIncluir = [];
		// Adiciona os scripts remotos
		$listaRemoto = $this->gerarListaRemoto($this->listaJs);
		if (count($listaRemoto) > 0) {
			$this->listaArquivosIncluir = $listaRemoto;
		}
		if ($this->comportamentoPadrao === self::COMPORTAMENTO_PADRAO_DEV) {
			// Se está configurado para ter o comportamento padrão de ambiente de desenvolvimento, todos os arquivos
			// não-remotos são processados sem concatenação ou compactação
			$listaConcatCompact = [];
			$listaConcat = [];
			$listaCompact = [];
			$listaNormal = $this->arrayWrapper->mesclar($this->gerarListaNormal($this->listaJs), $this->gerarListaConcatCompact($this->listaJs));
			$listaNormal = $this->arrayWrapper->mesclar($listaNormal, $this->gerarListaConcat($this->listaJs));
			$listaNormal = $this->arrayWrapper->mesclar($listaNormal, $this->gerarListaCompact($this->listaJs));
		} else {
			// Caso contrário, processa normalmente os assets de acordo com seus tipos
			$listaConcatCompact = $this->gerarListaConcatCompact($this->listaJs);
			$listaConcat = $this->gerarListaConcat($this->listaJs);
			$listaCompact = $this->gerarListaCompact($this->listaJs);
			$listaNormal = $this->gerarListaNormal($this->listaJs);
		}
		// Adiciona os arquivos que devem ser minificados e concatenados em um só arquivo
		if (count($listaConcatCompact) > 0) {
			$nomeConcatCompact = $this->gerarNome($listaConcatCompact);
			$minificadorConcatCompact = clone $this->minificadorJs;
			$outputConcatCompact = $this->diretorioOutput . $nomeConcatCompact . '.js';
			if ($this->comportamentoPadrao === self::COMPORTAMENTO_PADRAO_HOMOLOG) {
				file_exists($outputConcatCompact) ? unlink($outputConcatCompact) : null;
			}
			if (!file_exists($outputConcatCompact)) {
				foreach ($listaConcatCompact as $js) {
					$minificadorConcatCompact->add($js);
				}
				$minificadorConcatCompact->minify($outputConcatCompact);
			}
			$this->listaArquivosIncluir[] = '<script src="' . $this->urlBase . $nomeConcatCompact . '.js"></script>' . \PHP_EOL;
		}
		unset($minificadorConcatCompact);
		// Adiciona os arquivos que devem ser concatenados em um só arquivo, sem minificação
		if (count($listaConcat) > 0) {
			$nomeConcat = $this->gerarNome($listaConcat);
			$outputConcat = $this->diretorioOutput . $nomeConcat . '.js';
			if ($this->comportamentoPadrao === self::COMPORTAMENTO_PADRAO_HOMOLOG) {
				file_exists($outputConcat) ? unlink($outputConcat) : null;
			}
			if (!file_exists($outputConcat)) {
				foreach ($listaConcat as $js) {
					file_put_contents(
						$outputConcat,
						file_get_contents($js) . \PHP_EOL,
						\FILE_APPEND);
				}
			}
			$this->listaArquivosIncluir[] = '<script src="' . $this->urlBase . $nomeConcat . '.js"></script>' . \PHP_EOL;
		}
		// Adiciona os arquivos que devem ser minificados, sem concatenação
		if (count($listaCompact) > 0) {
			foreach ($listaCompact as $js) {
				$minificadorCompact = clone $this->minificadorJs;
				$nomeCompact = $this->gerarNome([$js]);
				$outputCompact = $this->diretorioOutput . $nomeCompact . '.js';
				if ($this->comportamentoPadrao === self::COMPORTAMENTO_PADRAO_HOMOLOG) {
					file_exists($outputCompact) ? unlink($outputCompact) : null;
				}
				if (!file_exists($outputCompact)) {
					$minificadorCompact->add($js);
					$minificadorCompact->minify($outputCompact);
				}
				$this->listaArquivosIncluir[] = '<script src="' . $this->urlBase . $nomeCompact . '.js"></script>' . \PHP_EOL;
				unset($minificadorCompact);
			}
		}
		// Adiciona os arquivos que devem ser processados sem minificação ou concatenação.
		if (count($listaNormal) > 0) {
			foreach ($listaNormal as $js) {
				if ($js instanceof JavascriptRemoto) {
					$this->listaArquivosIncluir[] = $js->gerarSnippetInclusao();
				} else {
					$nomeNormal = $this->gerarNome([$js]);
					$outputNormal = $this->diretorioOutput . $nomeNormal . '.js';
					if ($this->comportamentoPadrao === self::COMPORTAMENTO_PADRAO_DEV || $this->comportamentoPadrao === self::COMPORTAMENTO_PADRAO_HOMOLOG) {
						file_exists($outputNormal) ? unlink($outputNormal) : null;
					}
					if (!file_exists($outputNormal)) {
						copy((string) $js, $outputNormal);
					}
					$this->listaArquivosIncluir[] = '<script src="'. $this->urlBase . $nomeNormal . '.js"></script>' . \PHP_EOL;
				}
			}
		}
	}
	
	/**
	 * Adiciona um arquivo Javascript à lista de arquivos incluídos na página.
	 * 
	 * @access public 
	 * @param \Numenor\Html\Javascript $js Novo arquivo incluído na página.
	 * @return \Numenor\Html\ControleJavascript Instância do próprio objeto para encadeamento.
	 * @throws \Numenor\Excecao\ExcecaoAssetDuplicado se o asset informado já foi incluído anteriormente.
	 * @throws \Numenor\Excecao\ExcecaoAssetNaoExiste se o arquivo do asset informado não existe.
	 */
	public function adicionarJs(Javascript $js) : self {
		$indice = null;
		try {
			// Se o asset já existe, o método ArrayWrapper::encontrarItem retorna o índice do mesmo.
			$indice = $this->arrayWrapper->encontrarItem($this->listaJs, $js);
		} catch (\Throwable $e) {
			// Se o asset não foi encontrado, o método ArrayWrapper::encontrarItem levanta uma exceção.
			// Nesse caso é necessário ignorá-la.
		} finally {
			// Por fim, verifica o valor de retorno da busca pelo asset.
			// Se ele já existe, levanta-se a exceção
			if (!is_null($indice)) {
				throw new ExcecaoAssetDuplicado($js);
			}
		}
		if (!($js instanceof JavascriptRemoto) && !file_exists((string) $js)) {
			throw new ExcecaoAssetNaoExiste($js);
		}
		$this->listaJs[] = $js;
		return $this;
	}
	
	/**
	 * Define o minificador utilizado pelo sistema para processar e compactar os arquivos.
	 *
	 * @access public
	 * @param MatthiasMullie\Minify\JS $minificador Instância do minificador.
	 * @return \Numenor\Html\ControleJavascript Instância do próprio objeto para encadeamento.
	 */
	public function setMinificadorJs(MinifyJs $minificador) : self {
		$this->minificadorJs = $minificador;
		return $this;
	}
}
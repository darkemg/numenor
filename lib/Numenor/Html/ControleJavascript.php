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
	 * Processa a lista de arquivos Javascript incluídos, alterando-os conforme necessário (minificação, concatenação,
	 * etc.) e gerando os snippets de inclusão dos mesmos.
	 * 
	 * @access protected
	 */
	protected function minificar() {
		// Reseta a lista de arquivos a serem incluídos
		$this->listaArquivosIncluir = array();
		// Adiciona os arquivos que devem ser minificados e concatenados em um só arquivo
		$listaConcatCompact = $this->gerarListaConcatCompact($this->listaJs);
		if (count($listaConcatCompact) > 0) {
			$nomeConcatCompact = $this->gerarNome($listaConcatCompact);
			$minificadorConcatCompact = clone $this->minificadorJs;
			$outputConcatCompact = $this->diretorioOutput . $nomeConcatCompact . '.js';
			if (!file_exists($outputConcatCompact)) {
				foreach ($listaConcatCompact as $js) {
					$minificadorConcatCompact->add($js);
				}
				file_put_contents($outputConcatCompact, $minificadorConcatCompact->execute($outputConcatCompact), \FILE_APPEND);
			}
			$this->listaArquivosIncluir[] = '<script src="' . $this->urlBase . $nomeConcatCompact . '.js"></script>' . \PHP_EOL;
		}
		unset($minificadorConcatCompact);
		// Adiciona os arquivos que devem ser concatenados em um só arquivo, sem minificação
		$listaConcat = $this->gerarListaConcat($this->listaJs);
		if (count($listaConcat) > 0) {
			$nomeConcat = $this->gerarNome($listaConcat);
			$outputConcat = $this->diretorioOutput . $nomeConcat . '.js';
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
		$listaCompact = $this->gerarListaCompact($this->listaJs);
		if (count($listaCompact) > 0) {
			foreach ($listaCompact as $js) {
				$minificadorCompact = clone $this->minificadorJs;
				$nomeCompact = $this->gerarNome(array($js));
				$outputCompact = $this->diretorioOutput . $nomeCompact . '.js';
				if (!file_exists($outputCompact)) {
					$minificadorCompact->add($js);
					file_put_contents($outputCompact, $minificadorCompact->execute($outputCompact), \FILE_APPEND);
				}
				$this->listaArquivosIncluir[] = '<script src="' . $this->urlBase . $nomeCompact . '.js"></script>' . \PHP_EOL;
				unset($minificadorCompact);
			}
		}
		// Adiciona os arquivos que devem ser processados sem minificação ou concatenação.
		$listaNormal = $this->gerarListaNormal($this->listaJs);
		if (count($listaNormal) > 0) {
			foreach ($listaNormal as $js) {
				if ($js instanceof JavascriptRemoto) {
					$this->listaArquivosIncluir[] = $js->gerarSnippetInclusao();
				} else {
					$this->listaArquivosIncluir[] = '<script src="'. $js . '"></script>' . \PHP_EOL;
				}
			}
		}
	}
	
	/**
	 * Adiciona um arquivo Javascript à lista de arquivos incluídos na página.
	 * 
	 * @access public
	 * @param \Numenor\Html\Javascript $js Novo arquivo incluído na página.
	 */
	public function adicionarJs(Javascript $js) {
		$this->listaJs[] = $js;
	}
	
	/**
	 * Define o minificador utilizado pelo sistema para processar e compactar os arquivos.
	 *
	 * @access public
	 * @param MatthiasMullie\Minify\JS $minificador Instância do minificador.
	 */
	public function setMinificadorJs(MinifyJs $minificador) {
		$this->minificadorJs = $minificador;
	}
}
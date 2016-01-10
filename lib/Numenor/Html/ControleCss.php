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
	 * Processa a lista de arquivos CSS incluídos, alterando-os conforme necessário (minificação, concatenação,
	 * etc.) e gerando os snippets de inclusão dos mesmos.
	 *
	 * @access protected
	 */
	protected function minificar() {
	// Reseta a lista de arquivos a serem incluídos
		$this->listaArquivosIncluir = array();
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
				file_put_contents($outputConcatCompact, $minificadorConcatCompact->execute($outputConcatCompact), \FILE_APPEND);
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
				$nomeCompact = $this->gerarNome(array($css));
				$outputCompact = $this->diretorioOutput . $nomeCompact . '.css';
				if (!file_exists($outputCompact)) {
					$minificadorCompact->add($css);
					file_put_contents($outputCompact, $minificadorCompact->execute($outputCompact), \FILE_APPEND);
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
	 */
	public function adicionarCss(Css $css) {
		$this->listaCss[] = $css;
	}
	
	/**
	 * Define o minificador utilizado pelo sistema para processar e compactar os arquivos.
	 *
	 * @access public
	 * @param MatthiasMullie\Minify\CSS $minificador Instância do minificador.
	 */
	public function setMinificadorCss(MinifyCss $minificador) {
		$this->minificadorCss = $minificador;
	}
}
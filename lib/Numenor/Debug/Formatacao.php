<?php
namespace Numenor\Debug;

/**
 * Prepara um texto genérico para ser formatado de acordo com templates específicos para debug de informações.
 * 
 * AAAA.
 * 
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Debug
 */
class Formatacao
{
	
	/**
	 * Template para formatação do debug como HTML cru.
	 * 
	 * @var string
	 */
	const TEMPLATE_HTML_RAW = '<pre>{{VALOR}}</pre>';
	/**
	 * Template para formatação do debug como HTML "enfeitado" (inserido dentro de uma div com classe CSS específica).
	 *
	 * @var string
	 */
	const TEMPLATE_HTML_FANCY = '<div class="output output-fancy">{{VALOR}}</div>';
	/**
	 * Template para formatação do debug como um comentário HTML (de modo que possa ser visto apenas ao inspecionar o
	 * código-fonte do documento).
	 *
	 * @var string
	 */
	const TEMPLATE_HTML_COMMENT = '<!-- ' . \PHP_EOL . '{{VALOR}}' . \PHP_EOL . ' -->';
	/**
	 * Template para formatação do debug como um texto cru, prefixado por uma linha de separação (indicado para escrita
	 * em arquivos de texto).
	 *
	 * @var string
	 */
	const TEMPLATE_RAW = '================================' . \PHP_EOL . '{{VALOR}}' . \PHP_EOL; 
	
	/**
	 * Formata um valor com o template de HTML cru.
	 * Este formato coloca o valor informado dentro de uma tag <pre>, de forma que
	 * o texto seja exibido com fonte monoespaçada e preservando qualquer identação, 
	 * espaçamento e linhas em branco no valor original caso o mesmo seja exibido em um
	 * documento HTML.
	 * 
	 * @access public
	 * @static
	 * @param string $valor Valor a ser formatado.
	 * @return string O valor formatado no template.
	 */
	public static function htmlRaw($valor) 
	{
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_HTML_RAW);
	}
	
	/**
	 * Formata um valor com o template de HTML decorado.
	 * Este formato coloca o valor informado dentro de uma tag <div> com as classes "output" e
	 * "output-fancy", de modo que a exibição possa ser definida através de CSS. 
	 * 
	 * @access public
	 * @static
	 * @param string $valor Valor a ser formatado.
	 * @return string O valor formatado no template.
	 */
	public static function htmlFancy($valor) 
	{
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_HTML_FANCY);
	}
	
	/**
	 * Formata um valor com o template de comentário HTML.
	 * Este formato coloca o valor dentro de um comentário HTML de modo que o valor não seja
	 * visualizado quando o documento for exibido, sendo indicado para depuração em ambiente
	 * de produção.
	 * 
	 * @access public
	 * @static
	 * @param string $valor Valor a ser formatado.
	 * @return string O valor formatado no template.
	 */
	public static function htmlComentario($valor) 
	{
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_HTML_COMMENT);
	}
	
	/**
	 * Formata um valor com o template de texto cru.
	 * Este formato prefixa o valor com uma linha de caracteres de separação, sendo indicado para
	 * depuração em arquivos de texto. O separador facilita identificar onde cada depuração começa
	 * e termina.
	 * 
	 * @access public
	 * @static
	 * @param string $valor Valor a ser formatado.
	 * @return string O valor formatado no template.
	 */
	public static function raw($valor) 
	{
		return str_replace('{{VALOR}}', print_r($valor, true), static::TEMPLATE_RAW);
	}
}
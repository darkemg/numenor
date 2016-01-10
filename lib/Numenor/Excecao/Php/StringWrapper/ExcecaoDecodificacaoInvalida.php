<?php
/**
 * Exceção levantada pelo sistema quando ocorre um erro na decodificação de um texto codificado.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/String
 */
namespace Numenor\Excecao\Php\StringWrapper;
class ExcecaoDecodificacaoInvalida extends \Numenor\Excecao\ExcecaoErroSistema {
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('Erro na decodificação da string informada.', static::DEFAULT_CODE, $previous);
	}
}
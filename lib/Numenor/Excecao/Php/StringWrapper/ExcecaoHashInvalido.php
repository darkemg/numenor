<?php
/**
 * Exceção levantada pelo sistema quando a função password_hash() retorna um valor inválido, indicando que
 * houve erro na geração do hash.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao/Php/String
 */
namespace Numenor\Excecao\Php\StringWrapper;
class ExcecaoHashInvalido extends \Numenor\Excecao\ExcecaoErroSistema {
	
	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null) {
		parent::__construct('Não foi possível gerar um hash para a senha informada.', static::DEFAULT_CODE, $previous);
	}
}
<?php
namespace Numenor\Excecao;

/**
 * Classe de exceções levantadas pelo sistema quando há um erro de uso de alguma funcionalidade da
 * aplicação.
 *
 * Erros de uso são causados pela invocação incorreta de alguma operação disponibilizada pela aplicação
 * (por exemplo, chamada de um método de um objeto que depende de uma configuração prévia sem que esta
 * tenha sido feita antes).
 *
 * Estes erros produzem um estado irrecuperável na aplicação e devem ser tratados com a interrupção do
 * script.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Excecao
 */
class ExcecaoErroUso extends ExcecaoAbstrata
{
	
	/**
	 * Tratamento genérico de exceção de erro de sistema.
	 *
	 * @access public
	 * @see \Numenor\Excecao\ExcecaoAbstrata::tratar()
	 */
	public function tratar()
	{
		// Erros de uso devem sempre encerrar a execução do script, pois o sistema entrou em um
		// estado de erro por negligência do programador.
		die();
	}
}
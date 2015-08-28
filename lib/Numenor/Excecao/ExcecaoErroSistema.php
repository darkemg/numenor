<?php
/**
 * Classe de exceções levantadas pelo sistema quando há um erro de sistema na aplicação.
 * Erros de sistema são causados por algum tipo de operação que causou um erro irrecuperável (por exemplo,
 * falta de memória, operação em um arquivo que foi deletado subitamente por um outro processo, etc.)
 * Estes erros produzem um estado irrecuperável na aplicação e devem ser tratados com a interrupção do
 * script.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;
class ExcecaoErroSistema extends ExcecaoAbstrata {
	
	/**
	 * Tratamento genérico de exceção de erro de sistema.
	 *
	 * @access public
	 * @see \Numenor\Excecao\ExcecaoAbstrata::tratar()
	 */
	public function tratar() {
		// Erros de sistema devem sempre encerrar a execução do script, pois o sistema entrou em um
		// estado impossível de recuperar
		die();
	}
}
<?php
/**
 * Classe de exceções levantadas pelo sistema quando há um erro lógico na aplicação.
 * 
 * Erros lógicos são normalmente causados por algum valor ou ação incorretos definidos pelo usuário do sistema, e o 
 * sistema deve poder se recuperar deles de maneira amigável (informando o usuário do erro erro ocorrido de maneira 
 * clara e possibilitando que ele refaça a ação ou informe novamente o valor que ocasionou o erro).
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;
class ExcecaoErroLogico extends ExcecaoAbstrata {
	
	const DEFAULT_CODE = 200;
	
	/**
	 * Tratamento genérico de exceção de erro lógico.
	 * 
	 * @access public
	 * @see \Numenor\Excecao\ExcecaoAbstrata::tratar()
	 */
	public function tratar() {
		// Exceções lógicas devem ser tratadas de maneira amigável, pois podem ter sido levantadas por uma
		// ação incorreta por parte do usuário
	}
}
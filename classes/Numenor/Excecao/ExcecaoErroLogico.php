<?php
namespace Numenor\Excecao;
class ExcecaoErroLogico extends ExcecaoAbstrata {
	
	public function tratar() {
		// Exceções lógicas devem ser tratadas de maneira amigável, pois podem ter sido levantadas por uma
		// ação incorreta por parte do usuário
	}
}
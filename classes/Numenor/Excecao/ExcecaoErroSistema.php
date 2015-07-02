<?php
namespace Numenor\Excecao;
class ExcecaoErroSistema extends ExcecaoAbstrata {
	
	public function tratar() {
		// Erros de sistema devem sempre encerrar a execução do script, pois o sistema entrou em um
		// estado impossível de recuperar
		die();
	}
}
<?php
namespace Numenor\Excecao;
class ExcecaoErroUso extends ExcecaoAbstrata {
	
	public function tratar() {
		// Erros de uso devem sempre encerrar a execução do script, pois o sistema entrou em um
		// estado de erro por negligência do programador.
		die();
	}
}
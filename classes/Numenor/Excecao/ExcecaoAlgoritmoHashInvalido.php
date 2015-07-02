<?php

namespace Numenor\Excecao;

class ExcecaoAlgoritmoHashInvalido extends ExcecaoErroUso {
	
	public function __construct(\Exception $previous = null) {
		parent::__construct('O algoritmo de hash informado é inválido ou não está disponível nesta versão do PHP.', self::CODE_ALGORITMO_HASH_INVALIDO, $previous);
	}
}
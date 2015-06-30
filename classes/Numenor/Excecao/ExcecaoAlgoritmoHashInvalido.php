<?php

namespace Numenor\Excecao;
use Zend\I18n\Translator\Translator;

class ExcecaoAlgoritmoHashInvalido extends ExcecaoAbstrata {
	
	public function __construct(\Exception $previous = null) {
		parent::__construct('O algoritmo de hash informado é inválido ou não está disponível nesta versão do PHP.', self::CODE_ALGORITMO_HASH_INVALIDO, $previous);
	}
}
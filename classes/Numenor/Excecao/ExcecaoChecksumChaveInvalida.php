<?php

namespace Numenor\Excecao;
use Zend\I18n\Translator\Translator;

class ExcecaoChecksumChaveInvalida extends ExcecaoAbstrata {
	
	public function __construct(\Exception $previous = null) {
		parent::__construct('A chave informada para o gerador de checksums é inválida', self::CODE_CHECKSUM_CHAVE_INVALIDA, $previous);
	}
}
<?php

namespace Numenor\Excecao;

class ExcecaoChecksumChaveInvalida extends ExcecaoErroUso {
	
	public function __construct(\Exception $previous = null) {
		parent::__construct('A chave informada para o gerador de checksums é inválida', self::CODE_CHECKSUM_CHAVE_INVALIDA, $previous);
	}
}
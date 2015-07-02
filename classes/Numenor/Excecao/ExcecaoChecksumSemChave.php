<?php

namespace Numenor\Excecao;

class ExcecaoChecksumSemChave extends ExcecaoErroUso {
	
	public function __construct(\Exception $previous = null) {
		parent::__construct('A chave do gerador de checksums ainda não foi definida. Você deve informá-la através do método Checksum::setChave', self::CODE_CHECKSUM_SEM_CHAVE, $previous);
	}
}
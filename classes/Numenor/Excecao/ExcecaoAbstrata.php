<?php

namespace Numenor\Excecao;

abstract class ExcecaoAbstrata extends \Exception {
	
	const CODE_CHECKSUM_CHAVE_INVALIDA = 1;
	const CODE_CHECKSUM_SEM_CHAVE = 2;
} 
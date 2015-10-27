<?php
/**
 * Classe abstrata de exceção utilizada pela biblioteca Numenor de forma que as exceções derivadas
 * possam ser capturadas pelo comando catch() de forma diferenciada de outras exceções levantadas 
 * pelo sistema.
 * Além disso, a exceção aglutina em um só lugar os códigos de exceção utilizados por todas as suas
 * subclasses, tornando mais fácil a refatoração e utilização dos mesmos em outros pontos da aplicação.
 * As exceções da biblioteca foram classificadas a partir do conceito de hierarquia de exceções de
 * Krzysztof Cwalina (http://blogs.msdn.com/b/kcwalina/archive/2007/01/30/exceptionhierarchies.aspx)
 * 
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;

abstract class ExcecaoAbstrata extends \Exception {
	
	const CODE_CHECKSUM_CHAVE_INVALIDA = 1;
	const CODE_CHECKSUM_SEM_CHAVE = 2;
	const CODE_ALGORITMO_HASH_INVALIDO = 3;
	const CODE_CACHE_DISCO_DIR_NAO_DEFINIDO = 4;
	const CODE_CACHE_DISCO_DIR_ESCRITA = 5;
	const CODE_ARQUIVO_CONFIG_INVALIDO = 6;
	const CODE_FN_PASSWORD_HASH_INVALIDO = 7;
	const CODE_FN_ARRAYFILL_INVALIDINDEX = 8;
	const CODE_FN_ARRAYFILL_NUMITEMS = 9;
	const CODE_FN_ARRAYCOMBINE_TAMANHOINVALIDO = 10;
	const CODE_FN_ARRAYFILTER_INVALIDFLAG = 11;
	const CODE_FN_ARRAYFLIP_INVALIDTYPE = 12;
	const CODE_ARRAYWRAPPER_CHAVETIPOINVALIDO = 13;
	const CODE_ARRAYWRAPPER_CHAVEINEXISTENTE = 14;
	const CODE_ARRAYWRAPPER_ORDEMINVALIDA = 15;
	const CODE_ARRAYWRAPPER_TIPOORDEMINVALIDA = 16;
	const CODE_FN_ARRAYPAD_TAMANHOINVALIDO = 17;
	
	/**
	 * Método abstrato para definição da forma de tratamento geral das exceções.
	 * Este método deve idealmente ser definido nas classes que definem as categorias da hierarquia 
	 * (ExcecaoErroLogico, ExcecaoErroSistema, ExcecaoErroUso). Porém, é possível sobrecarregar o
	 * método de forma que exceções específicas tenham tratamentos específicos
	 * 
	 * @abstract
	 * @access public
	 */
	abstract public function tratar();
} 
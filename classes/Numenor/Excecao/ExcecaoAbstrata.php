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
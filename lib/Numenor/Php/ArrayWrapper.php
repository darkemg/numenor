<?php
/**
 * Wrapper para as funções de array da biblioteca padrão do PHP.
 * Na minha opinião (e na de muitos desenvolvedores, para falar a verdade), um dos piores aspectos do
 * desenvolvimento em PHP é a nomenclatura das funções da biblioteca padrão. Muitas funções recebem nomes
 * absolutamente idiotas por razões históricas (basicamente, "o nome da função C que executa isso é esse,
 * vamos usar o mesmo nome"), o que acabou não só trazendo antigos hábitos de nomenclatura para dentro de
 * uma linguagem que se pretende moderna, como ainda introduziu alguns novos maus hábitos de nomenclatura
 * novos. E isso sem falar em problemas com ordem de parâmetros inconsistente, comportamentos inconsistentes
 * entre funções que deveriam ser parecidas, etc.
 * Esta classe visa prover uma interface normalizada e uniforme para estas funções de array, de modo que o
 * desenvolvedor possa facilmente autocompletá-las utilizando uma IDE minimamente decente sem ter que ficar
 * lembrando ou consultando a documentação do PHP para lembrar exatamente como determinada função funciona. Eu
 * acredito que os beneficios de clareza e legibilidade superem o custo de overhead de uma chamada a mais de
 * função para executar a operação.
 * No desenvolvimento desta parte da biblioteca, meu objetivo foi manter um equilíbrio entre o padrão minimalista de
 * nomes do PHP nativo e a ridícula verbosidade de nomes estilo Java. Abreviações, quando fazem sentido, foram
 * mantidas.
 * A classe é definida como abstrata para evitar a instanciação (todos os métodos são estáticos, portanto não há necessidade
 * de instanciar esta classe).
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Php
 * @abstract
 */
namespace Numenor\Php;
use Numenor\Excecao\Php\ArrayWrapper as ExcecaoArrayWrapper;
abstract class ArrayWrapper {
	
	/**
	 * Retorna uma cópia do array com os caracteres das suas chaves para maiúsculas ou minúsculas.
	 * Funciona para chaves com codificação UTF-8.
	 * 
	 * @access public
	 * @static
	 * @param array $array O array a ser alterado.
	 * @param int $case Indica qual a caixa para a qual os índices do array devem ser convertidos. Deve ser uma das
	 * constantes CASE_LOWER ou CASE_UPPER.   
	 * @return array Cópia do array com os índices alterados.
	 * @throws \Numenor\Excecao\ArrayWrapper\ExcecaoArrayChangeCaseKeyInvalido se o parâmetro $case não for igual a
	 * uma das constantes CASE_LOWER ou CASE_UPPER. 
	 */
	public static function changeCaseKey(array $array, $case = CASE_LOWER) {
		if ($case != CASE_LOWER && $case != CASE_UPPER) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayChangeCaseKeyInvalido();
		}
		$return = array();
		foreach ($array as $key => $value) {
			if ($case == CASE_LOWER) {
				$return[StringWrapper::lowerCase($key)] = $value;
			} else {
				$return[StringWrapper::upperCase($key)] = $value;
			}
		}
		return $return;
	}
	
	/**
	 * Divide um array em pedaços menores.
	 * 
	 * @access public
	 * @static
	 * @param array $array O array a ser dividido.
	 * @param int $size Tamanho máximo dos pedaços do array. O último pedaço pode ser menor do que o tamanho indicado.
	 * @param boolean $preserveKeys Indica se os pedaços devem preservar as suas chaves originais.
	 * @return array Array multidimensional contendo os pedaços do array.
	 */
	public static function split(array $array, $size, $preserveKeys = false) {
		return array_chunk($array, $size, $preserveKeys);
	}
	
	/**
	 * Retorna todos os valores de uma única coluna de um array multidimensional, como se fosse uma tabela.
	 * 
	 * <code>
	 * $array = array(
     *		array(
     *   		'id' => 2135,
     *   		'first_name' => 'John',
     *   		'last_name' => 'Doe',
     *		),
	 *	    array(
	 *	        'id' => 3245,
	 *	        'first_name' => 'Sally',
	 *	        'last_name' => 'Smith',
	 *	    ),
	 *	    array(
	 *	        'id' => 5342,
	 *	        'first_name' => 'Jane',
	 *	        'last_name' => 'Jones',
	 *	    ),
	 *	    array(
	 *	        'id' => 5623,
	 *	        'first_name' => 'Peter',
	 *	        'last_name' => 'Doe',
	 *	    )
	 *	);
	 * \Numenor\Php\ArrayWrapper::getColumnValues($array, 'first_name');
	 * // ['John', 'Sally', 'Jane', 'Peter']
	 * \Numenor\Php\ArrayWrapper::getColumnValues($array, 'first_name', 'id');
	 * // [2135 => 'John', 3245 => 'Sally', 5342 => 'Jane', 5623 => 'Peter'] 
	 * </code>
	 * 
	 * @access public
	 * @static 
	 * @param array $array Array multidimensional de origem dos valores.
	 * @param string $columnKey Nome da chave representando a coluna dos valores desejados.
	 * @param string $indexKey Nome da chave da coluna cujos valores serão usados como chave dos array
	 * resultante.
	 * @throws \Numenor\Excecap\ArrayWrapper\ExcecaoArrayColumnNumeroParametros se o número de parâmetros informados
	 * é inferior ao mínimo necessário (2).
	 * @throws \Numenor\Excecap\ArrayWrapper\ExcecaoArrayColumnChaveInvalida se o parâmetro $columnKey não é do tipo
	 * {String}, ou não pode ser convertido para uma string.
	 * @throws \Numenor\Excecap\ArrayWrapper\ExcecaoArrayColumnIndiceInvalido se o parâmetro $indexKey foi informado
	 * e não é do tipo {String}, ou não pode ser convertido para uma string.
	 * @return array Lista de todos os valores da coluna correspondente.
	 */
	public static function getColumnValues(array $array, $columnKey, $indexKey = null) {
		$argc = func_num_args();
		$params = func_get_args();
		if ($argc < 2) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayColumnNumeroParametros();
		}
		if (!is_int($params[1])
				&& !is_float($params[1])
				&& !is_string($params[1])
				&& $params[1] !== null
				&& !(is_object($params[1]) && method_exists($params[1], '__toString'))) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayColumnChaveInvalida();
		}
		if (isset($params[2])
				&& !is_int($params[2])
				&& !is_float($params[2])
				&& !is_string($params[2])
				&& !(is_object($params[2]) && method_exists($params[2], '__toString'))
		) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayColumnIndiceInvalido();
		}
		// Se a função array_column não existe (PHP 5.4 ou menor), provê a implementação da mesma
		if (function_exists('array_column')) {
			return array_column($array, $columnKey, $indexKey);
		}
		$paramsInput = $params[0];
		$paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
		$paramsIndexKey = null;
		if (isset($params[2])) {
			if (is_float($params[2]) || is_int($params[2])) {
				$paramsIndexKey = (int) $params[2];
			} else {
				$paramsIndexKey = (string) $params[2];
			}
		}
		$resultArray = array();
		foreach ($paramsInput as $row) {
			$key = $value = null;
			$keySet = $valueSet = false;
			if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
				$keySet = true;
				$key = (string) $row[$paramsIndexKey];
			}
			if ($paramsColumnKey === null) {
				$valueSet = true;
				$value = $row;
			} elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
				$valueSet = true;
				$value = $row[$paramsColumnKey];
			}
			if ($valueSet) {
				if ($keySet) {
					$resultArray[$key] = $value;
				} else {
					$resultArray[] = $value;
				}
			}
		}
		return $resultArray;
	}
	
	/**
	 * Combina dois arrays em um único array, utilizando os valores do primeiro como chaves e os valores do segundo como valores.
	 * 
	 * @access public
	 * @static
	 * @param array $keys Array de chaves.
	 * @param array $values Array de valores.
	 * @return array Combinação dos dois arrays informados.
	 * @throws \Numenor\Excecao\Php\ExcecaoArrayCombineTamanhosIncompativeis se os dois arrays informados não têm o mesmo
	 * tamanho. 
	 */
	public static function combine(array $keys, array $values) {
		if (count($keys) !== count($values)) {
			throw new ExcecaoArray\ExcecaoArrayCombineTamanhosIncompativeis();
		}
		return array_combine($keys, $values);
	}
	
	/**
	 * Conta todos os valores do array e retorna a frequência de ocorrência de cada um deles.
	 * 
	 * @access public
	 * @static
	 * @param array $array Array a ser analisado. 
	 * @return array Array com os valores do array informado como chaves, e a frequência de ocorrência como valores.
	 */
	public static function countValues(array $array) {
		return array_count_values($array);
	}
	
	/**
	 * Calcula a diferença entre dois arrays.
	 * Ao contrário da função array_diff(), este método aceita apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('first' => 'A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::difference($array1, $array2)
	 * // ['B', 'D']
	 * </code>
	 * 
	 * @access public
	 * @static
	 * @param array $array1 Primeiro array a ser comparado.
	 * @param array $array2 Segundo array a ser comparado.
	 * @return array Array contendo a diferença entre os dois arrays comparados.
	 */
	public static function difference(array $array1, array $array2) {
		return array_diff($array1, $array2);		
	}
	
	/**
	 * Calcula a diferença entre dois arrays, considerando tanto os valores quanto as chaves do mesmo.
	 * Ao contrário da função array_diff_assoc(), este método aceita apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::difference($array1, $array2)
	 * // ['B', 'C', 'D', 'third' => 'C']
	 * </code>
	 * 
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function associativeDifference(array $array1, array $array2) {
		return array_diff_assoc($array1, $array2);
	}
	
	/**
	 * Cria um array preenchido com um único valor repetido $numerItems vezes.
	 * Ao contrário da função array_fill(), foi optado por não permitir que o índice seja negativo, nem que o número
	 * de itens seja menor do que 1 (para manter compatibilidade com PHP <= 5.5).
	 *  
	 * @access public
	 * @static
	 * @param int $startIndex Índice inicial do array gerado.
	 * @param int $numberItems Número de itens do array gerado
	 * @param mixed $item Valor colocado em todas as posições do array
	 * @throws \Numenor\Excecao\ArrayWrapper\ExcecaoArrayFillIndiceInvalido se o $startIndex informado for negativo. 
	 * @throws ExcecaoArrayWrapper\ExcecaoArrayFillTamanhoInvalido se $numberItems for um valor menor do que 1.
	 * @return array O array preenchido.
	 */
	public static function create($startIndex, $numberItems, $item) {
		if ($startIndex < 0) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillIndiceInvalido();
		}
		if ($numberItems < 1) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillTamanhoInvalido();
		}
		return array_fill($startIndex, $numberItems, $item);
	}
	
	
}
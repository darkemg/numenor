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
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Php
 */
namespace Numenor\Php;
use Numenor\Excecao\Php\ArrayWrapper as ExcecaoArrayWrapper;
class ArrayWrapper {
	
	/**
	 * Retorna uma cópia do array com os caracteres das suas chaves para maiúsculas ou minúsculas.
	 * Funciona para chaves com codificação UTF-8.
	 * 
	 * @access public
	 * @param array $array O array a ser alterado.
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de strings.
	 * @param int $case Indica qual a caixa para a qual os índices do array devem ser convertidos. Deve ser uma das
	 * constantes CASE_LOWER ou CASE_UPPER.
	 * @throws \Numenor\Excecao\ArrayWrapper\ExcecaoArrayChangeCaseKeyInvalido se o parâmetro $case não for igual a
	 * uma das constantes CASE_LOWER ou CASE_UPPER. 
	 * @return array Cópia do array com os índices alterados.
	 */
	public function changeCaseKey(array $array, StringWrapper $stringWrapper, $case = CASE_LOWER) {
		if ($case != CASE_LOWER && $case != CASE_UPPER) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayChangeCaseKeyInvalido();
		}
		$retorno = array();
		foreach ($array as $chave => $valor) {
			if ($case == CASE_LOWER) {
				$retorno[$stringWrapper->lowerCase($chave)] = $valor;
			} else {
				$retorno[$stringWrapper->upperCase($chave)] = $valor;
			}
		}
		return $returno;
	}
	
	/**
	 * Divide um array em pedaços menores.
	 * 
	 * @access public
	 * @param array $array O array a ser dividido.
	 * @param int $size Tamanho máximo dos pedaços do array. O último pedaço pode ser menor do que o tamanho indicado.
	 * @param boolean $preserveKeys Indica se os pedaços devem preservar as suas chaves originais.
	 * @return array Array multidimensional contendo os pedaços do array.
	 */
	public function split(array $array, $size, $preserveKeys = false) {
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
	public function getColumnValues(array $array, $columnKey, $indexKey = null) {
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
		// Se a função array_column não existe (PHP <= 5.4), provê a implementação da mesma
		// Fonte: <Recommended userland implementation for PHP lower than 5.5>
		// http://php.net/manual/en/function.array-column.php
		// https://github.com/ramsey/array_column/blob/master/src/array_column.php
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
	 * @param array $keys Array de chaves.
	 * @param array $values Array de valores.
	 * @throws \Numenor\Excecao\Php\ExcecaoArrayCombineTamanhosIncompativeis se os dois arrays informados não têm o mesmo
	 * tamanho. 
	 * @return array Combinação dos dois arrays informados.
	 */
	public function combine(array $keys, array $values) {
		if (count($keys) !== count($values)) {
			throw new ExcecaoArray\ExcecaoArrayCombineTamanhosIncompativeis();
		}
		return array_combine($keys, $values);
	}
	
	/**
	 * Conta todos os valores do array e retorna a frequência de ocorrência de cada um deles.
	 * 
	 * @access public
	 * @param array $array Array a ser analisado. 
	 * @return array Array com os valores do array informado como chaves, e a frequência de ocorrência como valores.
	 */
	public function countValues(array $array) {
		return array_count_values($array);
	}
	
	/**
	 * Retorna a intersecção entre dois arrays, ou seja, todos os elementos que estão presentes em ambos os arrays, preservando
	 * as chaves dos mesmos.
	 * Caso um elemento tenha chaves diferentes nos dois arrays, a chave mantida é a chave do primeiro array.
	 * Ao contrário da função array_intersect(), este método aceita apenas dois arrays de cada vez.
	 * 
	 * * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('first' => 'A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::intersect($array1, $array2)
	 * // ['A', 'C']
	 * </code>
	 *
	 * @access public
	 * @param array $array1 Primeiro array a ser comparado.
	 * @param array $array2 Segundo array a ser comparado.
	 * @return array Array contendo a intersecção dos dois arrays comparados.
	 */
	public function intersect(array $array1, array $array2) {
		return array_intersect($array1, $array2);
	}
	
	/**
	 * Retorna a intersecção de chaves entre dois arrays, ou seja, todos os elementos cujas chaves estão presentes em ambos
	 * os arrays, preservando os valores dos mesmos.
	 * Caso uma chave tenha valores diferentes nos dois arrays, o valor mantido é o valor do primeiro array.
	 * Ao contrário da função array_intersect_key(), este método aceita apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'second' => 'B', 'third' => 'C');
	 * $array2 = array('first' => 'A', 'second' => 'D', 'final' => 'C');
	 * \Numenor\Php\ArrayWrapper::intersectKeys($array1, $array2)
	 * // ['A', 'second' => 'B']
	 * </code>
	 *
	 * @access public
	 * @param array $array1 Primeiro array a ser comparado.
	 * @param array $array2 Segundo array a ser comparado.
	 * @return array Array contendo a intersecção da chaves dos dois arrays comparados.
	 */
	public function intersectKeys(array $array1, array $array2) {
		return array_intersect_key($array1, $array2);
	}
	
	/**
	 * Calcula a intersecção entre dois arrays,  ou seja, todos os elementos do primeiro array que estão presentes no
	 * segundo array, considerando tanto os valores quanto as chaves do mesmo.
	 * Ao contrário da função array_intersect_assoc(), este método aceita apenas dois arrays de cada vez.
	 *
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::difference($array1, $array2)
	 * // ['A']
	 * </code>
	 *
	 * @access public
	 * @param array Primeiro array a ser comparado.
	 * @param array $array2 Segundo array a ser comparado.
	 * @return array Array contendo a intersecção dos dois arrays comparados.
	 */
	public function associativeIntersect(array $array1, array $array2) {
		return array_intersect_assoc($array1, $array2);
	}
	
	/**
	 * Calcula a diferença entre dois arrays, ou seja, todos os elementos do primeiro array que não estão presentes no 
	 * segundo array.
	 * Ao contrário da função array_diff(), este método aceita apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('first' => 'A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::difference($array1, $array2)
	 * // ['B']
	 * </code>
	 * 
	 * @access public
	 * @param array $array1 Primeiro array a ser comparado.
	 * @param array $array2 Segundo array a ser comparado.
	 * @return array Array contendo a diferença entre os dois arrays comparados.
	 */
	public function difference(array $array1, array $array2) {
		return array_diff($array1, $array2);		
	}
	
	/**
	 * Calcula a diferença entre dois arrays,  ou seja, todos os elementos do primeiro array que não estão presentes no 
	 * segundo array, considerando tanto os valores quanto as chaves do mesmo.
	 * Ao contrário da função array_diff_assoc(), este método aceita apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::difference($array1, $array2)
	 * // ['B', 'C']
	 * </code>
	 * 
	 * @access public
	 * @param array Primeiro array a ser comparado.
	 * @param array $array2 Segundo array a ser comparado.
	 * @return array Array contendo a diferença entre os dois arrays comparados.
	 */
	public function associativeDifference(array $array1, array $array2) {
		return array_diff_assoc($array1, $array2);
	}
	
	/**
	 * Cria um array preenchido com um único valor repetido $numerItems vezes.
	 * Ao contrário da função array_fill(), foi optado por não permitir que o índice seja negativo, nem que o número
	 * de itens seja menor do que 1 (para manter compatibilidade com PHP <= 5.5).
	 *  
	 * @access public
	 * @param int $startIndex Índice inicial do array gerado.
	 * @param int $numberItems Número de itens do array gerado
	 * @param mixed $item Valor colocado em todas as posições do array
	 * @throws \Numenor\Excecao\ArrayWrapper\ExcecaoArrayFillIndiceInvalido se o $startIndex informado for negativo. 
	 * @throws ExcecaoArrayWrapper\ExcecaoArrayFillTamanhoInvalido se $numberItems for um valor menor do que 1.
	 * @return array O array preenchido.
	 */
	public function create($startIndex, $numberItems, $item) {
		if ($startIndex < 0) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillIndiceInvalido();
		}
		if ($numberItems < 1) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillTamanhoInvalido();
		}
		return array_fill($startIndex, $numberItems, $item);
	}
	
	/**
	 * Filtra um array através de uma função de callback.
	 * A função de callback é chamada para cada item do array; se ela retornar true, o item é incluído no array filtrado.
	 * 
	 * @access public
	 * @param array $array O array a ser filtrado.
	 * @param callable $filterFunction Função aplicada em cada item do array filtrado.
	 * @param int $flag Flag determinando quais parâmetros são enviados para a função de callback. Se não for informado, o valor
	 * do item é informado; caso seja informado, deve ser uma das seguintes constantes: ARRAY_FILTER_USE_KEY (envia a chave do
	 * valor como único parâmetro) ou ARRAY_FILTER_USE_BOTH (envia o valor e a chave como parâmetros).
	 * @throws \Numenor\Excecao\ExcecaoArrayFilterFlagInvalida se o parâmetro $flag for informado com um valor inválido. 
	 * @return array O array filtrado.
	 */
	public function filter(array $array, callable $filterFunction, $flag = 0) {
		if ($flag && $flag != ARRAY_FILTER_USE_KEY && $flag != ARRAY_FILTER_USE_BOTH) {
			throw new ExcecaoArray\ExcecaoArrayFilterFlagInvalida();
		}
		return array_filter($array, $filterFunction, $flag);
	}
	
	/**
	 * Inverte o array de forma que as chaves tornem-se valores e vice-versa.
	 * Como a função array_flip() não interrompe a execução caso os valores do array não possam ser usados como chave, apenas
	 * emite um warning, foi acrescentado um laço extra para verificar todos os itens do array e levantar uma exceção caso
	 * isso ocorra. Por essa razão, a implementação deste método pode não ser indicada para processamento de alta performance. 
	 * 
	 * @access public
	 * @param array $array O array a ser invertido.
	 * @throws \Numenor\Excecao\ExcecaoArrayFlipTipoInvalido se qualquer um dos valores do array não poder ser convertido para
	 * um tipo que possa ser usado como chave do array invertido (string ou inteiro).
	 * @return array Array com as chaves e valores invertidos.
	 */
	public function flip(array $array) {
		$values = array_values($array);
		$isValid = true;
		foreach ($values as $item) {
			if (!is_int($item) 
					&& !is_string($item) 
					&& !is_object($item) && method_exists($item, '__toString')) {
				$isValid = false;
				break;
			}
		}
		if (!$isValid) {
			throw new ExcecaoArray\ExcecaoArrayFlipTipoInvalido();
		}
		return array_flip($array);
	}
	
}
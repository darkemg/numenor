<?php
/**
 * Wrapper para array e suas funções da biblioteca padrão do PHP.
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
	 * O array.
	 * @access protected
	 * @var array
	 */
	protected $array;
	
	/**
	 * Método construtor da classe.
	 * 
	 * @access public
	 * @param array $array O array a ser encapsulado.
	 */
	public function __construct(array $array) {
		$this->array = $array;
	}
	
	/**
	 * Valida o tipo de uma chave de array.
	 * Uma chave é considerada válida se ela for um inteiro, uma string, ou um objeto representável como uma string
	 * (ou seja, um objeto que implemente o método __toString).
	 * 
	 * @access protected
	 * @param mixed $chave A chave a ser validada.
	 * @return boolean TRUE se a chave é de um tipo válido, FALSE caso contrário.
	 */
	protected function validarTipoChave($chave) {
		return (!is_int($chave)
				&& !is_string($chave)
				&& is_object($chave) && !method_exists($chave, '__toString'));
	}
	
	/**
	 * Retorna o array encapsulado.
	 * 
	 * @access public
	 * @return array
	 */
	public function getArray() {
		return $this->array;
	}
	
	/**
	 * Retorna todas as chaves do array.
	 * 
	 * @access public
	 * @return \Numenor\Php\ArrayWrapper Lista de chaves do array.
	 */
	public function getChaves() {
		return new self(array_keys($this->array));
	}
	
	/**
	 * Retorna todas as chaves do array que possuam o valor informado.
	 * Ao contrário do funcionamento normal da função array_keys(), a comparação é sempre feita de forma estrita (operador ===).
	 * 
	 * <code>
	 * $array = new \Numenor\Php\ArrayWrapper(array('A', 'B', 'C', 'first' => 'A', 'second' => 'B'));
	 * $array->getChave('A');
	 * // [0, 'first']
	 * </code>
	 * 
	 * @access public
	 * @param mixed $valor O valor para o qual se quer as chaves.
	 * @return \Numenor\Php\ArrayWrapper Lista de chaves que possuem o valor informado.
	 */
	public function getChave($valor) {
		return new self(array_keys($this->array, $valor, true));
	}
	
	/**
	 * Retorna o item do array com a chave correspondente.
	 * 
	 * @access public
	 * @param string|int $chave Chave do item no array
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoChaveInexistente se a chave não existir no array.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoChaveInvalida se a chave não puder ser convertida para um tipo
	 * válido (string ou inteiro).
	 * @return mixed O item da posição correspondente do array.
	 */
	public function getItem($chave) {
		if (!$this->validarTipoChave($chave)) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInvalida();
		}
		if (!isset($this->array[$chave])) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInexistente();
		}
		return $this->array[$chave];
	}
	
	/**
	 * Retorna o tamanho do array.
	 * 
	 * @access public
	 * @return int O tamanho do array.
	 */
	public function getTamanho() {
		return count($this->array);
	}
	
	/**
	 * Retorna todos os valores de uma única coluna de um array multidimensional, como se fosse uma tabela.
	 *
	 * <code>
	 * $array = new \Numenor\Php\ArrayWrapper(array(
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
	 *	));
	 * $array->getValorColuna('first_name');
	 * // ['John', 'Sally', 'Jane', 'Peter']
	 * $array->getValorColuna('first_name', 'id');
	 * // [2135 => 'John', 3245 => 'Sally', 5342 => 'Jane', 5623 => 'Peter']
	 * </code>
	 *
	 * @access public
	 * @param string $colunaChave Nome da chave representando a coluna dos valores desejados.
	 * @param string $chaveIndice Nome da chave da coluna cujos valores serão usados como chave dos array
	 * resultante.
	 * @throws \Numenor\Excecap\ArrayWrapper\ExcecaoArrayColumnNumeroParametros se o número de parâmetros informados
	 * é inferior ao mínimo necessário (1).
	 * @throws \Numenor\Excecap\ArrayWrapper\ExcecaoArrayColumnChaveInvalida se o parâmetro $columnKey não é do tipo
	 * {String}, ou não pode ser convertido para uma string.
	 * @throws \Numenor\Excecap\ArrayWrapper\ExcecaoArrayColumnIndiceInvalido se o parâmetro $indexKey foi informado
	 * e não é do tipo {String}, ou não pode ser convertido para uma string.
	 * @return \Numenor\Php\ArrayWrapper Lista de todos os valores da coluna correspondente.
	 */
	public function getValorColuna($colunaChave, $chaveIndice = null) {
		if ($this->validarTipoChave($colunaChave)) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInvalida();
		}
		if ($chaveIndice && !$this->validarTipoChave($chaveIndice)) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInvalida();
		}
		// Se a função array_column não existe (PHP <= 5.4), provê a implementação da mesma
		// Fonte: <Recommended userland implementation for PHP lower than 5.5>
		// http://php.net/manual/en/function.array-column.php
		// https://github.com/ramsey/array_column/blob/master/src/array_column.php
		if (function_exists('array_column')) {
			return array_column($this->$array, $colunaChave, $chaveIndice);
		}
		$paramsInput = $this->array;
		$paramsColumnKey = ($colunaChave !== null) ? (string) $colunaChave : null;
		$paramsIndexKey = $chaveIndice;
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
		return new self($resultArray);
	}
	
	/**
	 * Verifica a existência de uma chave no array.
	 * 
	 * @access public
	 * @param string|int $chave Chave a ser verificada no array
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ChaveInvalida se a chave não puder ser convertida para um tipo
	 * válido (string ou inteiro). 
	 * @return boolean TRUE caso a chave exista no array, FALSE caso contrário.
	 */
	public function verificarChave($chave) {
		return array_key_exists($chave, $this->array);
	}
	
	/**
	 * Insere um item no final do array.
	 * 
	 * @access public
	 * @param mixed $item O item a ser adicionado no final do array.
	 */
	public function inserirFinal($item) {
		$this->array[] = $item;
	}
	
	/**
	 * Remove e retorna o item do final do array.
	 * 
	 * @access public
	 * @return mixed O item removido do final do array.
	 */
	public function removerFinal() {
		return array_pop($this->array);
	}
	
	/**
	 * Insere um item no início do array, incrementando todas as chaves numéricas dos outros itens. As chaves strings
	 * não são alteradas.
	 * 
	 * @access public
	 * @param mixed $item O item a ser adicionado no início do array.
	 */
	public function inserirInicio($item) {
		array_unshift($this->array, $item);		
	}
	
	/**
	 * Remove um item do início do array, decrementando todas as chaves numéricas dos outros itens. As chaves strings
	 * não são alteradas.
	 * 
	 * @access public
	 * @return mixed O item removido do início do array.
	 */
	public function removerInicio() {
		return array_shift($this->array);
	}
	
	/**
	 * Insere um item no array com a chave indicada. 
	 * Se a chave já existir, substitui o valor.
	 * 
	 * @access public
	 * @param mixed $item O item a ser inserido no array.
	 * @param int|string $chave A chave onde o item deve ser inserido.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ChaveInvalida se a chave não puder ser convertida para um tipo
	 * válido (string ou inteiro).
	 */
	public function inserir($item, $chave) {
		if (!$this->validarTipoChave($chave)) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInvalida();
		}
		$this->array[$chave] = $item;
	}
	
	/**
	 * Remove um item do array com a chave indicada.
	 * O array é reindexado após a remoção, atualizando suas chaves numéricas. As chaves string não são afetadas.
	 * 
	 * @access public
	 * @param int|string $chave A chave do item a ser removido.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoChaveInexistente se a chave não existir no array.
	 * @return mixed Item removido do array.
	 */
	public function remover($chave) {
		if (!isset($this->array[$chave])) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInexistente();
		}
		$retorno = $this->array[$chave];
		unset($this->array[$chave]);
		array_merge($this->array);
		return $retorno;
	}
	
	
	/**
	 * Cria um array preenchido com um único valor repetido $numerItems vezes.
	 * Ao contrário da função array_fill(), foi optado por não permitir que o índice seja negativo, nem que o número
	 * de itens seja menor do que 1 (para manter compatibilidade com PHP <= 5.5).
	 *
	 * @access public
	 * @static
	 * @param int $inicio Índice inicial do array gerado.
	 * @param int $quantidadeItens Número de itens do array gerado.
	 * @param mixed $item Valor colocado em todas as posições do array.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoArrayFillIndiceInvalido se o $inicio informado for negativo.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoArrayFillTamanhoInvalido se $quantidadeItens for um valor menor do que 1.
	 * @return \Numenor\Php\ArrayWrapper O array gerado.
	 */
	public static function criar($inicio, $quantidadeItens, $item) {
		if ($inicio < 0) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillIndiceInvalido();
		}
		if ($quantidadeItens < 1) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillTamanhoInvalido();
		}
		return new self(array_fill($inicio, $quantidadeItens, $item));
	}
	
	/**
	 * Divide o array em pedaços menores.
	 *
	 * @access public
	 * @param int $tamanho Tamanho máximo dos pedaços do array. O último pedaço pode ser menor do que o tamanho indicado.
	 * @param boolean $preservarChaves Indica se os pedaços devem preservar as suas chaves originais.
	 * @return \Numenor\Php\ArrayWrapper Array multidimensional contendo os pedaços do array.
	 */
	public function dividir($tamanho, $preservarChaves = false) {
		return new self(array_chunk($this->$array, $tamanho, $preservarChaves));
	}
	
	/**
	 * Combina dois arrays em um único array, utilizando os valores do array encapsulado como chaves e os valores do array informado
	 * como valores no array resultante.
	 *
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $valores Array de valores.
	 * @throws \Numenor\Excecao\Php\ExcecaoArrayCombineTamanhosIncompativeis se os dois arrays informados não têm o mesmo
	 * tamanho.
	 * @return \Numenor\Php\ArrayWrapper Combinação dos dois arrays informados.
	 */
	public function combinar(ArrayWrapper $valores) {
		if ($this->getTamanho() != $valores->getTamanho()) {
			throw new ExcecaoArray\ExcecaoArrayCombineTamanhosIncompativeis();
		}
		return new self(array_combine($keys->getArray(), $valores->getArray()));
	}
	
	/**
	 * Inverte o array de forma que as chaves tornem-se valores e vice-versa.
	 * Como a função array_flip() não interrompe a execução caso os valores do array não possam ser usados como chave, apenas
	 * emite um warning, foi acrescentado um laço extra para verificar todos os itens do array e levantar uma exceção caso
	 * isso ocorra. Por essa razão, a implementação deste método pode não ser indicada para processamento de alta performance.
	 *
	 * @access public
	 * @throws \Numenor\Excecao\ExcecaoArrayFlipTipoInvalido se qualquer um dos valores do array não poder ser convertido para
	 * um tipo que possa ser usado como chave do array invertido (string ou inteiro).
	 * @return \Numenor\Php\ArrayWrapper Array com as chaves e valores invertidos.
	 */
	public function flip() {
		$values = array_values($this->array);
		$isValid = true;
		foreach ($values as $item) {
			$isValid = $this->validarTipoChave($item);
		}
		if (!$isValid) {
			throw new ExcecaoArray\ExcecaoArrayFlipTipoInvalido();
		}
		return new self(array_flip($this->array));
	}
	
	/**
	 * Conta todos os valores do array e retorna a frequência de ocorrência de cada um deles.
	 *
	 * @access public
	 * @return \Numenor\Php\ArrayWrapper valores do array como chaves, e a frequência de ocorrência como valores.
	 */
	public function contarValores() {
		return new self(array_count_values($array));
	}
	
	/**
	 * Retorna uma cópia do array com os caracteres das suas chaves alterados para letras minúsculas.
	 * Funciona para chaves com codificação UTF-8.
	 * 
	 * @access public
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de strings. 
	 * @return \Numenor\Php\ArrayWrapper O array com as chaves alteradas.
	 */
	public function chaveMinuscula(StringWrapper $stringWrapper) {
		$array = array();
		foreach ($this->array as $chave => $valor) {
			$array[$stringWrapper->lowerCase($chave)] = $valor;
		}
		return new self($array);
	}
	
	/**
	 * Retorna uma cópia do array com os caracteres das suas chaves alterados para letras maiúsculas.
	 * Funciona para chaves com codificação UTF-8.
	 *
	 * @access public
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de strings.
	 * @return \Numenor\Php\ArrayWrapper O array com as chaves alteradas.
	 */
	public function chaveMaiuscula(StringWrapper $stringWrapper) {
		$array = array();
		foreach ($this->array as $chave => $valor) {
			$array[$stringWrapper->upperCase($chave)] = $valor;
		}
		return new self($array);
	}
	
	/**
	 * Retorna a intersecção entre o array instanciado e o array informado, ou seja, todos os elementos que estão presentes 
	 * em ambos os arrays, preservando as chaves dos mesmos.
	 * Caso um elemento tenha chaves diferentes nos dois arrays, a chave mantida é a chave do array instanciado.
	 * Ao contrário da função array_intersect(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = new \Numenor\Php\ArrayWrapper(array('A', 'B', 'C'));
	 * $array2 = new \Numenor\Php\ArrayWrapper(array('first' => 'A', 'second' => 'D', 'third' => 'C'));
	 * $array1->interseccao($array2);
	 * // ['A', 'C']
	 * </code>
	 *
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $array O array a ser comparado.
	 * @return \Numenor\Php\ArrayWrapper Array contendo a intersecção dos dois arrays comparados.
	 */
	public function interseccao(ArrayWrapper $array) {
		return new self(array_intersect($this->array, $array));
	}
	
	/**
	 * Retorna a intersecção de chaves entre o array instanciado e o array informado, ou seja, todos as chaves que estão presentes 
	 * em ambos os arrays, preservando os valores dos mesmos.
	 * Caso uma chave tenha valores diferentes nos dois arrays, o valor mantido é o valor do primeiro array.
	 * Ao contrário da função array_intersect_key(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = new \Numenor\Php\ArrayWrapper(array('A', 'second' => 'B', 'third' => 'C'));
	 * $array2 = new \Numenor\Php\ArrayWrapper(array('first' => 'A', 'second' => 'D', 'final' => 'C'));
	 * $array1->interseccaoChaves($array2)
	 * // ['A', 'second' => 'B']
	 * </code>
	 *
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $array O array a ser comparado.
	 * @return \Numenor\Php\ArrayWrapper Array contendo a intersecção da chaves dos dois arrays comparados.
	 */
	public function interseccaoChaves(ArrayWrapper $array) {
		return new self(array_intersect_key($this->array, $array));
	}
	
	/**
	 * Retorna a intersecção entre o array instanciado e o array informado, ou seja, todos os elementos que estão presentes 
	 * em ambos os arrays, considerando tanto os valores quanto as chaves do mesmo.
	 * Ao contrário da função array_intersect_assoc(), este método trabalha com apenas dois arrays de cada vez.
	 *
	 * <code>
	 * $array1 = new \Numenor\Php\ArrayWrapper(array('A', 'B', 'C'));
	 * $array2 = new \Numenor\Php\ArrayWrapper(array('A', 'second' => 'D', 'third' => 'C'));
	 * $array1->interseccaoAssociativa($array2);
	 * // ['A']
	 * </code>
	 *
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $array O array a ser comparado.
	 * @return \Numenor\Php\ArrayWrapper Array contendo a intersecção dos dois arrays comparados.
	 */
	public function intersecacaoAssociativa(array $array) {
		return new self(array_intersect_assoc($this->array, $array));
	}
	
	/**
	 * Retorna a diferença entre o array instanciado e o array informado, ou seja, todos os elementos do primeiro array 
	 * que não estão presentes no segundo array.
	 * Ao contrário da função array_diff(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = \Numenor\Php\ArrayWrapper(array('A', 'B', 'C'));
	 * $array2 = \Numenor\Php\ArrayWrapper(array('first' => 'A', 'second' => 'D', 'third' => 'C'));
	 * $array1->diferenca($array2);
	 * // ['B']
	 * </code>
	 * 
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $array O array a ser comparado.
	 * @return \Numenor\Php\ArrayWrapper Array contendo a diferença entre os dois arrays comparados.
	 */
	public function diferenca(ArrayWrapper $array) {
		return new self(array_diff($this->array, $array));		
	}
	
	/**
	 * Retorna a diferença entre o array instanciado e o array informado, ou seja, todos os elementos do 
	 * primeiro array que não estão presentes no segundo array, considerando tanto os valores quanto as chaves do mesmo.
	 * Ao contrário da função array_diff_assoc(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = \Numenor\Php\ArrayWrapper(array('A', 'B', 'C'));
	 * $array2 = \Numenor\Php\ArrayWrapper(array('A', 'second' => 'D', 'third' => 'C'));
	 * $array1->diferencaAssociativa($array2);
	 * // ['B', 'C']
	 * </code>
	 * 
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $array O array a ser comparado.
	 * @return \Numenor\Php\ArrayWrapper Array contendo a diferença entre os dois arrays comparados.
	 */
	public function diferencaAssociativa(ArrayWrapper $array) {
		return new self(array_diff_assoc($this->array, $array));
	}
	
	/**
	 * Filtra o array através de uma função de callback.
	 * A função de callback é chamada para cada item do array; se ela retornar true, o item é incluído no array filtrado.
	 * 
	 * @access public
	 * @param callable $funcaoFiltro Função aplicada em cada item do array filtrado.
	 * @param int $flag Flag determinando quais parâmetros são enviados para a função de callback. Se não for informado, o valor
	 * do item é informado; caso seja informado, deve ser uma das seguintes constantes: ARRAY_FILTER_USE_KEY (envia a chave do
	 * valor como único parâmetro) ou ARRAY_FILTER_USE_BOTH (envia o valor e a chave como parâmetros).
	 * @throws \Numenor\Excecao\ExcecaoArrayFilterFlagInvalida se o parâmetro $flag for informado com um valor inválido. 
	 * @return \Numenor\Php\ArrayWrapper O array filtrado.
	 */
	public function filtrar(callable $funcaoFiltro, $flag = 0) {
		if ($flag && $flag != ARRAY_FILTER_USE_KEY && $flag != ARRAY_FILTER_USE_BOTH) {
			throw new ExcecaoArray\ExcecaoArrayFilterFlagInvalida();
		}
		return new self(array_filter($this->array, $funcaoFiltro, $flag));
	}
	
	/**
	 * Aplica uma função de callback em cada item do array.
	 * 
	 * @access public
	 * @param callable $callback Função de callback aplicada em cada item do array. A função deve aceitar um parâmetro, correspondente
	 * ao item do array sendo percorrido.
	 * @return \Numenor\Php\ArrayWrapper O array alterado.
	 */
	public function aplicarCallback(callable $callback) {
		return new self(array_map($callback, $this->array)); 
	}
	
	/**
	 * Mescla o array instanciado com o array informado.
	 * Caso uma mesma chave esteja presente em ambos os arrays, então o array resultante terá o valor do segundo array nesta chave.
	 * Ao contrário da função array_merge(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * @access public
	 * @param \Numenor\Php\ArrayWrapper $array O array a ser mesclado com o array instanciado.
	 * @return \Numenor\Php\ArrayWrapper O array resultante da mescla dos dois arrays.
	 */
	public function mesclar(ArrayWrapper $array) {
		return new self(array_merge($this->array, $array));
	}
	
	//array_multisort()
	//array_pad()
	//array_product()
	//array_rand()
}
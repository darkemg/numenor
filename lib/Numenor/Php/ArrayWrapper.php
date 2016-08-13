<?php
namespace Numenor\Php;

use Numenor\Excecao\Php\ArrayWrapper as ExcecaoArrayWrapper;

/**
 * Wrapper para array e suas funções da biblioteca padrão do PHP.
 *
 * Na minha opinião (e na de muitos desenvolvedores, para falar a verdade), um dos piores aspectos do
 * desenvolvimento em PHP é a nomenclatura das funções da biblioteca padrão. Muitas funções recebem nomes
 * absolutamente idiotas por razões históricas (basicamente, "o nome da função C que executa isso é esse,
 * vamos usar o mesmo nome"), o que acabou não só trazendo antigos hábitos de nomenclatura para dentro de
 * uma linguagem que se pretende moderna, como ainda introduziu alguns novos maus hábitos de nomenclatura
 * novos. E isso sem falar em problemas com ordem de parâmetros inconsistente, comportamentos inconsistentes
 * entre funções que deveriam ser parecidas, etc.
 *
 * Esta classe visa prover uma interface normalizada e uniforme para estas funções de array, de modo que o
 * desenvolvedor possa facilmente autocompletá-las utilizando uma IDE minimamente decente sem ter que ficar
 * lembrando ou consultando a documentação do PHP para lembrar exatamente como determinada função funciona. Eu
 * acredito que os beneficios de clareza e legibilidade superem o custo de overhead de uma chamada a mais de
 * função para executar a operação.
 *
 * No desenvolvimento desta parte da biblioteca, meu objetivo foi manter um equilíbrio entre o padrão minimalista de
 * nomes do PHP nativo e a ridícula verbosidade de nomes estilo Java. Abreviações, quando fazem sentido, foram
 * mantidas.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Php
 */
class ArrayWrapper
{
	
	/**
	 * Identificador de ordenação crescente.
	 * 
	 * @var string
	 */
	const ORDER_ASC = 'ASC';
	/**
	 * Identificador de ordenação decrescente.
	 *
	 * @var string
	 */
	const ORDER_DESC = 'DESC';
	
	/**
	 * Valida o tipo de uma chave de array.
	 * Uma chave é considerada válida se ela for um inteiro, uma string, ou um objeto representável como uma string
	 * (ou seja, um objeto que implemente o método __toString).
	 * 
	 * @access protected
	 * @param mixed $chave A chave a ser validada.
	 * @return boolean TRUE se a chave é de um tipo válido, FALSE caso contrário.
	 */
	protected function validarTipoChave($chave) : bool
	{
		return (!is_int($chave) && !is_string($chave) && is_object($chave) && !method_exists($chave, '__toString'));
	}
	
	/**
	 * Retorna todas as chaves do array.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @return array Lista de chaves do array.
	 */
	public function getChaves(array $array) : array
	{
		return array_keys($array);
	}
	
	/**
	 * Retorna todas as chaves do array que possuam o valor informado.
	 * 
	 * Ao contrário do funcionamento normal da função array_keys(), a comparação é sempre feita de forma estrita 
	 * (operador ===).
	 * 
	 * <code>
	 * \Numenor\Php\ArrayWrapper::getChave(array('A', 'B', 'C', 'first' => 'A', 'second' => 'B'), 'A');
	 * // [0, 'first']
	 * </code>
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param mixed $valor O valor para o qual se quer as chaves.
	 * @return array Lista de chaves que possuem o valor informado.
	 */
	public function getChave(array $array, $valor) : array
	{
		return array_keys($array, $valor, true);
	}
	
	/**
	 * Retorna um ou mais itens do array escolhidos aleatoriamente.
	 *
	 * @access public
	 * @param array $array O array.
	 * @param int $numeroItens Número de itens desejados. O padrão é 1 (apenas um item).
	 * @return mixed|array O item escolhido aleatoriamente, caso $numeroItens seja um; um array com $numeroItens 
	 * escolhidos aleatoriamente, caso contrário.
	 */
	public function getItemAleatorio(array $array, int $numeroItens = 1)
	{
		return array_rand($array, $numeroItens);
	}
	
	/**
	 * Retorna todos os valores de uma única coluna de um array multidimensional, como se fosse uma tabela.
	 *
	 * <code>
	 * $array = [
	 *		[
	 *   		'id' => 2135,
	 *   		'first_name' => 'John',
	 *   		'last_name' => 'Doe',
	 *		],
	 *	    [
	 *	        'id' => 3245,
	 *	        'first_name' => 'Sally',
	 *	        'last_name' => 'Smith',
	 *	    ],
	 *	    [
	 *	        'id' => 5342,
	 *	        'first_name' => 'Jane',
	 *	        'last_name' => 'Jones',
	 *	    ],
	 *	    [
	 *	        'id' => 5623,
	 *	        'first_name' => 'Peter',
	 *	        'last_name' => 'Doe',
	 *	    ]
	 *	];
	 * \Numenor\Php\ArrayWrapper::getValorColuna($array, 'first_name');
	 * // ['John', 'Sally', 'Jane', 'Peter']
	 * \Numenor\Php\ArrayWrapper::getValorColuna($array, 'first_name', 'id');
	 * // [2135 => 'John', 3245 => 'Sally', 5342 => 'Jane', 5623 => 'Peter']
	 * </code>
	 *
	 * @access public
	 * @param array $array O array.
	 * @param string $colunaChave Nome da chave representando a coluna dos valores desejados.
	 * @param string $chaveIndice Nome da chave da coluna cujos valores serão usados como chave dos array
	 * resultante.
	 * @throws \Numenor\Excecao\ArrayWrapper\ExcecaoArrayColumnNumeroParametros se o número de parâmetros informados
	 * é inferior ao mínimo necessário (1).
	 * @throws \Numenor\Excecao\ArrayWrapper\ExcecaoArrayColumnChaveInvalida se o parâmetro $columnKey não é do tipo
	 * {String}, ou não pode ser convertido para uma string.
	 * @throws \Numenor\Excecao\ArrayWrapper\ExcecaoArrayColumnIndiceInvalido se o parâmetro $indexKey foi informado
	 * e não é do tipo {String}, ou não pode ser convertido para uma string.
	 * @return array Lista de todos os valores da coluna correspondente.
	 */
	public function getValorColuna(array $array, string $colunaChave, string $chaveIndice = '') : array
	{
		if ($this->validarTipoChave($colunaChave)) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInvalida();
		}
		if ($chaveIndice && !$this->validarTipoChave($chaveIndice)) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInvalida();
		}
		return array_column($array, $colunaChave, $chaveIndice);
	}
	
	/**
	 * Busca um item no array, retornando a chave correspondente ao item se encontrado.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param mixed $item O item buscado.
	 * @return mixed A chave correspondente ao item encontrado no array.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoItemNaoEncontrado se o item não existe no array.
	 */
	public function encontrarItem(array $array, $item)
	{
		$indiceItem = array_search($item, $array, true);
		if ($indiceItem === false) {
			throw new ExcecaoArrayWrapper\ExcecaoItemNaoEncontrado();
		}
		return $indiceItem;
	}
	
	/**
	 * Verifica a existência de uma chave no array.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param string|int $chave Chave a ser verificada no array
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ChaveInvalida se a chave não puder ser convertida para um tipo
	 * válido (string ou inteiro). 
	 * @return boolean TRUE caso a chave exista no array, FALSE caso contrário.
	 */
	public function verificarChave(array $array, $chave) : bool
	{
		return array_key_exists($chave, $array);
	}
	
	/**
	 * Insere um item no final do array.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param mixed $item O item a ser adicionado no final do array.
	 */
	public function inserirFinal(array &$array, $item)
	{
		$array[] = $item;
	}
	
	/**
	 * Remove e retorna o item do final do array.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @return mixed O item removido do final do array.
	 */
	public function removerFinal(array &$array)
	{
		return array_pop($array);
	}
	
	/**
	 * Insere um item no início do array, incrementando todas as chaves numéricas dos outros itens. As chaves strings
	 * não são alteradas.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param mixed $item O item a ser adicionado no início do array.
	 */
	public function inserirInicio(array &$array, $item)
	{
		array_unshift($array, $item);
	}
	
	/**
	 * Remove um item do início do array, decrementando todas as chaves numéricas dos outros itens. As chaves strings
	 * não são alteradas.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @return mixed O item removido do início do array.
	 */
	public function removerInicio(array &$array)
	{
		return array_shift($array);
	}
	
	/**
	 * Insere um item no array com a chave indicada. 
	 * 
	 * Se a chave já existir, substitui o valor.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param mixed $item O item a ser inserido no array.
	 * @param int|string $chave A chave onde o item deve ser inserido.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ChaveInvalida se a chave não puder ser convertida para um tipo
	 * válido (string ou inteiro).
	 */
	public function inserir(array &$array, $item, $chave)
	{
		if (!$this->validarTipoChave($chave)) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInvalida();
		}
		$array[$chave] = $item;
	}
	
	/**
	 * Remove um item do array com a chave indicada.
	 * 
	 * O array é reindexado após a remoção, atualizando suas chaves numéricas. As chaves string não são afetadas.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param int|string $chave A chave do item a ser removido.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoChaveInexistente se a chave não existir no array.
	 * @return mixed Item removido do array.
	 */
	public function remover(array &$array, $chave)
	{
		if (!isset($array[$chave])) {
			throw new ExcecaoArrayWrapper\ExcecaoChaveInexistente();
		}
		$retorno = $array[$chave];
		unset($array[$chave]);
		array_merge($array);
		return $retorno;
	}
	
	
	/**
	 * Cria um array preenchido com um único valor repetido $quantidadeItens vezes.
	 * 
	 * Ao contrário da função array_fill(), foi optado por não permitir que o índice seja negativo, nem que o número
	 * de itens seja menor do que 1.
	 *
	 * @access public
	 * @static
	 * @param int $inicio Índice inicial do array gerado.
	 * @param int $quantidadeItens Número de itens do array gerado.
	 * @param mixed $item Valor colocado em todas as posições do array.
	 * @return array O array gerado.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoArrayFillIndiceInvalido se o $inicio informado for negativo.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoArrayFillTamanhoInvalido se $quantidadeItens for um valor menor 
	 * do que 1.
	 */
	public static function criar(int $inicio, int $quantidadeItens, $item) : array
	{
		if ($inicio < 0) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillIndiceInvalido();
		}
		if ($quantidadeItens < 1) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayFillTamanhoInvalido();
		}
		return array_fill($inicio, $quantidadeItens, $item);
	}
	
	/**
	 * Divide o array em pedaços menores.
	 *
	 * @access public
	 * @param array $array O array.
	 * @param int $tamanho Tamanho máximo dos pedaços do array. O último pedaço pode ser menor do que o tamanho indicado.
	 * @param boolean $preservarChaves Indica se os pedaços devem preservar as suas chaves originais.
	 * @return array Array multidimensional contendo os pedaços do array.
	 */
	public function dividir(array $array, int $tamanho, bool $preservarChaves = false) : array
	{
		return array_chunk($array, $tamanho, $preservarChaves);
	}
	
	/**
	 * Combina dois arrays em um único array, utilizando os valores do primeiro array informado como chaves e os valores
	 * do segundo array informado como valores no array resultante.
	 *
	 * @access public
	 * @param array $chaves Array de chaves.
	 * @param array $valores Array de valores.
	 * @throws \Numenor\Excecao\Php\ExcecaoArrayCombineTamanhosIncompativeis se os dois arrays informados não têm o 
	 * mesmo tamanho.
	 * @return array Combinação dos dois arrays informados.
	 */
	public function combinar(array $chaves, array $valores)
	{
		if (sizeof($chaves) != sizeof($valores)) {
			throw new ExcecaoArray\ExcecaoArrayCombineTamanhosIncompativeis();
		}
		return array_combine($chaves, $valores);
	}
	
	/**
	 * Preenche o array com um valor informado até o tamanho informado.
	 * 
	 * Se o $novoTamanho for um número positivo, os itens são acrescentados no final do array.
	 * 
	 * Se o $novoTamanho for um número negativo, os itens são acrescentados no início do array.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param int $novoTamanho Novo tamanho desejado para o array.
	 * @param mixed $valor Valor a ser inserido nas novas posições do array.
	 * @return array O array preenchido com os novos valores.
	 */
	public function preencher(array $array, int $novoTamanho, $valor) : array
	{
		if (($novoTamanho > 0 && $novoTamanho <= sizeof($array))
				|| ($novoTamanho < 0 && abs($novoTamanho) <= sizeof($array))) {
			throw new ExcecaoArrayWrapper\ExcecaoArrayPadTamanhoInvalido();
		}
		return array_pad($array, $novoTamanho, $valor);
	}
	
	/**
	 * Inverte o array de forma que as chaves tornem-se valores e vice-versa.
	 * 
	 * Como a função array_flip() não interrompe a execução caso os valores do array não possam ser usados como chave, 
	 * apenas emite um warning, foi acrescentado um laço extra para verificar todos os itens do array e levantar uma 
	 * exceção caso isso ocorra. Por essa razão, a implementação deste método pode não ser indicada para processamento 
	 * de alta performance.
	 *
	 * @access public
	 * @param array $array O array.
	 * @throws \Numenor\Excecao\ExcecaoArrayFlipTipoInvalido se qualquer um dos valores do array não poder ser 
	 * convertido para um tipo que possa ser usado como chave do array invertido (string ou inteiro).
	 * @return array Array com as chaves e valores invertidos.
	 */
	public function flip(array $array) : array
	{
		$values = array_values($array);
		$isValid = true;
		foreach ($values as $item) {
			$isValid = $this->validarTipoChave($item);
		}
		if (!$isValid) {
			throw new ExcecaoArray\ExcecaoArrayFlipTipoInvalido();
		}
		return array_flip($array);
	}
	
	/**
	 * Conta todos os valores do array e retorna a frequência de ocorrência de cada um deles.
	 *
	 * @access public
	 * @param array $array O array.
	 * @return array Valores do array como chaves, e a frequência de ocorrência como valores.
	 */
	public function contarValores(array $array) : array
	{
		return array_count_values($array);
	}
	
	/**
	 * Retorna uma cópia do array com os caracteres das suas chaves alterados para letras minúsculas.
	 * 
	 * Funciona para chaves com codificação UTF-8.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de strings. 
	 * @return array O array com as chaves alteradas.
	 */
	public function chaveMinuscula(array $array, StringWrapper $stringWrapper) : array
	{
		$retorno = array();
		foreach ($array as $chave => $valor) {
			$retorno[$stringWrapper->lowerCase($chave)] = $valor;
		}
		return $retorno;
	}
	
	/**
	 * Retorna uma cópia do array com os caracteres das suas chaves alterados para letras maiúsculas.
	 * 
	 * Funciona para chaves com codificação UTF-8.
	 *
	 * @access public
	 * @param array $array O array.
	 * @param \Numenor\Php\StringWrapper $stringWrapper Instância do objeto de encapsulamento das operações de strings.
	 * @return array O array com as chaves alteradas.
	 */
	public function chaveMaiuscula(array $array, StringWrapper $stringWrapper) : array
	{
		$retorno = array();
		foreach ($array as $chave => $valor) {
			$retorno[$stringWrapper->upperCase($chave)] = $valor;
		}
		return $retorno;
	}
	
	/**
	 * Retorna a intersecção entre dois arrays, ou seja, todos os elementos que estão presentes em ambos os arrays, 
	 * preservando as chaves dos mesmos.
	 * 
	 * Caso um elemento tenha chaves diferentes nos dois arrays, a chave mantida é a chave do primeiro array.
	 * Ao contrário da função array_intersect(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('first' => 'A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::interseccao($array1, $array2);
	 * // ['A', 'C']
	 * </code>
	 *
	 * @access public
	 * @param array $array1 Primeiro array.
	 * @param array $array2 Segundo array.
	 * @return array Intersecção dos dois arrays comparados.
	 */
	public function interseccao(array $array1, array $array2) : array
	{
		return array_intersect($array1, $array2);
	}
	
	/**
	 * Retorna a intersecção de chaves entre dois arrays, ou seja, todos as chaves que estão presentes em ambos os 
	 * arrays, preservando os valores dos mesmos.
	 * 
	 * Caso uma chave tenha valores diferentes nos dois arrays, o valor mantido é o valor do primeiro array.
	 * 
	 * Ao contrário da função array_intersect_key(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'second' => 'B', 'third' => 'C');
	 * $array2 = array('first' => 'A', 'second' => 'D', 'final' => 'C');
	 * \Numenor\Php\ArrayWrapper::interseccaoChaves($array1, $array2);
	 * // ['A', 'second' => 'B']
	 * </code>
	 *
	 * @access public
	 * @param array $array1 Primeiro array.
	 * @param array $array2 Segundo array.
	 * @return array Intersecção da chaves dos dois arrays comparados.
	 */
	public function interseccaoChaves(array $array1, array $array2) : array
	{
		return array_intersect_key($array1, $array2);
	}
	
	/**
	 * Retorna a intersecção entre o dois arrays, ou seja, todos os elementos que estão presentes em ambos os arrays, 
	 * considerando tanto os valores quanto as chaves do mesmo.
	 * 
	 * Ao contrário da função array_intersect_assoc(), este método trabalha com apenas dois arrays de cada vez.
	 *
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::interseccaoAssociativa($array1, $array2);
	 * // ['A']
	 * </code>
	 *
	 * @access public
	 * @param array $array1 Primeiro array.
	 * @param array $array2 Segundo array.
	 * @return array Array contendo a intersecção dos dois arrays comparados.
	 */
	public function intersecacaoAssociativa(array $array1, array $array2) : array
	{
		return array_intersect_assoc($array1, $array2);
	}
	
	/**
	 * Retorna a diferença entre o primeiro e o segundo array, ou seja, todos os elementos do primeiro array que não 
	 * estão presentes no segundo array.
	 * 
	 * Ao contrário da função array_diff(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('first' => 'A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::diferenca($array1, $array2);
	 * // ['B']
	 * </code>
	 * 
	 * @access public
	 * @param array $array1 Primeiro array.
	 * @param array $array2 Segundo array.
	 * @return array Array contendo a diferença entre os dois arrays comparados.
	 */
	public function diferenca(array $array1, array $array2) : array
	{
		return array_diff($array1, $array2);
	}
	
	/**
	 * Retorna a diferença entre o array instanciado e o array informado, ou seja, todos os elementos do primeiro array 
	 * que não estão presentes no segundo array, considerando tanto os valores quanto as chaves do mesmo.
	 * 
	 * Ao contrário da função array_diff_assoc(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * <code>
	 * $array1 = array('A', 'B', 'C');
	 * $array2 = array('A', 'second' => 'D', 'third' => 'C');
	 * \Numenor\Php\ArrayWrapper::diferencaAssociativa($array1, $array2);
	 * // ['B', 'C']
	 * </code>
	 * 
	 * @access public
	 * @param array $array1 Primeiro array.
	 * @param array $array2 Segundo array.
	 * @return array Array contendo a diferença entre os dois arrays comparados.
	 */
	public function diferencaAssociativa(array $array1, array $array2) : array
	{
		return array_diff_assoc($array1, $array2);
	}
	
	/**
	 * Calcula o produto de todos os valores do array.
	 * 
	 * <code>
	 * $array = array(2, 4, 6);
	 * \Numenor\Php\ArrayWrapper::produto($array);
	 * // 48 
	 * </code>
	 * 
	 * @access public
	 * @param array $array O array.
	 * @return number O produto dos valores do array.
	 */
	public function produto(array $array)
	{
		return array_product($array);
	}
	
	/**
	 * Filtra o array através de uma função de callback.
	 * 
	 * A função de callback é chamada para cada item do array; se ela retornar true, o item é incluído no array filtrado.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param callable $funcaoFiltro Função aplicada em cada item do array filtrado.
	 * @param int $flag Flag determinando quais parâmetros são enviados para a função de callback. Se não for informado,
	 * o valor do item é informado; caso seja informado, deve ser uma das seguintes constantes: ARRAY_FILTER_USE_KEY 
	 * (envia a chave do valor como único parâmetro) ou ARRAY_FILTER_USE_BOTH (envia o valor e a chave como parâmetros).
	 * @throws \Numenor\Excecao\ExcecaoArrayFilterFlagInvalida se o parâmetro $flag for informado com um valor inválido. 
	 * @return array O array filtrado.
	 */
	public function filtrar(array $array, callable $funcaoFiltro, int $flag = 0) : array
	{
		if ($flag && $flag != ARRAY_FILTER_USE_KEY && $flag != ARRAY_FILTER_USE_BOTH) {
			throw new ExcecaoArray\ExcecaoArrayFilterFlagInvalida();
		}
		return array_filter($array, $funcaoFiltro, $flag);
	}
	
	/**
	 * Aplica uma função de callback em cada item do array.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param callable $callback Função de callback aplicada em cada item do array. A função deve aceitar um parâmetro, 
	 * correspondente ao item do array sendo percorrido.
	 * @return array O array alterado.
	 */
	public function aplicarCallback(array $array, callable $callback) : array
	{
		return array_map($callback, $array); 
	}
	
	/**
	 * Mescla dois arrays.
	 * 
	 * Caso uma mesma chave esteja presente em ambos os arrays, então o array resultante terá o valor do segundo array 
	 * nesta chave.
	 * 
	 * Ao contrário da função array_merge(), este método trabalha com apenas dois arrays de cada vez.
	 * 
	 * @access public
	 * @param array $array1 Primeiro array.
	 * @param array $array2 Segundo array.
	 * @return array O array resultante da mescla dos dois arrays.
	 */
	public function mesclar(array $array1, array $array2) : array
	{
		return array_merge($array1, $array2);
	}
	
	/**
	 * Ordena o array, utilizando a implementação nativa do PHP para o algoritmo Quicksort.
	 * 
	 * Ao contrário da maioria das funções de array da biblioteca padrão, a função sort() não retorna uma cópia do array
	 * informado; ao invés disso, o parâmetro é passado por referência, e a função retorna um valor booleano. Para fins 
	 * de padronização, este método retorna uma cópia do array.
	 * 
	 * ATENÇÃO: as chaves do array ordenado não são preservadas; o array é reindexado com chaves numéricas, o que pode 
	 * ou não ser o comportamento desejado.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param string $ordem Indica se a ordenação deve ser em ordem crescente ou decrescente.
	 * @param int $tipoOrdenacao Indica o tipo de comparação feita entre os valores: 
	 * 		- SORT_REGULAR não faz conversão de tipo entre os valores (podendo levar a resultados inesperados se os 
	 * 		tipos dos valores são diferentes), 
	 * 		- SORT_NUMERIC converte os valores para números para fazer a ordenação
	 * 		- SORT_STRING converte os valores para texto
	 * 		- SORT_LOCALE_STRING é igual a SORT_STRING, mas usando o locale definido na configuração do PHP
	 * 		- SORT_NATURAL faz a ordenação como "string em ordem natural", ou seja, algo como "1, 2, 10, 11".
	 * @return array O array ordenado.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoOrdemInvalida se a ordem não corresponde a uma das ordens 
	 * aceitas (crescente ou 
	 * decrescente).
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoTipoOrdemInvalida se o tipo de ordenação não corresponde a um 
	 * dos tipos de ordenação aceitos. 
	 */
	public function ordenar(array $array, string $ordem = self::ASC, int $tipoOrdenacao = \SORT_STRING) : array
	{
		if (!in_array($ordem, array(self::ASC, self::DESC))) {
			throw new ExcecaoArrayWrapper\ExcecaoOrdemInvalida();
		}
		if (!in_array($tipoOrdenacao, array(\SORT_REGULAR, \SORT_NUMERIC, \SORT_STRING, \SORT_LOCALE_STRING, \SORT_NATURAL))) {
			throw new ExcecaoArrayWrapper\ExcecaoTipoOrdemInvalida();
		}
		$copia = $array;
		switch ($ordem) {
			case self::ASC:
				sort($copia, $tipoOrdenacao);
				break;
			case self::DESC:
				rsort($copia, $tipoOrdenacao);
				break;
		}
		return $copia;
	}
	
	/**
	 * Ordena o array, utilizando a implementação nativa do PHP para o algoritmo Quicksort, e mantendo a associação 
	 * entre chaves e valores.
	 * 
	 * Ao contrário da maioria das funções de array da biblioteca padrão, a função sort() não retorna uma cópia do array
	 * informado; ao invés disso, o parâmetro é passado por referência, e a função retorna um valor booleano.
	 * 
	 * Para fins de padronização, este método retorna uma cópia do array.
	 *
	 * @access public
	 * @param array $array O array.
	 * @param string $ordem Indica se a ordenação deve ser em ordem crescente ou decrescente.
	 * @param int $tipoOrdenacao Indica o tipo de comparação feita entre os valores:
	 * 		- SORT_REGULAR não faz conversão de tipo entre os valores (podendo levar a resultados inesperados se os 
	 * 		tipos dos valores são diferentes),
	 * 		- SORT_NUMERIC converte os valores para números para fazer a ordenação
	 * 		- SORT_STRING converte os valores para texto
	 * 		- SORT_LOCALE_STRING é igual a SORT_STRING, mas usando o locale definido na configuração do PHP
	 * 		- SORT_NATURAL faz a ordenação como "string em ordem natural", ou seja, algo como "1, 2, 10, 11".
	 * @return array O array ordenado, com as suas chaves preservadas.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoOrdemInvalida se a ordem não corresponde a uma das ordens 
	 * aceitas (crescente ou decrescente).
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoTipoOrdemInvalida se o tipo de ordenação não corresponde a um 
	 * dos tipos de ordenação aceitos.
	 */
	public function ordenarAssociativo(array $array, string $ordem = self::ASC, int $tipoOrdenacao = SORT_STRING) : array
	{
		if (!in_array($ordem, array(self::ASC, self::DESC))) {
			throw new ExcecaoArrayWrapper\ExcecaoOrdemInvalida();
		}
		if (!in_array($tipoOrdenacao, array(\SORT_REGULAR, \SORT_NUMERIC, \SORT_STRING, \SORT_LOCALE_STRING, \SORT_NATURAL))) {
			throw new ExcecaoArrayWrapper\ExcecaoTipoOrdemInvalida();
		}
		$copia = $array;
		switch ($ordem) {
			case self::ASC:
				asort($copia, $tipoOrdenacao);
				break;
			case self::DESC:
				arsort($copia, $tipoOrdenacao);
				break;
		}
		return $copia;
	}
	
	/**
	 * Ordena o array pelas suas chaves, utilizando a implementação nativa do PHP para o algoritmo Quicksort.
	 * 
	 * Ao contrário da maioria das funções de array da biblioteca padrão, a função ksort() não retorna uma cópia do 
	 * array informado; ao invés disso, o parâmetro é passado por referência, e a função retorna um valor booleano.
	 * 
	 * Para fins de padronização, este método retorna uma cópia do array.
	 * 
	 * @access public
	 * @param array $array O array.
	 * @param string $ordem Indica se a ordenação deve ser em ordem crescente ou decrescente.
	 * @param int $tipoOrdenacao Indica o tipo de comparação feita entre os valores: 
	 * 		- SORT_REGULAR não faz conversão de tipo entre os valores (podendo levar a resultados inesperados se os 
	 * 		tipos dos valores são diferentes), 
	 * 		- SORT_NUMERIC converte os valores para números para fazer a ordenação
	 * 		- SORT_STRING converte os valores para texto
	 * 		- SORT_LOCALE_STRING é igual a SORT_STRING, mas usando o locale definido na configuração do PHP
	 * 		- SORT_NATURAL faz a ordenação como "string em ordem natural", ou seja, algo como "1, 2, 10, 11".
	 * @return array O array ordenado pelas chaves.
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoOrdemInvalida se a ordem não corresponde a uma das ordens 
	 * aceitas (crescente ou decrescente).
	 * @throws \Numenor\Excecao\Php\ArrayWrapper\ExcecaoTipoOrdemInvalida se o tipo de ordenação não corresponde a um 
	 * dos tipos de ordenação aceitos. 
	 */
	public function ordenarChave(array $array, string $ordem = self::ASC, int $tipoOrdenacao = SORT_STRING) : array
	{
		if (!in_array($ordem, array(self::ASC, self::DESC))) {
			throw new ExcecaoArrayWrapper\ExcecaoOrdemInvalida();
		}
		if (!in_array($tipoOrdenacao, array(\SORT_REGULAR, \SORT_NUMERIC, \SORT_STRING, \SORT_LOCALE_STRING, \SORT_NATURAL))) {
			throw new ExcecaoArrayWrapper\ExcecaoTipoOrdemInvalida();
		}
		$copia = $array;
		switch ($ordem) {
			case self::ASC:
				ksort($copia, $tipoOrdenacao);
				break;
			case self::DESC:
				krsort($copia, $tipoOrdenacao);
				break;
		}
		return $copia;
	}
}

<?php
namespace Numenor\Php;

use Numenor\Excecao\Php\StringWrapper as ExcecaoString;

/**
 * Wrapper para as funções de string da biblioteca padrão do PHP.
 *
 * Na minha opinião (e na de muitos desenvolvedores, para falar a verdade), um dos piores aspectos do desenvolvimento em
 * PHP é a nomenclatura das funções da biblioteca padrão. Muitas funções recebem nomes absolutamente idiotas por razões
 * históricas (basicamente, "o nome da função C que executa isso é esse, vamos usar o mesmo nome"), o que acabou não só
 * trazendo antigos hábitos de nomenclatura para dentro de uma linguagem que se pretende moderna, como ainda introduziu
 * alguns novos maus hábitos de nomenclatura novos. E isso sem falar em problemas com ordem de parâmetros inconsistente,
 * comportamentos inconsistentes entre funções que deveriam ser parecidas, etc.
 *
 * O caso das funções de string é bastante emblemático, pois há exemplos de praticamente todos esses problemas. O site
 * PHP Sadness (http://phpsadness.com) faz um bom trabalho de listar os casos, mas basta olhar para coisas como nl2br(),
 * parse_str() e explode() e notar que não há padrão algum na forma como essas funções são nomeadas, ou seus parâmetros
 * ordenados em comparação com outras funções semelhantes.
 *
 * Esta classe visa prover uma interface normalizada e uniforme para estas funções de string, de modo que o
 * desenvolvedor possa facilmente autocompletá-las utilizando uma IDE minimamente decente sem ter que ficar lembrando ou
 * consultando a documentação do PHP para lembrar exatamente como determinada função funciona. Eu acredito que os
 * beneficios de clareza e legibilidade superem o custo de overhead de uma chamada a mais de função para executar a
 * operação.
 *
 * No desenvolvimento desta parte da biblioteca, meu objetivo foi manter um equilíbrio entre o padrão minimalista de
 * nomes do PHP nativo e a ridícula verbosidade de nomes estilo Java. Abreviações, quando fazem sentido, foram
 * mantidas.
 *
 * Quando possível, a versão mb_* (multibyte) de uma função foi preferida à versão normal que só funciona com conjuntos
 * de caracteres restritos, porque suporte a Unicode deveria ser uma preocupação crucial da linguagem. Da mesma forma,
 * funções que precisam de algum tratamento no input para produzir um output adequado em Unicode já implementam estes
 * tratamentos.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor\Php
 */
class StringWrapper
{
	
	/**
	 * Parâmetro de expressão regular: múltiplas linhas.
	 * 
	 * @var string
	 */
	const REGEX_MULTILINE = 'm';
	/**
	 * Parâmetro de expressão regular: ignorar espaços em branco.
	 *
	 * @var string
	 */
	const REGEX_IGNORE_WS = 'x';
	/**
	 * Parâmetro de expressão regular: ignorar maiúsculas.
	 *
	 * @var string
	 */
	const REGEX_CASE_INSENSITIVE = 'i';
	/**
	 * Parâmetro de expressão regular: utilizar modo Posix.
	 *
	 * @var string
	 */
	const REGEX_POSIX = 'p';
	/**
	 * Parâmetro de expressão regular: utilizar modo Java.
	 *
	 * @var string
	 */
	const REGEX_MODE_JAVA = 'j';
	/**
	 * Parâmetro de expressão regular: utilizar modo GNU.
	 *
	 * @var string
	 */
	const REGEX_MODE_GNU = 'u';
	/**
	 * Parâmetro de expressão regular: utilizar modo grep.
	 *
	 * @var string
	 */
	const REGEX_MODE_GREP = 'g';
	/**
	 * Parâmetro de expressão regular: utilizar modo Emacs.
	 *
	 * @var string
	 */
	const REGEX_MODE_EMACS = 'c';
	/**
	 * Parâmetro de expressão regular: utilizar modo Ruby.
	 *
	 * @var string
	 */
	const REGEX_MODE_RUBY = 'r';
	/**
	 * Parâmetro de expressão regular: utilizar modo Perl.
	 *
	 * @var string
	 */
	const REGEX_MODE_PERL = 'z';
	/**
	 * Parâmetro de expressão regular: utilizar modo Posix Basic.
	 *
	 * @var string
	 */
	const REGEX_MODE_POSIXBASIC = 'b';
	/**
	 * Parâmetro de expressão regular: utilizar modo Posix Extended.
	 *
	 * @var string
	 */
	const REGEX_MODE_POSIXEXTENDED = 'd';
	/**
	 * Limpar string à esquerda.
	 *
	 * @var string
	 */
	const TRIM_LEFT = 'left';
	/**
	 * Limpar string à direita.
	 *
	 * @var string
	 */
	const TRIM_RIGHT = 'right';
	/**
	 * Limpar a string à esquerda e direita.
	 *
	 * @var string
	 */
	const TRIM_BOTH = 'both';
	
	/**
	 * Método construtor da classe.
	 * Define a codificação UTF-8 como padrão para as operações de regex e manipulação de strings para as funções mb_*.
	 *
	 * @access public
	 */
	public function __construct()
	{
		mb_regex_encoding('UTF-8');
		mb_internal_encoding('UTF-8');
	}
	
	/**
	 * Remove o primeiro e o último caracter de uma expressão regular.
	 * 
	 * As funções de expressão regular da biblioteca `mb_*` têm uma pegadinha no seu parâmetro $delimiter: embora seja 
	 * esperada uma expressão regular, a mesma deve ser informada sem o delimitador, ao contrário das funções padrão que
	 * trabalham com expressões regulares (por exemplo, `preg_match()`). Este método corrige este problema de 
	 * padronização e aceita a expressão regular com o delimitador.
	 * 
	 * <code>
	 * $regex = '/^(a|b)*?$/';
	 * // Output: '^(a|b)*?$'
	 * </code>
	 * 
	 * @access protected
	 * @param string $regex Expressão regular.
	 * @return string
	 */
	protected function removerDelimitadorRegex(string $regex) : string
	{
		return mb_substr($regex, 1, mb_strlen($regex) - 1);
	}
	
	/**
	 * Converte uma string representando um número binário para hexadecimal.
	 * 
	 * @access public
	 * @param string $string Texto representando um número binário (p.ex.: 010000010).
	 * @return string Representação hexadecimal do número binário.
	 */
	public function binarioParaHexadecimal(string $string) : string
	{
		return bin2hex($string);
	}
	
	/**
	 * Converte uma string representando um número hexadecimal para binário.
	 * 
	 * @access public
	 * @param string $string Texto representando um número hexadecimal (p.ex.: 1abf).
	 * @return string Representação binária do número hexadecimal.
	 */
	public function hexadecimalParaBinary(string $string) : string
	{
		return hex2bin($string);
	}
	
	/**
	 * Retorna o caracter correspondente ao valor do código ASCII informado.
	 * 
	 * @access public
	 * @param int $asciiCode Valor do código ASCII do caracter.
	 * @return string Caracter correspondente ao código.
	 */
	public function caracterAscii(int $asciiCode) : string
	{
		return chr($asciiCode);
	}
	
	/**
	 * Retorna o código ASCII de um caracter informado.
	 * 
	 * @access public
	 * @param string $char Caracter ASCII.
	 * @return int Código ASCII do caracter.
	 */
	public function asciiCaracter(string $char) : int
	{
		return ord($char);
	}
	
	/**
	 * Cria um hash de senha criptograficamente seguro. O hash gerado é automaticamente "salgado".
	 * 
	 * Este método deve ser usado preferencialmente no lugar do função `crypt`, que não salga o hash automaticamente, e 
	 * portanto pode ser utilizado incorretamente pelo desenvolvedor.
	 * 
	 * A função password_hash, apesar de relativamente recente, possui algumas inconsistências na sua declaração 
	 * (como o parâmetro opcional $salt ser marcado como deprecated no PHP 7). Este método visa tornar estas 
	 * inconsistências transparentes para o desenvolvedor.
	 * 
	 * @access public
	 * @param string $senha Senha para a qual se quer gerar o hash.
	 * @param int $custo Custo algorítmico do hash gerado. Quanto maior, melhor a força do hash, porém mais demorada é a
	 * geração do mesmo. O valor padrão 10 é considerado um bom valor base para o custo com relação ao tempo gasto.
	 * @param int $algoritmo Identificador do algoritmo usado para a geração do hash. Deve ser uma das constantes de 
	 * senha do PHP.
	 * @return string O hash da senha gerado.
	 * @throws \Numenor\Excecao\Php\StringWrapper\ExcecaoHashInvalido se o hash não pôde ser gerado.
	 */
	public function hashSenha(string $senha, int $custo = 10, int $algoritmo = \PASSWORD_DEFAULT) : string
	{
		$hash = password_hash($senha, $algoritmo, array('cost' => $custo));
		// Se o hash não pôde ser gerado, levanta uma exceção (padronização de tratamento de erro).
		if ($hash === false) {
			throw new ExcecaoString\ExcecaoHashInvalido();
		}
	}
	
	/**
	 * Separa uma string a partir de uma expressão regular delimitadora.
	 * 
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $delimitador Expressão regular delimitadora. Para fins de uniformização com outras funções de
	 * expressão regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/). 
	 * @param string $string Texto a ser separado.
	 * @param int $limite Indica o limite máximo de elementos em que o texto deve ser divido. Se não informado, o padrão
	 * é -1 (sem limite). 
	 * @return array Texto dividido. 
	 */
	public function separar(string $delimitador, string $string, int $limite = -1) : array
	{
		return mb_split($this->removerDelimitadorRegex($delimitador), $string, $limite);
	}
	
	/**
	 * Une uma lista de caracteres utilizando um caracter de união entre cada elemento.
	 * 
	 * @access public
	 * @param string $unificador Texto inserido entre cada um dos elementos da lista de caracteres.
	 * @param array $partes Lista de caracteres.
	 * @return string Texto resultante.
	 */
	public function unir(string $unificador, array $partes) : string
	{
		return implode($unificador, $partes);
	}
	
	/**
	 * Substitui todas as ocorrências de uma string por outra.
	 * 
	 * Ao contrário de várias outras funções de string da biblioteca padrão, str_replace funciona com strings multibyte;
	 * porém, a ordem dos parâmetros é diferente (o texto a ser alterado é o último parâmetro, ao invés do primeiro). 
	 * 
	 * Este método visa corrigir este problema de padronização dos parâmetros.
	 *
	 * @access public
	 * @param string $string Texto a ser alterado.
	 * @param string $busca Parte do texto a ser substituída.
	 * @param string $substituicao Texto de substituição.
	 * @return string Modificadores da expressão regular de busca.
	 */
	public function substituir(string $string, string $busca, string $substituicao) : string
	{
		return str_replace($busca, $substituicao, $string);
	}
	
	/**
	 * Busca por expressão regular dentro de um texto.
	 * 
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $string Texto onde será feita a busca.
	 * @param string $regex Expressão regular de busca. Para fins de uniformização com outras funções de expressão 
	 * regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/).
	 * @return boolean Indica se a expressão regular foi encontrada dentro do texto. 
	 */
	public function regexBusca(string $string, string $regex) : bool
	{
		return mb_ereg_match($this->removerDelimitadorRegex($regex), $string);
	}
	
	/**
	 * Substitui as ocorrências de uma string identificada por uma expressão regular por outra.
	 * 
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $string Texto a ser alterado.
	 * @param string $regex Expressão regular de busca. Para fins de uniformização com outras funções de expressão 
	 * regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/).
	 * @param string $substituicao Texto de substituição.
	 * @param string $opcoes Modificadores da expressão regular de busca.
	 * @return string Texto substituído.
	 */
	public function regexSubstituir(
		string $string,
		string $regex,
		string $substituicao,
		string $opcoes = self::REGEX_POSIX . self::REGEX_MODE_RUBY
	) : string {
		return mb_ereg_replace($this->removerDelimitadorRegex($regex), $substituicao, $string, $opcoes);
	}
	
	/**
	 * Substitui as ocorrências de uma string identificada por uma expressão regular utilizando uma função de callback.
	 * 
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $string Texto a ser alterado
	 * @param string $regex Expressão regular de busca. Para fins de uniformização com outras funções de
	 * expressão regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/).
	 * @param callable $callback Função de callback que será chamada passando um array dos elementos encontrados dentro 
	 * do texto a ser alterado.
	 * @param string $opcoes Modificadores da expressão regular de busca.
	 * @return string Texto substituído.
	 */
	public function regexSubstituirCallback(
		string $string,
		string $regex,
		callable $callback,
		string $opcoes = self::REGEX_POSIX . self::REGEX_MODE_RUB
	) : string {
		return mb_ereg_replace_callback($regex, $callback, $string, $opcoes);
	}
	
	/**
	 * Codifica todos os caracteres do texto que possuem uma representação como entidade HTML para a entidade 
	 * correspondente.
	 * 
	 * <code>
	 * $string = "A 'quote' is <b>bold</b>."
	 * // Output: "A &#039;quote&#039; is &lt;b&gt;bold&lt;/b&gt;"
	 * </code>
	 * 
	 * @access public
	 * @param string $string Texto a ser codificado.
	 * @param int $flags Máscara de bits das flags indicando como a conversão deve ser efetuada. O padrão ENT_QUOTES | 
	 * ENT_HTML5 é o mais indicado para a maioria dos casos (codifica todos os caracteres, incluindo aspas, e trata o 
	 * código como HTML 5).
	 * @param string $encoding Codificação do texto usada para fazer a conversão dos caracteres. Dependendo da versão do
	 * PHP, o valor padrão deste parâmetro é completamente diferente; por isso, foi definido o padrão como UTF-8.
	 * @param boolean $doubleEncode Indica se entidades HTML pré-existentes no texto devem ser codificadas ou não.
	 * @return string Texto com os caracteres convertidos para suas representações como entidade HTML.
	 */
	public function codificarHtmlEntities(
		string $string,
		int $flags = \ENT_QUOTES | \ENT_HTML5,
		string $encoding = 'UTF-8',
		bool $doubleEncode = true
	) : string {
		return htmlentities($string, $flags, $encoding, $doubleEncode);
	}
	
	/**
	 * Decodifica as representações de entidade HTML presentes no texto para o caracter correspondente.
	 * 
	 * <code>
	 * $string = 'I'll &quot;walk&quot; the &lt;b&gt;dog&lt;/b&gt; now'
	 * // Output: 'I'll "walk" the <b>dog</b> now'
	 * </code>
	 * 
	 * @access public
	 * @param string $string Texto a ser codificado.
	 * @param int $flags Máscara de bits das flags indicando como a conversão deve ser efetuada. O padrão ENT_QUOTES | 
	 * ENT_HTML5 é o mais indicado para a maioria dos casos (codifica todos os caracteres, incluindo aspas, e trata o 
	 * código como HTML 5).
	 * @param string $codificacao Codificação do texto usada para fazer a conversão dos caracteres. Dependendo da versão
	 * do PHP, o valor padrão deste parâmetro é completamente diferente; por isso, foi definido o padrão como UTF-8.
	 * @return string Texto com os caracteres representados como entidades HTML convertidos para os próprios caracteres. 
	 */
	public function decodificarHtmlEntities(
		string $string,
		int $flags = ENT_COMPAT | ENT_HTML5,
		string $codificacao = 'UTF-8'
	) : string {
		return html_entity_decode($string, $flags, $codificacao);
	}
	
	/**
	 * Codifica uma string em MIME base64.
	 * 
	 * @access public
	 * @param string $string Texto a ser codificado.
	 * @return string O texto codificado em base64.
	 */
	public function codificarBase64(string $string) : string
	{
		return base64_encode($string);
	}
	
	/**
	 * Decodifica uma string MIME base64.
	 * 
	 * @access public
	 * @param string $string Texto a ser decodificado.
	 * @return string O texto decodificado.
	 * @throws \Numenor\Excecao\Php\StringWrapper\ExcecaoDecodificacaoInvalida se ocorrer um erro na decodificação do
	 * texto.
	 */
	public function decodificarBase64(string $string) : string
	{
		// Substitui qualquer espaço em branco pelo caracter +, para evitar que codificações geradas por algumas
		// bibliotecas sejam consideradas inválidas (p.ex., a codificação gerada pelo método Javascript 
		// canvas.toUrlData() 
		$retorno = base64_decode(str_replace(' ', '+', $string));
		if (!$retorno) {
			throw new ExcecaoString\ExcecaoDecodificacaoInvalida();
		}
		return $retorno;
	}
	
	/**
	 * Remove caracteres do início e/ou do final da string.
	 *
	 * @access public
	 * @param string $string Texto a ser tratado.
	 * @param string $onde Indica onde deve ocorrer a remoção dos caracteres (esquerda/início, direita/fim, ou ambos).
	 * @param string $caracteres Lista de caracteres que devem ser removidos.
	 * @return string Texto com os caracteres informados removidos do início e/ou do final.
	 */
	public function trim(string $string, string $onde = self::TRIM_BOTH, string $caracteres = '') : string
	{
		switch ($onde) {
			case self::TRIM_LEFT:
				$return = ltrim($string, $caracteres);
				break;
			case self::TRIM_RIGHT:
				$return = rtrim($string, $caracteres);
				break;
			case self::TRIM_BOTH:
			default:
				$return = trim($string, $caracteres);
				break; 
		}
		return $return;
	}
	
	/**
	 * Reduz um texto para o tamanho informado, opcionalmente posposto por um texto de marcação.
	 * O texto reduzido é tratado de forma que ele não termine em espaço em branco, mesmo que isto torne-o menor do que o
	 * tamanho desejado.
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $string Texto a ser reduzido.
	 * @param int $inicio Posição do texto onde deve começar a redução. 
	 * @param int $largura Largura máxima do texto reduzido.
	 * @param string $marcador Texto de marcação a ser inserido após a redução do texto.
	 * @return string Texto reduzido.
	 */
	public function reduzir(string $string, int $inicio, int $largura, string $marcador = '') : string
	{
		return $this->trim(mb_strimwidth($string, $inicio, $largura)) . $marcador;
	}
	
	/**
	 * Converte o primeiro caracter do texto informado para minúscula.
	 * 
	 * Ao contrário da função lcfirst(), este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $string Texto a ser tratado.
	 * @return string Texto com o primeiro caracter convertido para minúscula.
	 */
	public function primeiraLetraMinuscula(string $string) : string
	{
		$primeiroCaracter = mb_substr($string, 0, 1);
		$primeiroCaracter = $this->converterMinusculas($primeiroCaracter);
		return $primeiroCaracter . mb_substr($string, 1);
	}
	
	/**
	 * Converte o primeiro caracter do texto informado para maiúscula.
	 * 
	 * Ao contrário da função ucfirst, este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $string Texto a ser tratado.
	 * @return string Texto com o primeiro caracter convertido para minúscula.
	 */
	public function primeiraLetraMaiuscula(string $string) : string
	{
		$primeiroCaracter = mb_substr($string, 0, 1);
		$primeiroCaracter = $this->converterMaiusculas($primeiroCaracter);
		return $primeiroCaracter . mb_substr($string, 1);
	}
	
	/**
	 * Converte o texto inteiro para minúsculas.
	 * 
	 * @access public
	 * @param string $string Texto a ser tratado.
	 * @return string Texto convertido para minúsculas.
	 */
	public function converterMinusculas(string $string) : string
	{
		return mb_strtolower($string);
	}
	
	/**
	 * Converte o texto inteiro para maiúsculas.
	 *
	 * @access public
	 * @param string $string Texto a ser tratado.
	 * @return string Texto convertido para maiúsculas.
	 */
	public function converterMaiusculas(string $string) : string
	{
		return mb_strtoupper($string);
	}
	
	/**
	 * Transforma o texto convertendo a primeira letra de cada palavra que o compõe para maiúscula, e o restante para
	 * minúscula.
	 * 
	 * <code>
	 * $string = 'mary had a Little lamb and she loved it so'
	 * // Output: 'Mary Had A Little Lamb And She Loved It So'
	 * </code>
	 *
	 * @access public
	 * @param string $string Texto a ser tratado.
	 * @return string Texto convertido para maiúsculas.
	 */
	public function converterPalavrasMaiusculas(string $string) : string
	{
		return mb_convert_case($string, \MB_CASE_TITLE);
	}
	
	/**
	 * Introduz um caracter de quebra na string a cada N caracteres.
	 * Ao contrário da função wordwrap(), este método funciona com caracteres multibyte. Entretanto, ele não é otimizado, sendo
	 * desaconselhável utilizá-lo em laços de repetição.
	 * Implementação adaptada da solução criada pelo usuário Fosfor, em: 
	 * http://stackoverflow.com/questions/3825226/multi-byte-safe-wordwrap-function-for-utf-8
	 * 
	 * @access public
	 * @param string $string Texto a ser dividido.
	 * @param int $largura Largura máxima do texto antes da introdução do caracter de quebra. 
	 * @param string $quebra Caracter de quebra.
	 * @param boolean $cortar Indica se a quebra pode ser inserida no meio de uma palavra. Caso este parâmetro seja false, a quebra é feita
	 * sempre após uma palavra, mesmo que isso faça a largura daquele trecho do texto ser menor que o tamanho máximo. 
	 * @return string Texto dividido.
	 */
	public function quebrarLinha(string $string, int $largura = 75, string $quebra = "\n", bool $cortar = false) : string
	{
		$lines = explode($quebra, $string);
		foreach ($lines as &$line) {
			$line = $this->trim($line, self::TRIM_RIGHT);
			if (mb_strlen($line) <= $largura) {
				continue;
			}
			$words = explode(' ', $line);
			$line = '';
			$actual = '';
			foreach ($words as $word) {
				if (mb_strlen($actual . $word) <= $largura) {
					$actual .= $word . ' ';
				} else {
					if ($actual != '') {
						$line .= $this->trim($actual, self::TRIM_RIGHT) . $quebra;
					}
					$actual = $word;
					if ($cortar) {
						while (mb_strlen($actual) > $largura) {
							$line .= mb_substr($actual, 0, $largura) . $quebra;
							$actual = mb_substr($actual, $largura);
						}
					}
					$actual .= ' ';
				}
			}
			$line .= $this->trim($actual);
		}
		return implode($break, $lines);
	}
}

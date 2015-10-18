<?php
/**
 * Wrapper para as funções de string da biblioteca padrão do PHP.
 * Na minha opinião (e na de muitos desenvolvedores, para falar a verdade), um dos piores aspectos do
 * desenvolvimento em PHP é a nomenclatura das funções da biblioteca padrão. Muitas funções recebem nomes
 * absolutamente idiotas por razões históricas (basicamente, "o nome da função C que executa isso é esse,
 * vamos usar o mesmo nome"), o que acabou não só trazendo antigos hábitos de nomenclatura para dentro de
 * uma linguagem que se pretende moderna, como ainda introduziu alguns novos maus hábitos de nomenclatura
 * novos. E isso sem falar em problemas com ordem de parâmetros inconsistente, comportamentos inconsistentes
 * entre funções que deveriam ser parecidas, etc.
 * O caso das funções de string é bastante emblemático, pois há exemplos de praticamente todos esses problemas.
 * O site PHP Sadness (http://phpsadness.com) faz um bom trabalho de listar os casos, mas basta olhar para 
 * coisas como nl2br, parse_str e explode e notar que não há padrão algum na forma como essas funções são nomeadas,
 * ou seus parâmetros ordenados em comparação com outras funções semelhantes.
 * Esta classe visa prover uma interface normalizada e uniforme para estas funções de string, de modo que o
 * desenvolvedor possa facilmente autocompletá-las utilizando uma IDE minimamente decente sem ter que ficar
 * lembrando ou consultando a documentação do PHP para lembrar exatamente como determinada função funciona. Eu
 * acredito que os beneficios de clareza e legibilidade superem o custo de overhead de uma chamada a mais de
 * função para executar a operação.
 * No desenvolvimento desta parte da biblioteca, meu objetivo foi manter um equilíbrio entre o padrão minimalista de
 * nomes do PHP nativo e a ridícula verbosidade de nomes estilo Java. Abreviações, quando fazem sentido, foram 
 * mantidas.
 * Quando possível, a versão mb_* (multibyte) de uma função foi preferida à versão normal que só funciona com conjuntos
 * de caracteres restritos, porque suporte a Unicode deveria ser uma preocupação crucial da linguagem. Da mesma forma,
 * funções que precisam de algum tratamento no input para produzir um output adequado em Unicode já implementam estes
 * tratamentos.
 * A classe é definida como abstrata para evitar a instanciação (todos os métodos são estáticos, portanto não há necessidade
 * de instanciar esta classe)
 * 
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Php
 * @abstract
 */
namespace Numenor\Php;
use Numenor\Excecao\Php\StringWrapper as ExcecaoString;
abstract class StringWrapper {
	
	const REGEX_MULTILINE = 'm';
	const REGEX_IGNORE_WS = 'x';
	const REGEX_CASE_INSENSITIVE = 'i';
	const REGEX_POSIX = 'p';
	const REGEX_MODE_JAVA = 'j';
	const REGEX_MODE_GNU = 'u';
	const REGEX_MODE_GREP = 'g';
	const REGEX_MODE_EMACS = 'c';
	const REGEX_MODE_RUBY = 'r';
	const REGEX_MODE_PERL = 'z';
	const REGEX_MODE_POSIXBASIC = 'b';
	const REGEX_MODE_POSIXEXTENDED = 'd';
	const TRIM_LEFT = 'left';
	const TRIM_RIGHT = 'right';
	const TRIM_BOTH = 'both';
	
	/**
	 * Remove o primeiro e o último caracter de uma expressão regular.
	 * As funções de expressão regular da biblioteca `mb_*` têm uma pegadinha no seu parâmetro $delimiter: 
	 * embora seja esperada uma expressão regular, a mesma deve ser informada sem o delimitador, ao contrário das
	 * funções padrão que trabalham com expressões regulares (por exemplo, `preg_match()`). Este método corrige este 
	 * problema de padronização e aceita a expressão regular com o delimitador.
	 * 
	 * <code>
	 * $regex = '/^(a|b)*?$/';
	 * // Output: '^(a|b)*?$'
	 * 
	 * @access protected
	 * @static
	 * @param string $regex
	 * @return string
	 */
	protected static function removerDelimitadorRegex($regex) {
		return mb_substr($regex, 1, mb_strlen($regex) - 1);
	}
	
	/**
	 * Método de inicialização da classe.
	 * Define a codificação UTF-8 como padrão para as operações de regex e manipulação de strings para as funções mb_*.
	 *   
	 * @access public
	 * @static
	 */
	public static function init() {
		mb_regex_encoding('UTF-8');
		mb_internal_encoding('UTF-8');
	}
	
	/**
	 * Converte uma string representando um número binário para hexadecimal.
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto representando um número binário (p.ex.: 010000010).
	 * @return string Representação hexadecimal do número binário.
	 */
	public static function binaryToHexadecimal($string) {
		return bin2hex($string);
	}
	
	/**
	 * Converte uma string representando um número hexadecimal para binário.
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto representando um número hexadecimal (p.ex.: 1abf)
	 * @return string Representação binária do número hexadecimal.
	 */
	public static function hexadecimalToBinary($string) {
		return hex2bin($string);
	}
	
	/**
	 * Retorna o caracter correspondente ao valor do código ASCII informado.
	 * 
	 * @access public
	 * @static
	 * @param int $asciiCode Valor do código ASCII do caracter.
	 * @return string Caracter correspondente ao código.
	 */
	public static function charFromAscii($asciiCode) {
		return chr($asciiCode);
	}
	
	/**
	 * Retorna o código ASCII de um caracter informado.
	 * 
	 * @access public
	 * @static
	 * @param string $char Caracter ASCII.
	 * @return int Código ASCII do caracter.
	 */
	public static function asciiFromChar($char) {
		return ord($char);
	}
	
	/**
	 * Cria um hash de senha criptograficamente seguro. O hash gerado é automaticamente "salgado".
	 * Este método deve ser usado preferencialmente no lugar do função `crypt`, que não salga o hash
	 * automaticamente, e portanto pode ser utilizado incorretamente pelo desenvolvedor.
	 * A função password_hash, apesar de relativamente recente, possui algumas inconsistências na 
	 * sua declaração (como o parâmetro opcional $salt ser marcado como deprecated no PHP 7). Este método
	 * visa tornar estas inconsistências transparentes para o desenvolvedor.
	 * 
	 * @access public
	 * @static
	 * @param string $password Senha para a qual se quer gerar o hash.
	 * @param int $cost Custo algorítmico do hash gerado. Quanto maior, melhor a força do hash, porém mais
	 * demorada é a geração do mesmo. O valor padrão 10 é considerado um bom valor base para o custo com
	 * relação ao tempo gasto.
	 * @param string $algorithm Identificador do algoritmo usado para a geração do hash. Deve ser uma das
	 * constantes de senha do PHP.
	 * @return string O hash da senha gerado.
	 * @throws \Numenor\Excecao\Php\StringWrapper\ExcecaoHashInvalido se o hash não pôde ser gerado.
	 */
	public static function passwordHash($password, $cost = 10, $algorithm = PASSWORD_DEFAULT) {
		$hash = password_hash($password, $algorithm, array('cost' => $cost));
		// Se o hash não pôde ser gerado, levanta uma exceção (padronização de tratamento de erro).
		if ($hash === false) {
			throw new ExcecaoString\ExcecaoHashInvalido();
		}
	}
	
	/**
	 * Separa uma string a partir de uma expressão regular delimitadora.
	 * 
	 * @access public
	 * @static
	 * @param string $delimiter Expressão regular delimitadora. Para fins de uniformização com outras funções de
	 * expressão regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/). 
	 * @param string $string Texto a ser separado.
	 * @param int $limit Indica o limite máximo de elementos em que o texto deve ser divido. Se não informado, o padrão é
	 * -1 (sem limite). 
	 * @return array Texto dividido. 
	 */
	public static function split($delimiter, $string, $limit = -1) {
		return mb_split(static::removerDelimitadorRegex($delimiter), $string, $limit);
	}
	
	/**
	 * Une uma lista de caracteres utilizando um caracter de união entre cada elemento.
	 * 
	 * @access public
	 * @static
	 * @param string $glue Texto inserido entre cada um dos elementos da lista de caracteres.
	 * @param array $pieces Lista de caracteres.
	 * @return string Texto resultante.
	 */
	public static function join($glue, array $pieces) {
		return implode($glue, $pieces);
	}
	
	/**
	 * Busca por expressão regular dentro de um texto.
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @param string $string Texto onde será feita a busca.
	 * @param string $pattern Expressão regular de busca. Para fins de uniformização com outras funções de
	 * expressão regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/).
	 * @return bool
	 */
	public static function regexMatch($string, $pattern) {
		return mb_ereg_match(static::removerDelimitadorRegex($pattern), $string);
	}
	
	/**
	 * Substitui as ocorrências de uma string identificada por uma expressão regular por outra.
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto a ser alterado.
	 * @param string $pattern Expressão regular de busca. Para fins de uniformização com outras funções de
	 * expressão regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/).
	 * @param string $replacement Texto de substituição.
	 * @param string $option Modificadores da expressão regular de busca.
	 * @return string Texto substituído.
	 */
	public static function regexReplace($string, $pattern, $replacement, $option = self::REGEX_POSIX . self::REGEX_MODE_RUBY) {
		return mb_ereg_replace($pattern, $replacement, $string, $option);
	}
	
	/**
	 * Substitui as ocorrências de uma string identificada por uma expressão regular utilizando uma função de callback.
	 * Este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @static 
	 * @param string $string Texto a ser alterado
	 * @param string $pattern Expressão regular de busca. Para fins de uniformização com outras funções de
	 * expressão regular, a expressão deve ser passada com o delimitador (normalmente, /<regex>/).
	 * @param callable $callback Função de callback que será chamada passando um array dos elementos encontrados dentro do texto a 
	 * ser alterado.
	 * @param string $option Modificadores da expressão regular de busca.
	 * @return string Texto substituído.
	 */
	public static function regexReplaceCallback($string, $pattern, callable $callback, $option = self::REGEX_POSIX . self::REGEX_MODE_RUBY) {
		return mb_ereg_replace_callback($pattern, $callback, $string, $option);
	}
	
	/**
	 * Substitui todas as ocorrências de uma string por outra.
	 * Ao contrário de várias outras funções de string da biblioteca padrão, str_replace funciona com strings multibyte;
	 * porém, a ordem dos parâmetros é diferente (o texto a ser alterado é o último parâmetro, ao invés do primeiro). Este
	 * método visa corrigir este problema de padronização dos parâmetros.
	 *
	 * @access public
	 * @static
	 * @param string $string Texto a ser alterado.
	 * @param string $needle Parte do texto a ser substituída.
	 * @param string $replace Texto de substituição.
	 * @return string Modificadores da expressão regular de busca.
	 */
	public static function replace($string, $needle, $replace) {
		return str_replace($needle, $replace, $string);
	}
	
	/**
	 * Codifica todos os caracteres do texto que possuem uma representação como entidade HTML para a entidade correspondente.
	 * 
	 * <code>
	 * $string = "A 'quote' is <b>bold</b>."
	 * // Output: "A &#039;quote&#039; is &lt;b&gt;bold&lt;/b&gt;"
	 * </code>
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto a ser codificado.
	 * @param int $flags Máscara de bits das flags indicando como a conversão deve ser efetuada. O padrão ENT_QUOTES | ENT_HTML5
	 * é o mais indicado para a maioria dos casos (codifica todos os caracteres, incluindo aspas, e trata o código como HTML 5).
	 * @param string $encoding Codificação do texto usada para fazer a conversão dos caracteres. Dependendo da versão do PHP, o valor
	 * padrão deste parâmetro é completamente diferente; por isso, foi definido o padrão como UTF-8.
	 * @param boolean $doubleEncode Indica se entidades HTML pré-existentes no texto devem ser codificadas ou não.
	 * @return string Texto com os caracteres convertidos para suas representações como entidade HTML.
	 */
	public static function htmlEntitiesEncode($string, $flags = ENT_QUOTES | ENT_HTML5, $encoding = 'UTF-8', $doubleEncode = true) {
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
	 * @static
	 * @param string $string Texto a ser codificado.
	 * @param int $flags Máscara de bits das flags indicando como a conversão deve ser efetuada. O padrão ENT_QUOTES | ENT_HTML5
	 * é o mais indicado para a maioria dos casos (codifica todos os caracteres, incluindo aspas, e trata o código como HTML 5).
	 * @param string $encoding Codificação do texto usada para fazer a conversão dos caracteres. Dependendo da versão do PHP, o valor
	 * padrão deste parâmetro é completamente diferente; por isso, foi definido o padrão como UTF-8.
	 * @return string Texto com os caracteres representados como entidades HTML convertidos para os próprios caracteres. 
	 */
	public static function htmlEntitiesDecode($string, $flags = ENT_COMPAT | ENT_HTML5, $encoding = 'UTF-8') {
		return html_entity_decode($string, $flags, $encoding);
	}
	
	/**
	 * Remove caracteres do início e/ou do final da string.
	 *
	 * @access public
	 * @static
	 * @param string $string Texto a ser tratado.
	 * @param string $where Indica onde deve ocorrer a remoção dos caracteres (esquerda/início, direita/fim, ou ambos).
	 * @param string $characterMask Lista de caracteres que devem ser removidos.
	 * @return string Texto com os caracteres informados removidos do início e/ou do final.
	 */
	public static function trim($string, $where = self::TRIM_BOTH, $characterMask = null) {
		switch ($where) {
			case self::TRIM_LEFT:
				$return = ltrim($string, $characterMask);
				break;
			case self::TRIM_RIGHT:
				$return = rtrim($string, $characterMask);
				break;
			case self::TRIM_BOTH:
			default:
				$return = trim($string, $characterMask);
				break; 
		}
		return $return;
	}
	
	/**
	 * Reduz um texto para o tamanho informado, opcionalmente posposto por um texto de marcação.
	 * O texto reduzido é tratado de forma que ele não termine em espaço em branco, mesmo que isto torne-o menor do que o
	 * tamanho desejado.
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto a ser reduzido.
	 * @param int $start Posição do texto onde deve começar a redução. 
	 * @param int $width Largura máxima do texto reduzido.
	 * @param string $marker Texto de marcação a ser inserido após a redução do texto.
	 * @return string Texto reduzido.
	 */
	public static function truncate($string, $start, $width, $marker = null) {
		return self::trim(mb_strimwidth($string, $start, $width)) . $marker;
	}
	
	/**
	 * Converte o primeiro caracter do texto informado para minúscula.
	 * Ao contrário da função lcfirst(), este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto a ser tratado.
	 * @return string Texto com o primeiro caracter convertido para minúscula.
	 */
	public static function lowerCaseFirst($string) {
		$primeiroCaracter = mb_substr($string, 0, 1);
		$primeiroCaracter = self::lowerCase($primeiroCaracter);
		return $primeiroCaracter . mb_substr($string, 1);
	}
	
	/**
	 * Converte o primeiro caracter do texto informado para maiúscula.
	 * Ao contrário da função ucfirst, este método funciona com strings multibyte.
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto a ser tratado.
	 * @return string Texto com o primeiro caracter convertido para minúscula.
	 */
	public static function upperCaseFirst($string) {
		$primeiroCaracter = mb_substr($string, 0, 1);
		$primeiroCaracter = self::upperCase($primeiroCaracter);
		return $primeiroCaracter . mb_substr($string, 1);
	}
	
	/**
	 * Converte o texto inteiro para minúsculas.
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto a ser tratado.
	 * @return string Texto convertido para minúsculas.
	 */
	public static function lowerCase($string) {
		return mb_strtolower($string);
	}
	
	/**
	 * Converte o texto inteiro para maiúsculas.
	 *
	 * @access public
	 * @static
	 * @param string $string Texto a ser tratado.
	 * @return string Texto convertido para maiúsculas.
	 */
	public static function upperCase($string) {
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
	 * @static
	 * @param string $string Texto a ser tratado.
	 * @return string Texto convertido para maiúsculas.
	 */
	public static function titleCase($string) {
		return mb_convert_case($string, MB_CASE_TITLE);
	}
	
	/**
	 * Introduz um caracter de quebra na string a cada N caracteres.
	 * Ao contrário da função wordwrap(), este método funciona com caracteres multibyte. Entretanto, ele não é otimizado, sendo
	 * desaconselhável utilizá-lo em laços de repetição.
	 * Implementação adaptada da solução criada pelo usuário Fosfor, em: 
	 * http://stackoverflow.com/questions/3825226/multi-byte-safe-wordwrap-function-for-utf-8
	 * 
	 * @access public
	 * @static
	 * @param string $string Texto a ser dividido.
	 * @param int $width Largura máxima do texto antes da introdução do caracter de quebra. 
	 * @param string $break Caracter de quebra.
	 * @param boolean $cut Indica se a quebra pode ser inserida no meio de uma palavra. Caso este parâmetro seja false, a quebra é feita
	 * sempre após uma palavra, mesmo que isso faça a largura daquele trecho do texto ser menor que o tamanho máximo. 
	 * @return string Texto dividido.
	 */
	public static function wordWrap($string, $width = 75, $break = "\n", $cut = false) {
		$lines = explode($break, $string);
		foreach ($lines as &$line) {
			$line = self::trim($line, self::TRIM_RIGHT);
			if (mb_strlen($line) <= $width) {
				continue;
			}
			$words = explode(' ', $line);
			$line = '';
			$actual = '';
			foreach ($words as $word) {
				if (mb_strlen($actual . $word) <= $width) {
					$actual .= $word . ' ';
				} else {
					if ($actual != '') {
						$line .= self::trim($actual, self::TRIM_RIGHT) . $break;
					}
					$actual = $word;
					if ($cut) {
						while (mb_strlen($actual) > $width) {
							$line .= mb_substr($actual, 0, $width) . $break;
							$actual = mb_substr($actual, $width);
						}
					}
					$actual .= ' ';
				}
			}
			$line .= self::trim($actual);
		}
		return self::join($break, $lines);
	}
}
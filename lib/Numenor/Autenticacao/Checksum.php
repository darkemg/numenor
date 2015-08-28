<?php
/**
 * Classe para geração de checksums para valores arbitrários.
 * 
 * Um checksum ("soma de verificação") é um código gerado para testar a integridade dos dados
 * transmitidos por um canal qualquer de comunicação.
 * 
 * Na web, é especialmente importante que valores enviados e recebidos através do browser
 * pelos métodos GET, POST, COOKIE, etc. sejam corretamente validados pelo servidor antes de
 * serem operados, tendo em vista que os mesmos podem facilmente ser manipulados e informações
 * vazadas para terceiros que não deveriam ter acesso a essas informações (por exemplo, um
 * usuário ser capaz de visualizar os dados relativos a qualquer usuário simplesmente modificando
 * o parâmetro userId enviado via GET).
 * 
 * O valor de checksum gerado por esta classe NÃO é criptograficamente seguro, uma vez que a 
 * função password_hash() do PHP truca os valores de $password maiores do que 72 caracteres
 * (tornando impossível o uso deste método de geração de hash para valores arbitrários). No
 * entanto, ele é adequado para a assinatura de parâmetros enviados e recebidos em uma aplicação
 * normal; o salt para o hash é gerado através da função openssl_random_pseudo_bytes(), que é 
 * menos previsível do que uniqid(), rand(), microtime() e outros métodos usuais de geração de
 * salt.
 *  
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Autenticacao
 */
namespace Numenor\Autenticacao;
use Numenor\Excecao\ExcecaoAlgoritmoHashInvalido;
use Numenor\Excecao\ExcecaoChecksumChaveInvalida;
use Numenor\Excecao\ExcecaoChecksumSemChave;

class Checksum {
	
	const SEPARADOR_SALT_HASH = ':';
	
	/**
	 * Chave padrão utilizada na aplicação para geração e validação dos checksums.
	 * 
	 * Este valor pode ser sobrescrito individualmente em cada instância da classe, mas permite que a
	 * mesma chave seja utilizada em toda a aplicação, caso isso seja mais conveniente.
	 *
	 * @access protected
	 * @var string
	 */
	protected static $chavePadrao;
	/**
	 * Identificador do algoritmo de hash utilizado para gerar os checksums da classe. Deve ser um dos
	 * valores aceitos pela função hash(), e retornados pela função hash_algos().
	 *  
	 * @access protected
	 * @var string
	 */
	protected $algoritmo;
	/**
	 * Tamanho do salt gerado para o hash; deve ser um número inteiro positivo. Quanto maior o número,
	 * mais forte será o salt.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $tamanhoSalt;
	/**
	 * Indica se o salt gerado pela função openssl_random_pseudo_bytes() deve ser criptograficamente
	 * seguro.
	 * 
	 * @access protected
	 * @var boolean
	 */
	protected $cryptoStrong;
	/**
	 * Chave utilizada juntamente com o salt para gerar e validar o checksum gerado.
	 * 
	 * Esta chave deve ser a mesma tanto para a geração quanto para a verificação do
	 * checksum.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $chave;
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param string $chave Chave utilizada na geração do checksum. Caso não seja informado, a chave
	 * padrão definida será utilizada.
	 * @param number $algoritmo Identificador do algoritmo usado para gerar o checksum.
	 * @throws \Numenor\Excecao\ExcecaoAlgoritmoHashInvalido se o algoritmo de hash 
	 * informado é inválido ou não está instalado no servidor.
	 */
	public function __construct($chave = null, $algoritmo = 'sha512') {
		// Determina se o algortimo informado está disponível
		$listaAlgoritmos = hash_algos();
		if (array_search($algoritmo, $listaAlgoritmos) === false) {
			throw new ExcecaoAlgoritmoHashInvalido();
		}
		$this->chave = !empty($chave)
				? $chave
				: static::$chavePadrao;
		$this->algoritmo = $algoritmo;
		$this->tamanhoSalt = 24;
		$this->cryptoStrong = true;
	}
	
	/**
	 * Define a chave utilizada na geração do checksum.
	 * 
	 * @access public
	 * @static
	 * @param string $chave Chave definida para utilização na geração dos checksums. Note
	 * que é possível alterar a chave uma vez definida (para permitir que chaves diferentes
	 * sejam utilizadas na geração de checksums diferentes), mas não é possível definir uma
	 * chave vazia.
	 * @throws \Numenor\Excecao\ExcecaoChecksumChaveInvalida se a chave informada é nula.
	 */
	public static function setChavePadrao($chave) {
		if (!$chave) {
			throw new ExcecaoChecksumChaveInvalida();
		}
		static::$chavePadrao = $chave;
	}

	/**
	 * Gera um checksum para o valor informado, opcionalmente encodado em base64 para 
	 * transmissão via canais que aceitem apenas texto ASCII.
	 * 
	 * @access public
	 * @param mixed $valor Valor para o qual o checksum deve ser gerado.
	 * @param boolean $encoded Indica se o checksum gerado deve ser encodado com base64.
	 * @return string O checksum gerado, no formato salt + separador + hash.
	 * @throws \Numenor\Excecao\ExcecaoChecksumSemChave se a chave definida para utilização
	 * na geração de checksums não foi definida.
	 */
	public function gerarChecksum($valor, $encoded = false) {
		if (!$this->chave) {
			throw new ExcecaoChecksumSemChave();
		}
		$salt = bin2hex(openssl_random_pseudo_bytes($this->tamanhoSalt, $this->cryptoStrong));
		$hash = $salt . static::SEPARADOR_SALT_HASH . hash($this->algoritmo, $salt . $this->chave . $valor);
		return $encoded
				? base64_encode($hash)
				: $hash;
	}
	
	/**
	 * Gera um checksum para a lista de valores informados (como uma coleção de pares nome => valor),
	 * opcionalmente encodados em base64 para transmissão via canais que aceitem apenas texto ASCII.
	 *
	 * @access public
	 * @param \stdClass $listaValores Lista de valores para os quais os checksums devem ser
	 * gerados.
	 * @param boolean $encoded Indica se os checksums gerados devem ser encodados com base64.
	 * @return \stdClass Uma cópia da lista de valores originais, acrescentada dos checksums
	 * (identificados como atributos nome + 'Checksum').
	 */
	public function gerarChecksumLista(\stdClass $listaValores, $encoded = false) {
		$checksums = new \stdClass();
		foreach ($listaValores as $nome => $valor) {
			$checksums->{$nome . 'Checksum'} = $this->gerarChecksum($valor, $encoded);
		}
		$lista = (object) array_merge((array) $listaValores, (array) $checksums);
		return $lista;
	}
	
	/**
	 * Valida um valor contra o checksum informado para o mesmo.
	 * 
	 * @access public
	 * @param mixed $valor Valor a ser conferido.
	 * @param string $checksum Checksum informado para o valor.
	 * @return boolean O checksum é válido para o valor informado?
	 * @throws \Numenor\Excecao\ExcecaoChecksumSemChave se a chave definida para utilização
	 * na geração de checksums não foi definida.
	 */
	public function validarChecksum($valor, $checksum) {
		if (!$this->chave) {
			throw new ExcecaoChecksumSemChave();
		}
		// array resultante: 
		// [0] = salt
		// [1] = hash
		$array = explode(static::SEPARADOR_SALT_HASH, $checksum);
		$hash = hash($this->algoritmo, $array[0] . $this->chave . $valor);
		return $hash === $array[1]; 
	}
}
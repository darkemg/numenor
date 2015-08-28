<?php
/**
 * Cronômetro para controle do tempo de execução de uma aplicação PHP.
 * Permite a criação de um ponto inicial e parciais em determinados pontos de uma
 * aplicação, indicando quanto tempo o script levou para ser executado até aquele
 * ponto.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Debug
 */
namespace Numenor\Debug;

class CronometroExecucao {
	
	/**
	 * Identificador do cronômetro.
	 * Normalmente utilizado caso o cronômetro seja salvo em banco de dados.
	 * @access private
	 * @var int
	 */
	private $id;
	/**
	 * Nome do cronômetro.
	 * Permite ao desenvolvedor identificar facilmente o cronômetro, caso mais de
	 * um deles seja iniciado em diferentes pontos da aplicação.
	 * @access private
	 * @var string
	 */
	private $nome;
	/**
	 * Tempo de início do cronômetro.
	 * @access private
	 * @var float
	 */
	private $inicio;
	/**
	 * Lista de parciais definidas para o cronômetro.
	 * @access private
	 * @var \stdClass[]
	 */
	private $parciais = array();
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param int $id Identificador do cronômetro.
	 * @param string $nome Nome do cronômetro.
	 */
	public function __construct($id, $nome) {
		$this->id = $id;
		$this->nome = $nome;
	}
	
	/**
	 * Inicia a contagem de tempo do cronômetro.
	 * 
	 * @access public
	 * @param string $descricao Descrição do momento de início do cronômetro, 
	 * para referência
	 */
	public function iniciar($descricao = '') {
		$this->inicio = (object) array(
				'tempo' => microtime(true),
				'descricao' => $descricao);
	}
	
	/**
	 * Define uma parcial do cronômetro, contabilizando o tempo decorrido desde
	 * o momento em que o cronômetro foi iniciado.
	 * 
	 * @access public
	 * @param string $descricao Descrição da parcial do cronômetro, para 
	 * referência
	 */
	public function definirParcial($descricao = '') {
		$this->parciais[] = (object) array(
				'tempo' => microtime(true) - $this->inicio->tempo,
				'descricao' => $descricao);
	}
}
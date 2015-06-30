<?php

namespace Numenor\Debug;

class CronometroExecucao {
	
	private $id;
	private $nome;
	private $inicio;
	private $parciais = array();
	
	public function __construct($id, $nome) {
		$this->id = $id;
		$this->nome = $nome;
	}
	
	public function iniciar($descricao = '') {
		$this->inicio = (object) array(
				'tempo' => microtime(true),
				'descricao' => $descricao);
	}
	
	public function definirParcial($descricao = '') {
		$this->parciais[] = (object) array(
				'tempo' => microtime(true) - $this->inicio->tempo,
				'descricao' => $descricao);
	}
}
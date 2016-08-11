<?php
/**
 * Exceção levantada pelo sistema quando o arquivo de configuração da biblioteca informado não existe, não
 * possui permissão de leitura ou não estpa em um dos formatos aceitos pelo leitor de arquivos de configuração.
 *
 * @author Darke M. Goulart <darkemg@users.noreply.github.com>
 * @package Numenor/Excecao
 */
namespace Numenor\Excecao;

class ExcecaoArquivoConfiguracaoInvalido extends ExcecaoErroUso
{
	
	/**
	 * Método construtor da classe
	 * 
	 * @access public
	 * @param \Exception $previous Exceção anterior (se a exceção atual tiver sido encadeada)
	 */
	public function __construct(\Exception $previous = null)
	{
		parent::__construct(
			'O arquivo de configuração informado não pode ser lido. Ele pode não existir, não estar acessível, ou não estar em um formato válido.',
			static::DEFAULT_CODE,
			$previous
		);
	}
}
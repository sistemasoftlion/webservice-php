<?php 

//Dados fornecidos pela transportadora (em caso de dúvida leia o arquivo READEME.md)
$email = ''; 
$senha = '';
$token = ''; 

//Dados de Conexão
$options = array('location' 	        => 'http://webservice.softlion.com.br/server', 
                 'uri' 			=> 'http://webservice.softlion.com.br/soap/server/', 
		 'encoding'		=> 'ISO-8859-1', 
		 'trace' 		=> true,
		 'exceptions' 		=> true,
		 'compression' 		=> SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
		 'connection_timeout'	=> 1000); 

//Inicia conexão SOAP com o WebService				 
$client = new SoapClient(NULL, $options); 

//Dados da sua empresa
$dados->usuario->nome 		= 'Nome do Responsável'; //Nome da pessoa responsável por possíveis contatos da transportadora	
$dados->usuario->empresa 	= 'Nome Fantasia'; //Nome fantasia da empresa		
$dados->usuario->telefone	= '(DDD) XXXX-XXXX'; //Número de telefone para contato	

$dados->cepOrigem  = '00000-000'; //CEP de origem
$dados->cepDestino = '00000-000'; //CEP de destino

$dados->valorNota  = '50.00'; // Valor total da nota referente aos pacotes a serem enviados

//Pacotes para calculo de frete 
$dados->pacote{1}->peso        = 2500; //em gramas
$dados->pacote{1}->largura     = 40;	  //em centimentros
$dados->pacote{1}->altura      = 40;	  //em centimentros
$dados->pacote{1}->comprimento = 35;	  //em centimentros
$dados->pacote{1}->quantidade  = 1; 	  //em unidades

$dados->pacote{2}->peso         = 6500; //em gramas
$dados->pacote{2}->largura      = 20;	  //em centimentros
$dados->pacote{2}->altura       = 20;	  //em centimentros
$dados->pacote{2}->comprimento 	= 25; 	//em centimentros
$dados->pacote{2}->quantidade 	= 4;  	//em unidades

//... Se desejar enviar mais campos, basta seguir a sequência {3}...{4}...etc.


//Função utilizada para calcular frete
$retorno = $client->CalcularFrete($email,$senha,$token,$dados);


/*
Exibição dos Dados de Retorno
Para facilitar o entendimento e os testes deixaremos alguns exemplo de exibição do retorno. 
Após entender como tratar o retorno de acordo com o Manual do Desenvolvedor você poderá utilizar 
da forma que se encaixe em seu site ou sistema.
*/


/** /
//Exibe todo o retorno do WebService
echo '<pre>';
print_r($retorno);
echo '</pre>';
exit;
/**/

/**/
//Exemplo de como exibir apenas os campos desejados
if($retorno->retorno) { //Se retorno for igual a 1 (true) ele exibe os dados, se for 0 (false) ele gerou algum erro
	
	echo '<h1>Dados do Frete:</h1>';
	echo '<b>Cidade de Coleta:</b> '. $retorno->endereco->origem->cidade .'/'. $retorno->endereco->origem->uf .'<br />';
	echo '<b>Cidade de Destino:</b> '. $retorno->endereco->destino->cidade .'/'. $retorno->endereco->destino->uf .'<br />';
	echo '<hr />';
	echo '<h2>Fretes Gerados</h2>';
	
	foreach($retorno->servico as $frete) {
		echo '<b>'. $frete->nome .'</b><br />';
		echo 'Prazo de entrega de '. $frete->prazo .' no valor de R$ '. number_format($frete->frete,2,',','.').'<br />';
		echo '<a href="'. $frete->url_cotacao_pdf .'" target="_blank">Gerar Cotação</a><br /><br />';
	}
}
else {
	
	echo '<h1>Erro</h1>';
	foreach($retorno->erro as $erro) {
		echo $erro .'<br />';
	}
}
/**/

?>

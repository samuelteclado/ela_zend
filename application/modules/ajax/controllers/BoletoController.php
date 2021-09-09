<?php

class Ajax_BoletoController extends Zend_Controller_Action {

    private $_lancamentoRepository;

    public function preDispatch() {
        parent::preDispatch();

        $requestUri = explode('/', $this->getRequest()->getRequestUri());
        foreach ($requestUri as $key => $requestParam) {
            if ($requestParam == 'boleto' && $requestUri[$key + 1] == 'imagens') {
                break;
            }

            if ($requestParam == 'imagens') {
                $this->_redirect($this->view->baseUrl() . '/boleto/' . $requestParam . '/' . $requestUri[$key + 1]);
                break;
            }
        }
    }

    public function init() {
        $this->_lancamentoRepository = new LancamentoRepository();
    }

    public function gerarAction() {
        $id = EncryptionUtil::getInstance()->decode($_POST['apiKey']);

        $usuario_repository = new UsuarioRepository();
        $usuario = $usuario_repository->getById($id);
        
        if (!AppUtil::authenticate($usuario))
            exit("Autenticação falhou");

        $lancamento = $this->_getLancamento();

        if ($lancamento->id <= 0)
            exit("Boleto não encontrado");

        $lancamento->Boleto->clear();

        if ($lancamento->Boleto->count() <= 0) {
            $boleto_db = new Boleto();
            $boleto_db->data_emissao = date('Y-m-d');
            $boleto_db->numero = $lancamento->id;
            $boleto_db->escola_id = $lancamento->escola_id;
            $lancamento->Boleto->add($boleto_db);
            $lancamento->save();
        }

        $banco = $this->_getBanco($lancamento->ContaBancaria->Banco->codigo);
//instancia o helper boleto
        $boleto = $this->_helper->getHelper('boleto');

//instancia um objeto vazio para alocar os dados
        $dadosboleto = new DadosBoleto();

        $taxa_boleto = $lancamento->ContaBancaria->taxa_boleto;
        $valor_cobrado = $lancamento->getValorVencimentoAtual();
        $data_vencimento = $lancamento->getDataVencimentoAtual();
        $valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');


// Composição Nosso Numero - CEF SIGCB
        $dadosboleto->nosso_numero1 = "000"; // tamanho 3
        $dadosboleto->nosso_numero_const1 = "2"; //constanto 1 , 1=registrada , 2=sem registro
        $dadosboleto->nosso_numero2 = "000"; // tamanho 3
        $dadosboleto->nosso_numero_const2 = "4"; //constanto 2 , 4=emitido pelo proprio cliente
        $dadosboleto->nosso_numero3 = $lancamento->Boleto[0]->numero; // tamanho 9


        $dadosboleto->nosso_numero = $lancamento->Boleto[0]->numero;  // Nosso numero sem o DV - REGRA: Máximo de 11 caracteres!
        $dadosboleto->numero_documento = $dadosboleto->nosso_numero; // Num do pedido ou do documento = Nosso numero
        $dadosboleto->data_vencimento = AppUtil::convertDateToString($data_vencimento); // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto->data_documento = AppUtil::convertDateTimeToString($lancamento->Boleto[0]->data_emissao, 'date'); // Data de emissão do Boleto
        $dadosboleto->data_processamento = AppUtil::convertDateTimeToString($lancamento->Boleto[0]->data_emissao, 'date'); // Data de processamento do boleto (opcional)
        $dadosboleto->valor_boleto = $valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
// DADOS DO SEU CLIENTE
        $this->_setSacado($lancamento, $dadosboleto);

// INFORMACOES PARA O CLIENTE
        $demonstrativo_array = $this->_createArray($lancamento->ContaBancaria->Cedente[0]->demonstrativo);
        $dadosboleto->demonstrativo1 = $lancamento->descricao;
        $dadosboleto->demonstrativo2 = $demonstrativo_array[1];
        $dadosboleto->demonstrativo3 = $demonstrativo_array[2];

// INSTRUÇÕES PARA O CAIXA
        $instrucoes_array = $this->_createArray($lancamento->ContaBancaria->Cedente[0]->instrucoes);
        $dadosboleto->instrucoes1 = "Sr. Caixa, após vencimento: "; //$instrucoes_array[0];
        $dadosboleto->instrucoes2 = "A) cobrar multa de " . $lancamento->vencimento_multa . "%"; //$instrucoes_array[1];
        $dadosboleto->instrucoes3 = "B) cobrar juros de " . number_format($lancamento->vencimento_juros, 2, ',', '') . "% ao mês"; //$instrucoes_array[2];
        $dadosboleto->instrucoes4 = ""; //$instrucoes_array[3];
        $dadosboleto->local_pagamento = $lancamento->ContaBancaria->Cedente[0]->local_pagamento;

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto->quantidade = "";
        $dadosboleto->valor_unitario = "";
        $dadosboleto->aceite = "N";
        $dadosboleto->especie = "R$";
        $dadosboleto->especie_doc = "DS";

// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
// DADOS DA SUA CONTA - Bradesco
        $dadosboleto->agencia = $lancamento->ContaBancaria->agencia; // Num da agencia, sem digito
        $dadosboleto->agencia_dv = $lancamento->ContaBancaria->agencia_digito; // Digito do Num da agencia
        $dadosboleto->conta = $lancamento->ContaBancaria->conta;  // Num da conta, sem digito
        $dadosboleto->conta_dv = $lancamento->ContaBancaria->conta_digito;  // Digito do Num da conta
// DADOS PERSONALIZADOS - Bradesco
        $dadosboleto->conta_cedente = $lancamento->ContaBancaria->conta; // ContaCedente do Cliente, sem digito (Somente Números)
        $dadosboleto->conta_cedente_dv = $lancamento->ContaBancaria->conta_digito; // Digito da ContaCedente do Cliente
        $dadosboleto->carteira = $lancamento->ContaBancaria->carteira_padrao;  // Código da Carteira: pode ser 06 ou 03
// SEUS DADOS        
        $this->_setCedente($lancamento, $dadosboleto);

        $dadosboleto->identificacao = "";
        $dadosboleto->endereco = "";
        $dadosboleto->cidade_uf = "";

        $dadosboleto->url_imagens = $this->view->baseUrl() . '/boleto/';

//envia os dados para o helper
        $boleto->setDados($dadosboleto);
//seta qual o banco do boleto
        $boleto->setBanco($banco);
//renderiza o boleto
        $boleto->geraBoleto();
    }

    public function visualizarAction() {

        $lancamento = $this->_getLancamento();

        if ($lancamento->id <= 0)
            exit;

        $mpdf = $this->_createMPDF($lancamento);
        $mpdf->Output("boleto_" . $lancamento->id . ".pdf", "I");
        exit;
    }

    public function enviarAction() {

        $lancamento = $this->_getLancamento();

        if ($lancamento->id <= 0)
            exit;

        $mpdf = $this->_createMPDF($lancamento);

        $filename = "boleto_" . $lancamento->id . ".pdf";
        $attachment = $mpdf->Output($filename, "S");

        $app_email = Zend_Registry::getInstance()->get('email');

        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('remetente', $app_email->mail->de);

        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to = "samuelteclado@hotmail.com";

        $subject = "[" . $lancamento->Escola->razao_social . "] " . $lancamento->descricao;
        $body = $html->render('lembrar_senha.phtml');

        $send_email = EmailUtil::getInstance();
        $send_email->createAttachment($attachment, $filename, 'application/pdf');
        $send_email->send($from, $to, $subject, $body);

        exit;
    }

    public function downloadAction() {

        $lancamento = $this->_getLancamento();

        if ($lancamento->id <= 0)
            exit;

        $mpdf = $this->_createMPDF($lancamento);
        $mpdf->Output("boleto_" . $lancamento->id . ".pdf", "D");

        exit;
    }

    public function imagensAction() {
//instancia o helper boleto
        $boleto = $this->_helper->boleto();
//seta uma nova logo
//$boleto->setLogo("/images/site/logo.gif");
//renderiza todas as imagens
        $boleto->imagens();
    }

    private function _setSacado(Lancamento $lancamento, $dadosboleto) {
// DADOS DO SEU CLIENTE
        if ($lancamento->tipo == Lancamento::MENSALIDADE) {
            $dadosboleto->sacado = $lancamento->Escola->razao_social;
            $dadosboleto->sacado1 = $lancamento->Escola->razao_social;
            $dadosboleto->sacado2 = $lancamento->Escola->razao_social . ', ' . AppUtil::convertIntToCNPJ($lancamento->Escola->cnpj);
            $dadosboleto->endereco1 = $lancamento->Escola->getSede()->Endereco[0]->logradouro . ', ' . $lancamento->Escola->getSede()->Endereco[0]->numero . ' - ' . $lancamento->Escola->getSede()->Endereco[0]->bairro;
            $dadosboleto->endereco2 = AppUtil::convertIntToCEP($lancamento->Escola->getSede()->Endereco[0]->cep);
        } else {
            $dadosboleto->sacado = $lancamento->Aluno->ResponsavelAluno[0]->nome;
            $dadosboleto->sacado1 = $lancamento->Aluno->ResponsavelAluno[0]->nome;
            $dadosboleto->sacado2 = $lancamento->Aluno->ResponsavelAluno[0]->nome . ', ' . AppUtil::convertIntToCPF($lancamento->Aluno->ResponsavelAluno[0]->cpf);
            $dadosboleto->endereco1 = $lancamento->Aluno->AlunoEnderecoCobranca[0]->logradouro . ', ' . $lancamento->Aluno->AlunoEnderecoCobranca[0]->numero . ' - ' . $lancamento->Aluno->AlunoEnderecoCobranca[0]->bairro;
            $dadosboleto->endereco2 = AppUtil::convertIntToCEP($lancamento->Aluno->AlunoEnderecoCobranca[0]->cep);
        }
    }

    private function _setCedente(Lancamento $lancamento, $dadosboleto) {
// DADOS DO SEU CLIENTE
        if ($lancamento->tipo == Lancamento::MENSALIDADE) {
            $dadosboleto->cedente = "HOLY SOLUCOES EM SISTEMAS LTDA - ME";
            $dadosboleto->cedente_endereco = "R. FLOR DAS ALMAS, 217 - JARDIM ALVORADA - BELO HORIZONTE/MG - 30.810-130";
            $dadosboleto->cpf_cnpj = "14.633.923/0001-95";
        } else {
            $dadosboleto->cedente = $lancamento->Escola->razao_social;
            $dadosboleto->cedente_endereco = $lancamento->Escola->getSede()->Endereco[0]->logradouro . ', ' . $lancamento->Escola->getSede()->Endereco[0]->numero . ' - ' . $lancamento->Escola->getSede()->Endereco[0]->bairro . ' - ' . $lancamento->Escola->getSede()->Endereco[0]->cidade . '/' . $lancamento->Escola->getSede()->Endereco[0]->estado . ' - ' .  AppUtil::convertIntToCEP($lancamento->Escola->getSede()->Endereco[0]->cep);
            $dadosboleto->cpf_cnpj = $lancamento->Escola->cnpj;
        }
    }

    private function _getBanco($banco_codigo) {
        $banco = "";

        switch ($banco_codigo) {
            case 104:
                $banco = 'cef_html';
                break;
            case 237:
                $banco = 'bradesco';
                break;
            case 756:
                $banco = 'bancoob';
                break;
        }

        return $banco;
    }

    public function _createMPDF(Lancamento $lancamento) {
        $ch = curl_init($this->view->baseUrl() . '/ajax/boleto/gerar/' . $lancamento->id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'apiKey=' . EncryptionUtil::getInstance()->encode(Zend_Auth::getInstance()->getIdentity()->id));

        $output = curl_exec($ch);
        
        include(APPLICATION_BOLETO_PATH . "/mpdf.php");

        $mpdf = new mPDF('c','A4', 0, '', 11.8, 2, 9, 0, 0, 0, 'P');

        $mpdf->allow_charset_conversion = true;
        // permite a conversao (opcional)
        $mpdf->charset_in = 'UTF-8';


        // Colocar Rodapé $mpdf->SetFooter('{DATE j/m/Y H:i}|{PAGENO}/{nb}|SIGES / ' . $lancamento->Escola->razao_social);

        $mpdf->WriteHTML($output);

        return $mpdf;
    }

    private function _createArray($text) {
        $text_array = explode(PHP_EOL, $text);
        $result = array("", "", "", "");

        for ($index = 0; $index < count($text_array); $index++) {
            $result[$index] = $text_array[$index];
        }

        return $result;
    }

    private function _getBoletoNumero() {
        $boleto_numero = NULL;

        $requestUri = explode('/', $this->getRequest()->getRequestUri());

        foreach ($requestUri as $key => $requestParam) {
            if ($requestParam == 'visualizar' || $requestParam == 'gerar' || $requestParam == 'download' || $requestParam == 'enviar') {
                $boleto_numero = $requestUri[$key + 1];
                break;
            }
        }

        return $boleto_numero;
    }

    private function _getLancamento() {
        $boleto_numero = $this->_getBoletoNumero();

        $lancamento = $this->_lancamentoRepository->getById(EncryptionUtil::getInstance()->decode($boleto_numero));

        return $lancamento;
    }

}

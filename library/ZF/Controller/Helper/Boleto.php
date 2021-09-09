<?php

class Zend_Controller_Action_Helper_Boleto extends Zend_Controller_Action_Helper_Abstract {

    protected $_options = null;
    protected $_output = '';
    protected $banco;
    protected $dados;
    protected $dir = '/include/boletophp';
    protected $logo;

    //permite a chamada direta do helper
    protected function action(array $options = null) {
        $this->_options = $options;
        return $this;
    }

    public function init() {
        //retira o layout
        $this->getActionController()->getHelper('layout')->disableLayout();
        //retira o view
        $this->getActionController()->getHelper('viewRenderer')->setNoRender();
    }

    //seta uma logomarca, caso essa logomarca nao seja setada, sera usada a padrão do boletophp
    public function setLogo($logo) {
        $this->logo = $logo;
    }

    //seta os dados do boleto
    public function setDados($dados) {
        //transforma os dados em um array, forma padrao do boleto php
        $dados = (array) $dados;

        $this->dados = AppUtil::convertArrayValuesToUtf8Decode($dados);
    }

    //seta a pasta do boletophp, caso queira modificar
    public function setDir($dir = 'boletophp') {
        $this->dir = $dir;
    }

    //seta o banco do boleto
    public function setBanco($banco) {
        $this->banco = $banco;
    }

    //funcao render do boleto
    public function geraBoleto() {
        //aloca a variavel global para a o $dadosboleto (padrao do boleto php)
        $dadosboleto = $this->dados;
        foreach ($dadosboleto as $key => $dados) {
            if (is_string($dados)) {
                //descoverte as strings utf (necessario para rodar no zend, ja que todo o projeto esta em utf8)
                $dadosboleto[$key] = utf8_decode($dados);
            }
        }
        // corrige uma pequena quebra de padrao do boleto php
        $data_venc = $dadosboleto['data_vencimento'];
        //inclue os dois arquivos do boleto

        include(APPLICATION_PATH . $this->dir . "/include/funcoes_" . $this->banco . ".php");
        include(APPLICATION_PATH . $this->dir . "/include/layout_" . $this->banco . ".php");
    }

    //instancia cada imagem em um novo endereco normalizado com o zend framework
    public function imagens() {
        $requestUri = explode('/', $this->getRequest()->getRequestUri());
        foreach ($requestUri as $key => $requestParam) {
            if ($requestParam == 'imagens') {
                $finalKey = $key + 1;
                break;
            }
        }
        //caso a logo seja definida pelo usuario, faz a troca da imagem logo_empresa.png (padrao do boleto php)
        if ($requestUri[$finalKey] == 'logo_empresa.png' && !empty($this->logo)) {
            $filename = $this->getFullUrl($this->logo);
        } else {
            $filename = APPLICATION_PATH . $this->dir . '/imagens/' . $requestUri[$finalKey];
        }
        //re-renderiza cada imagem solicitada com o caminho normalizado
        $image = file_get_contents($filename);
        $this->getResponse()->clearBody();
        $this->getResponse()->setHeader('Content-Type', 'image/jpeg');
        $this->getResponse()->setBody($image);
    }

    //para normalizacao da nova logo, refaz o o endereço da nova imagem para a nova renderização
    protected function getFullUrl($url) {
        $proto = strtolower(preg_replace('/[^a-zA-Z]/', '', $_SERVER['SERVER_PROTOCOL']));
        $baseUrl = $this->getRequest()->getHttpHost() . $this->getRequest()->getBasePath();
        return $proto . '://' . $baseUrl . $url;
    }

    //acesso ao metodo direct
    public function direct(array $options = null) {

        return $this->action($options);
    }

}

//classe vazia para store dos dados do boleto
class DadosBoleto extends stdClass {
    
}

<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 06/12/2017
 * Time: 17:04
 */

namespace Source\Models;

class Payment
{
    /***
     * Parâmetros de Autenticação
     */
    private $service;
    private $email;
    private $token;

    /***
     * Parâmetros do REST
     */
    private $action;
    private $callback;
    private $params;

    /***
     * Atributo de sessão da conexão
     */
    private $sessionId;

    /**
     * <b>Construtor</b>: Não é necessário fazer instância desse método.
     * Responsabilidade de setar os parâmetros de autenticação com o WebService
     */
    public function __construct()
    {
        $this->service = 'https://ws.sandbox.pagseguro.uol.com.br';
        $this->email = ''; // Seu e-mail de Login no PagSeguro
        $this->token = ''; // Token da sua conta no PagSeguro
    }

    /**
     * <b>createPlan</b>: Método responsável por fazer a criação de um novo plano
     * no PagSeguro utilizando o WebService
     * @param INT $ref = ID do plano no banco de dados
     * @param STRING $name = Nome do plano
     * @param STRING $period = Periodicidade de cobrança [WEEKLY, MONTHLY, BIMONTHLY, TRIMONTHLY, SEMIANNUALLY ou YEARLY]
     * @param DECIMAL $amount = Valor do plano com duas casas decimais separadas por ponto (100.00)
     */
    public function createPlan($ref, $name, $period, $amount)
    {
        $this->action = '/pre-approvals/request';
        $this->params = [
            'reference' => $ref,
            'preApproval' => [
                'name' => $name,
                'charge' => 'AUTO',
                'period' => $period,
                'amountPerPayment' => $amount,
            ],
        ];

        $this->post();
    }

    /**
     * <b>createMemberShip</b>: Método responsável por fazer a adesão de um
     * cliente ao plano.
     * @param STRING $plan = Código do plano junto ao PagSeguro
     * @param STRING $ref = ID da assinatura no banco de dados
     * @param STRING $name = Nome do Cliente
     * @param STRING $email = E-mail do Cliente
     * @param STRING $document = CPF do Cliente
     * @param STRING $phone = Telefone de contato junto com o DDD (Sem pontuação)
     * @param STRING $street = Endereço do Cliente
     * @param STRING $number = Número do Endereço
     * @param STRING $complement = Complemento do Endereço
     * @param STRING $district = Bairro do Endereço
     * @param STRING $city = Cidade do Endereço
     * @param STRING $state = Estado do Endereço
     * @param STRING $country = Sigla do País com 3 letras (BRA)
     * @param STRING $postalCode = CEP do Endereço
     * @param STRING $token = Token do cartão de crédito
     * @param STRING $holderName = Nome do Titular do Cartão
     * @param DATE $holderBirth = Data de Nascimento do Titular do Cartão no formado DD/MM/AAAA
     * @param STRING $holderPhone = Telefone de contato junto com o DDD do Titular do Cartão (Sem pontuação)
     */
    public function createMemberShip($plan, $ref, $name, $email, $document, $phone, $street, $number, $complement, $district, $city, $state, $country, $postalCode, $token, $holderName, $holderBirth, $holderPhone)
    {
        $this->action = '/pre-approvals';
        $this->params = [
            'plan' => $plan,
            'reference' => $ref,
            'sender' => [
                'name' => $name,
                'email' => $email,
                'ip' => '1.1.1.1',
                'phone' => [
                    'areaCode' => substr($phone, 0, 2),
                    'number' => substr($phone, 2),
                ],
                'address' => [
                    'street' => $street,
                    'number' => $number,
                    'complement' => $complement,
                    'district' => $district,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'postalCode' => $postalCode,
                ],
                'documents' => [
                    ['type' => 'CPF', 'value' => $document],
                ],
            ],
            'paymentMethod' => [
                'type' => 'CREDITCARD',
                'creditCard' => [
                    'token' => $token,
                    'holder' => [
                        'name' => $holderName,
                        'birthDate' => $holderBirth,
                        'documents' => [
                            ['type' => 'CPF', 'value' => '23363265824'],
                        ],
                        'phone' => [
                            'areaCode' => substr($holderPhone, 0, 2),
                            'number' => substr($holderPhone, 2),
                        ],
                    ],
                ],
            ],
        ];

        $this->post();
    }

    /**
     * <b>getSessionId</b>: Método utilizado para resgatar o ID da sessão para que possa consumir as informações
     * do javascript nos métodos que manipulam o cartão de crédito.
     */
    public function getSessionId()
    {
        $action = '/v2/sessions';
        $params = [
            'email' => $this->email,
            'token' => $this->token,
        ];

        $ch = curl_init($this->service.$action);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //echo curl_exec($ch);
        $result = simplexml_load_string(curl_exec($ch));
        curl_close($ch);
        $this->sessionId = $result->id;

        return $this->sessionId;
    }

    /**
     * <b>getCallback</b>: Método responsável por retornar o objeto da comunicação REST
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * <b>getMemberShip</b>: Método responsável por retornar o objeto de assinatura do PagSeguro
     * @param STRING $code = Código retornado pela API de notificação do PagSeguro
     */
    public function getMemberShip($code)
    {
        $this->action = "/pre-approvals/notifications/{$code}";
        $this->get();
    }

    /**
     * <b>getTransaction</b>: Método responsável por retornar o objeto de transação do PagSeguro
     * @param STRING $code = Código retornado pela API de notificação do PagSeguro
     */
    public function getTransaction($code)
    {
        $this->action = "/v2/transactions/notifications/{$code}";
        $this->get();
    }

    /**
     * <b>get</b>: Método responsável por resgatar informações da comunicação REST
     */
    private function get()
    {
        $url = $this->service.$this->action."?email={$this->email}&token={$this->token}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'));
        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);

        if (empty($this->callback)) {
            $url = $this->service.$this->action."?email={$this->email}&token={$this->token}";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Accept: application/xml;charset=ISO-8859-1'));
            $this->callback = simplexml_load_string(curl_exec($ch));
            curl_close($ch);
        }
    }

    /**
     * <b>post</b>: Método responsável por inputar informações na comunicação REST
     */
    private function post()
    {
        $url = $this->service.$this->action."?email={$this->email}&token={$this->token}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'));
        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }
}
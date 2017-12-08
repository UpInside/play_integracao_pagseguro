<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 06/12/2017
 * Time: 16:16
 */

require __DIR__.'/Source/Models/Payment.php';
$pay = new \Source\Models\Payment;

$plan = [
    'plan_id' => 1,
    //'plan_code_pagseguro' => 'CODIGO DO PLANO AQUI',
    'plan_title' => 'Meu Plano Teste',
    'plan_price' => '250.00',
    'plan_active' => 1,
    'plan_recurrency' => 'MONTHLY'
];

//Chamada do método para criação do plano
//$pay->createPlan('1', $plan['plan_title'], $plan['plan_recurrency'], $plan['plan_price']);

$user = [
    'user_name' => 'Gustavo Web',
    'user_document' => '91309581304',
    'user_phone' => '48988888888',
    //'user_email' => 'EMAIL DO SEU CLIENTE DE TESTE (DISPONÍVEL NA DASH DO PAGSEGURO)',
    'user_addr_street' => 'Rua dos Bobos',
    'user_addr_number' => '0',
    'user_addr_complement' => 'Apto 123',
    'user_addr_district' => 'Campeche',
    'user_addr_city' => 'Floripa',
    'user_addr_state' => 'SC',
    'user_addr_country' => 'BRA',
    'user_addr_postalcode' => '88063301',
];

$card = [
    'card_number' => '4111111111111111',
    //'card_brand' => 'NOME DA BANDEIRA',
    //'card_token' => 'TOKEN DO CARTAO DE CREDITO',
    'card_cvv' => '123',
    'card_expiration_month' => '12',
    'card_expiration_year' => '2018',
    'card_holder_name' => 'Gustavo Web',
    'card_holder_birth' => '28/10/1992',
    'card_holder_phone' => '48988888888'
];

// Chamada do método de adesão
//$pay->createMemberShip(
//    $plan['plan_code_pagseguro'],
//    '1',
//    $user['user_name'],
//    $user['user_email'],
//    $user['user_document'],
//    $user['user_phone'],
//    $user['user_addr_street'],
//    $user['user_addr_number'],
//    $user['user_addr_complement'],
//    $user['user_addr_district'],
//    $user['user_addr_city'],
//    $user['user_addr_state'],
//    $user['user_addr_country'],
//    $user['user_addr_postalcode'],
//    $card['card_token'],
//    $card['card_holder_name'],
//    $card['card_holder_birth'],
//    $card['card_holder_phone']
//);

// Chamada do método de resgate do objeto de assinatura
//$pay->getMemberShip('CODIGO DA ASSINATURA NO PAGSEGURO');
//var_dump($pay, $pay->getCallback());

// Chamada do método de resgate do objeto de trnsação
//$pay->getTransaction('75BDD182F35DF35D8DF994769F9D21FCE27D');
//var_dump($pay, $pay->getCallback());

?>

<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js">
</script>
<script type="text/javascript">
    //PagSeguroDirectPayment.setSessionId('<?//= $pay->getSessionId(); ?>//');

    //cardNumber = '4111111111111111';
    //cardBrand = '';

    //PagSeguroDirectPayment.getBrand({
    //    cardBin: cardNumber,
    //    success: function(response) {
    //        console.log(response);
    //        cardBrand = response.brand.name;
    //    },
    //    error: function(response) {
    //        console.log(response);
    //    }
    //});

    //PagSeguroDirectPayment.createCardToken({
    //    cardNumber: cardNumber,
    //    brand: cardBrand,
    //    cvv: '123',
    //    expirationMonth: '12',
    //    expirationYear: '2018',
    //    success: function(response){
    //        console.log(response);
    //    },
    //    error: function(response){
    //        console.log(response);
    //    }
    //});
</script>
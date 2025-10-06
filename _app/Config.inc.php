<?php
error_reporting(0); // Desativa todos os tipos de erro
ini_set('display_errors', 0); // Garante que não exiba erros
/*
 * BANCO DE DADOS
 */
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'www.localhost') :
    define('SIS_DB_HOST', 'localhost'); //Link do banco de dados no localhost
    define('SIS_DB_USER', 'root'); //Usuário do banco de dados no localhost
    define('SIS_DB_PASS', ''); //Senha  do banco de dados no localhost
    define('SIS_DB_DBSA', 'bd_resicore'); //Nome  do banco de dados no localhost
else:
    define('SIS_DB_HOST', 'localhost'); //Link do banco de dados no servidor
    define('SIS_DB_USER', 'resicorecom_root'); //Usuário do banco de dados no servidor
    define('SIS_DB_PASS', '8=3uiHM#3LsO'); //Senha  do banco de dados no servidor
    define('SIS_DB_DBSA', 'resicorecom_bd'); //Nome  do banco de dados no servidor
endif;
/*
 * CACHE E CONFIG
 */
define('SIS_CACHE_TIME', 10); //Tempo em minutos de sessão
define('SIS_CONFIG_WC', 1); //Registrar configurações no banco para gerenciar pelo painel!
/*
 * AUTO MANAGER
 */
define('DB_AUTO_TRASH', 0); //Remove todos os itens não gerenciados do banco!
define('DB_AUTO_PING', 0); //Tenta enviar 1x por dia o sitemap e o RSS para o Google/Bing
/*
 * TABELAS
 */
define('DB_CONF', 'config'); //Tabela de Configurações
define('DB_USERS', 'parceiros'); //Tabela de usuários
define('DB_USERS_ADDR', 'parceiros_address'); //Tabela de endereço de usuários
define('DB_USERS_NOTES', 'parceiros_notes'); //Tabela de notas do usuário
define('DB_POSTS', 'posts'); //Tabela de posts
define('DB_POSTS_IMAGE', 'posts_images'); //Tabela de imagens de post
define('DB_CATEGORIES', 'categories'); //Tabela de categorias de posts
define('DB_SEARCH', 'search'); //Tabela de pesquisas
define('DB_PAGES', 'pages'); //Tabela de páginas
define('DB_PAGES_IMAGE', 'pages_images'); //Tabela de imagens da página
define('DB_COMMENTS', 'comments'); //Tabela de Comentários
define('DB_COMMENTS_LIKES', 'comments_likes'); //Tabela GOSTEI dos Comentários
define('DB_PDT', 'products'); //Tabela de produtos
define('DB_PDT_STOCK', 'products_stock'); //Tabela de estoque por variação
define('DB_PDT_IMAGE', 'products_images'); //Tabela de imagem de produtos
define('DB_PDT_GALLERY', 'products_gallery'); //Tabela de galeria de produtos
define('DB_PDT_CATS', 'products_categories'); //Tabela de categorias de produtos
define('DB_PDT_BRANDS', 'products_brands'); //Tabela de fabricantes/marcas de produtos
define('DB_PDT_COUPONS', 'products_coupons'); //Tabela de Cupons de desconto
define('DB_ORDERS', 'orders'); //Tabela de pedidos
define('DB_IMOBI', 'properties'); //Tabela de imóveis WS IMOBI
define('DB_IMOBI_GALLERY', 'properties_gallery'); //Tabela de galeria de imóveis
define('DB_SLIDES', 'slides'); //Tabela de conteúdo em destaque
define('DB_ORDERS_ITEMS', 'orders_items'); //Tabela de itens do pedido
define('DB_VIEWS_VIEWS', 'siteviews_views'); //Controle de acesso ao site
define('DB_VIEWS_ONLINE', 'siteviews_online'); //Controle de usuários online
define('DB_API', 'workcontrol_api'); //Controle de api do WC
define('DB_CODE', 'workcontrol_code'); //Controle de code de WC

/*
 * EAD DBSA
 */
define('DB_EAD_COURSES', 'ead_courses'); //Tabela de cursos
define('DB_EAD_COURSES_BONUS', 'ead_courses_bonus'); //Tabela de bônus para cursos
define('DB_EAD_COURSES_SEGMENTS', 'ead_courses_segments'); //Tabela de segmentos de cursos
define('DB_EAD_MODULES', 'ead_modules'); //Tabela de módulos
define('DB_EAD_CLASSES', 'ead_classes'); //Tabela de aulas
define('DB_EAD_ENROLLMENTS', 'ead_enrollments'); //Tabela de matrículas
define('DB_EAD_ORDERS', 'ead_orders'); //Tabela de pedidos
define('DB_EAD_SUPPORT', 'ead_support'); //Tabela de dúvidas
define('DB_EAD_SUPPORT_REPLY', 'ead_support_reply'); //Tabela de respostas
define('DB_EAD_STUDENT_CLASSES', 'ead_student_classes'); //Tabela de matrículas
define('DB_EAD_STUDENT_CERTIFICATES', 'ead_student_certificates'); //Tabela de certificados
define('DB_EAD_STUDENT_DOWNLOADS', 'ead_student_downloads'); //Tabela de downloads do curso


/*
  AUTO LOAD DE CLASSES
 */

function MyAutoLoad($Class)
{
    $cDir = ['Conn', 'Helpers', 'Models', 'WorkControl'];
    $iDir = null;

    foreach ($cDir as $dirName):
        if (!$iDir && file_exists(__DIR__ . '/' . $dirName . '/' . $Class . '.class.php') && !is_dir(__DIR__ . '/' . $dirName . '/' . $Class . '.class.php')):
            include_once(__DIR__ . '/' . $dirName . '/' . $Class . '.class.php');
            $iDir = true;
        endif;
    endforeach;
}

spl_autoload_register("MyAutoLoad");

/*
 * Define todas as constantes do banco dando sua devida preferência!
 */
$WorkControlDefineConf = 1;
if (SIS_CONFIG_WC):
    $Read = new Read;
    $Read->FullRead("SELECT conf_key, conf_value FROM " . DB_CONF);
    if ($Read->getResult()):
        foreach ($Read->getResult() as $WorkControlDefineConf):
            if ($WorkControlDefineConf['conf_key'] != 'THEME' || empty($_SESSION['WC_THEME'])):
                define("{$WorkControlDefineConf['conf_key']}", "{$WorkControlDefineConf['conf_value']}");
            endif;
        endforeach;
        $WorkControlDefineConf = true;
    endif;
endif;

require 'Config/Config.inc.php';
require 'Config/Agency.inc.php';
require 'Config/Client.inc.php';
require 'Config/Custom.inc.php';

/*
 * Exibe erros lançados
 */

function Erro($ErrMsg, $ErrNo = null)
{
    $CssClass = ($ErrNo == E_USER_NOTICE ? 'trigger_info' : ($ErrNo == E_USER_WARNING ? 'trigger_alert' : ($ErrNo == E_USER_ERROR ? 'trigger_error' : 'trigger_success')));
    echo "<div class='trigger {$CssClass}'>{$ErrMsg}<span class='ajax_close'></span></div>";
}

/*
 * Exibe erros lançados por ajax
 */

function AjaxErro($ErrMsg, $ErrNo = null)
{
    $CssClass = ($ErrNo == E_USER_NOTICE ? 'trigger_info' : ($ErrNo == E_USER_WARNING ? 'trigger_alert' : ($ErrNo == E_USER_ERROR ? 'trigger_error' : 'trigger_success')));
    return "<div class='trigger trigger_ajax {$CssClass}'>{$ErrMsg}<span class='ajax_close'></span></div>";
}

/*
 * personaliza o gatilho do PHP
 */

function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine)
{
    echo "<div class='trigger trigger_error'>";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class='ajax_close'></span></div>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');


/*
 * Descreve nivel de usuário
 */

function getWcLevel($Level = null)
{
    $UserLevel = [
        1 => 'ResiH',
        6 => 'ResiH + ResiCore',
        9 => 'Administrador',
        10 => 'Super Admin'
    ];

    if (!empty($Level)):
        return $UserLevel[$Level];
    else:
        return $UserLevel;
    endif;
}

/*
 * Descreve estatus de pedidos
 */

function getOrderStatus($Status = null)
{
    $OrderStatus = [
        1 => 'Concluído',
        2 => 'Cancelado',
        3 => 'Novo Pedido',
        4 => 'Agd. Pagamento', //OPERADORA
        5 => 'Agd. Pagamento', //CONFIRMAÇÃO MANUAL (BOLETO, DEPÓSITO)
        6 => 'Processando'
    ];

    if (!empty($Status)):
        return $OrderStatus[$Status];
    else:
        return $OrderStatus;
    endif;
}

/*
 * Descreve tipos de pagamentos
 */

function getOrderPayment($Payment = null)
{
    $Payments = [
        1 => 'Pendente',
        101 => 'Cartão de Crédito', //PAGSEGURO
        102 => 'Boleto Bancário' //PAGSEGURO
    ];

    if (!empty($Payment)):
        return $Payments[$Payment];
    else:
        return $Payments;
    endif;
}

/*
 * Fator multiplicador PagSeguro
 * https://pagseguro.uol.com.br/para_seu_negocio/parcelamento_com_acrescimo.jhtml#rmcl
 * @author: Whallysson Avelino <whallyssonallain@gmail.com>
 */

function getFactor($Factor = null)
{
    $FactorMult = [
        1 => 1.00000,
        2 => 0.52255,
        3 => 0.35347,
        4 => 0.26898,
        5 => 0.21830,
        6 => 0.18453,
        7 => 0.16044,
        8 => 0.14240,
        9 => 0.12838,
        10 => 0.11717,
        11 => 0.10802,
        12 => 0.10040,
        13 => 0.09397,
        14 => 0.08846,
        15 => 0.08371,
        16 => 0.07955,
        17 => 0.07589,
        18 => 0.07265
    ];
    if (!empty($Factor)):
        return $FactorMult[$Factor];
    else:
        return $FactorMult;
    endif;
}

/*
 * Recupera Meios de Entrega
 */

function getShipmentTag($Tag = null)
{
    $ArrShipment = [
        '10001' => 'Envio Padrão', //Código para envio pela transportadora
        '10002' => 'Envio Gratis', //Código para envio sem custo
        '10003' => 'Envio Fixo', //Código para envio de frete fixo
        '10004' => 'Taxa de Entrega', //Taxa de Entrega
        '10005' => 'Retirar na Loja', //ID para o app de criar pedido
        '40010' => 'Sedex', //40010 SEDEX sem contrato.
        '40045' => 'Sedex a Cobrar', //40045 SEDEX a Cobrar, sem contrato.
        '40126' => 'Sedex a Cobrar', //40126 SEDEX a Cobrar, com contrato.
        '40215' => 'Sedex 10', //40215 SEDEX 10, sem contrato.
        '40290' => 'Sedex Hoje', //40290 SEDEX Hoje, sem contrato.
        '40096' => 'Sedex', //40096 SEDEX com contrato.
        '40436' => 'Sedex', //40436 SEDEX com contrato.
        '40444' => 'Sedex', //40444 SEDEX com contrato.
        '40568' => 'Sedex', //40568 SEDEX com contrato.
        '40606' => 'Sedex', //40606 SEDEX com contrato.
        '41106' => 'PAC', //41106 PAC sem contrato.
        '41068' => 'PAC', //41068 PAC com contrato.
        '81019' => 'e-Sedex', //81019 e-SEDEX, com contrato.
        '81027' => 'e-Sedex Prioritário', //81027 e-SEDEX Prioritário, com contrato.
        '81035' => 'e-Sedex Express', //81035 e-SEDEX Express, com contrato.
        '81868' => 'e-Sedex', //81868 (Grupo 1) e-SEDEX, com contrato.
        '81833' => 'e-Sedex', //81833 (Grupo 2) e-SEDEX, com contrato.
        '81850' => 'e-Sedex', //81850 (Grupo 3) e-SEDEX, com contrato.
        '4014' => 'Sedex', // Novo código de serviço dos correios 2017-05
        '4510' => 'PAC' // Novo código de serviço dos correios 2017-05
    ];

    if (!empty($Tag) && array_key_exists($Tag, $ArrShipment)):
        return $ArrShipment[$Tag];
    else:
        return $ArrShipment;
    endif;
}

/*
 * Recupera Tipos de imóveis
 */

function getWcRealtyType($Type = null)
{
    $RealtyTypes = [
        1 => 'Apartamento',
        2 => 'Área',
        3 => 'Casa',
        4 => 'Galpão',
        5 => 'Pousada',
        6 => 'Prédio',
        7 => 'Sala',
        8 => 'Terreno'
    ];
    if (!empty($Type)):
        return $RealtyTypes[$Type];
    else:
        return $RealtyTypes;
    endif;
}

function getWcRealtyFinality($Finality = null)
{
    $RealtyFinality = [
        1 => 'Comercial',
        2 => 'Residencial'
    ];
    if (!empty($Finality)):
        return $RealtyFinality[$Finality];
    else:
        return $RealtyFinality;
    endif;
}

function getWcRealtyTransaction($Transaction = null)
{
    $RealtyTransaction = [
        1 => 'Alugar',
        2 => 'Comprar',
        3 => 'Temporada'
    ];
    if (!empty($Transaction)):
        return $RealtyTransaction[$Transaction];
    else:
        return $RealtyTransaction;
    endif;
}

function getWcRealtyNote($Note = null)
{
    $RealtyNotes = [
        1 => 'Destaque',
        2 => 'Lançamento',
        3 => 'Reservado',
        4 => 'Locado',
        5 => 'Vendido',
        6 => 'Indisponível',
    ];
    if (!empty($Note)):
        return $RealtyNotes[$Note];
    else:
        return $RealtyNotes;
    endif;
}

function getWcHotmartStatus($Status = null)
{
    $HotmartStatus = [
        'started' => 'Iniciado',
        'billet_printed' => 'Boleto Impresso',
        'pending_analysis' => 'Pendente',
        'delayed' => 'Atrasado',
        'canceled' => 'Cancelado',
        'approved' => 'Aprovado',
        'completed' => 'Concluído',
        'chargeback' => 'Chargeback',
        'blocked' => 'Bloqueado',
        'refunded' => 'Devolvido',
        'admin_free' => 'Cadastrado'
    ];
    if (!empty($Status)):
        return $HotmartStatus[$Status];
    else:
        return $HotmartStatus;
    endif;
}

function getWcHotmartStatusClass($Status = null)
{
    $HotmartStatus = [
        'started' => 'blue icon-checkmark2',
        'billet_printed' => 'blue icon-barcode',
        'pending_analysis' => 'blue icon-history',
        'delayed' => 'yellow icon-alarm',
        'canceled' => 'red icon-cancel-circle',
        'approved' => 'green icon-checkmark',
        'completed' => 'green icon-checkbox-checked',
        'chargeback' => 'yellow icon-warning',
        'blocked' => 'red icon-lock',
        'refunded' => 'red icon-cross',
        'admin_free' => 'green icon-bell'
    ];
    if (!empty($Status)):
        return $HotmartStatus[$Status];
    else:
        return $HotmartStatus;
    endif;
}

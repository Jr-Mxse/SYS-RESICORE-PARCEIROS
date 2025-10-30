<?php

/** ===============
 * DATETIME
 * ================
 */
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

define('APP_MARCAS', 1);
define('DB_MARCAS', 'marcas');
define('LEVEL_MARCAS', 6);

define('APP_CLIENTES', 1);
define('DB_CLIENTES', 'clientes');
define('DB_CLIENTES_ADDR', 'clientes_address');
define('DB_CLIENTES_CNPJ', 'clientes_empresas');
define('DB_CLIENTES_DOC', 'clientes_documentos');
define('DB_CLIENTES_CONTRATOS', 'clientes_contratos');
define('DB_CLIENTES_CONTRATOS_RESP', 'clientes_contratos_resp');
define('DB_CLIENTES_CONTRATOS_ITENS', 'clientes_contratos_itens');
define('LEVEL_CLIENTES', 6);

define('APP_SERVICOS', 1);
define('DB_SERVICOS', 'servicos');
define('DB_SERVICOS_ITENS', 'servicos_itens');
define('LEVEL_SERVICOS', 6);

define('DB_PROPOSTAS', 'propostas');
define('DB_PROPOSTA_CADASTRO', 'proposta_cadastro');
define('DB_PROPOSTA_CONFIG', 'proposta_config');
define('DB_USERS_LINKS', 'users_links');

define('APP_PARCEIROS', 1);
define('DB_PARCEIROS', 'parceiros');
define('DB_PARCEIROS_ADDR', 'parceiros_address');
define('DB_PARCEIROS_DOC', 'parceiros_documentos');
define('DB_PARCEIROS_CONVITE', 'parceiros_convite');
define('DB_PARCEIROS_MIGRAR', 'parceiros_migrar');
define('DB_LEADS', 'parceiros_leads');
define('DB_LEADS_DOC', 'parceiros_leads_documentos');
define('LEVEL_PARCEIROS', 6);

/*
 * GERENCIADOR DE Transferencias
*/
define('APP_TRANSF', 1);
define('DB_TRANSF', 'transferencias');
define('DB_TRANSF_IMOBI', 'transferencias_imoveis');
define('LEVEL_TRANSF', 6);
define('DB_TRANSF_CARDS', 'transferencias_cards'); // Kanban de tarefas (fazer / fazendo / feito)
define('DB_TRANSF_CARDS_GR', 'transferencias_cards_group'); // Grupo do kanban de tarefas
define('DB_TRANSF_MAKE', 'transferencias_make'); // Sub tarefas (check)
define('DB_TRANSF_TAGS', 'transferencias_tags'); // Sub-categorias para organizar os projetos
define('DB_TRANSF_PROJECTS', 'transferencias_projects'); // Projetos das tarefas
define('DB_TRANSF_PROMODORO', 'transferencias_promodoro'); // Registros de tempo
define('DB_TRANSF_LOGS', 'transferencias_logs'); // Log das tarefas
define('DB_TRANSF_FEED', 'transferencias_feedbacks'); // Log de feedbacks enviados aos clientes
define('TRANSF_PROMODORO_TIMER', '3000'); // Tempo de cada promodoro  [em segundos]
define('TRANSF_PROMODORO_SLEEP', '300'); // Tempo de cada descanso [em segundos]
define('TRANSF_KANBAN_AUTOCREATE', 1); // Cria automaticamente tarefas a partir da funcão setProjKanban()
define('TRANSF_KANBAN_LIMIT', 20); // Limite de tarefas exibidas no kanban
define('TRANSF_DIAGNOSIS', 0); // Habilita diagnóstico de projeto na lista de projetos
define('TRANSF_TIME_MANAGER', 0); // Habilita marcação de tempo
define('TRANSF_TIME_BEEP', 'beep-4'); // Beep disparado no fim do timer (consule pasta /admin/_sis/transferencias/files/_src/beeps/)
define('TRANSF_FEEDBACKS', 0); // Habilita feedbacks ao cliente - [área do cliente necessária!] 
define('TRANSF_CLIENT_PERMISSION', 1); // Habilita para mais pessoas terem acesso ao projeto - além do dono (cliente)
define('TRANSF_PRIVATE', 0); // Usuário só pode ver seus projetos - ou os que foi convidado

define('APP_FAQ', 1);
define('LEVEL_FAQ', 6);
define('DB_FAQ', 'faq');

function getProjProgress($Progress = null)
{
    $PR = [
        1 => 'Aguardando',
        2 => 'Execução',
        3 => 'Finalizado'
    ];

    if ((!empty($Progress) || $Progress == '0') && array_key_exists($Progress, $PR)) :
        return $PR[$Progress];
    else :
        return $PR;
    endif;
}

function getProjType($Type = null)
{
    $types = [
        1 => 'Modelo',
        2 => 'Projeto Normal',
        3 => 'Projeto Interno'
    ];

    if ((!empty($Type) || $Type == '0') && array_key_exists($Type, $types)) :
        return $types[$Type];
    else :
        return $types;
    endif;
}

function getCardType($Progress = null)
{
    $PR = [
        1 => 'Simples',
        2 => 'Card de Trabalhos em Produção',
        3 => 'Card de Trabalhos Concluídos',
        4 => 'Card de Bugs'
    ];

    if ((!empty($Progress) || $Progress == '0') && array_key_exists($Progress, $PR)) :
        return $PR[$Progress];
    else :
        return $PR;
    endif;
}

function getCardColor($Color = null)
{
    $CL = [
        '#bcbcbc' => 'grey',
        '#1cae5d' => 'green',
        '#269de3' => 'blue',
        '#ecaa17' => 'yellow',
        '#ff3f03' => 'red'
    ];

    if (!empty($Color) && array_key_exists($Color, $CL)) :
        return $CL[$Color];
    else :
        return $CL;
    endif;
}

function setProjKanban()
{
    $KAN = [[
        'card_icon' => 'icon-info',
        'card_text' => 'ETAPA 01',
        'card_color' => '#bcbcbc'
    ], [
        'card_icon' => 'icon-info',
        'card_text' => 'ETAPA 02',
        'card_color' => '#269de3'
    ], [
        'card_icon' => 'icon-info',
        'card_text' => 'ETAPA 03',
        'card_color' => '#ecaa17'
    ], [
        'card_icon' => 'icon-info',
        'card_text' => 'ETAPA 04',
        'card_color' => '#1cae5d'
    ], [
        'card_icon' => 'icon-info',
        'card_text' => 'ETAPA 05',
        'card_color' => '#ff3f03'
    ]];
    return $KAN;
}

function getRateFeedback($Prio = null)
{
    $TypePrio = [
        1 => 'péssimo',
        2 => 'ruim',
        3 => 'mediano',
        4 => 'bom',
        5 => 'ótimo'
    ];
    if (!empty($Prio)) :
        return $TypePrio[$Prio];
    else :
        return $TypePrio;
    endif;
}

function ajusteValores($origem): float
{
    // Normaliza entrada
    if ($origem === null) return 0.0;
    $s = trim((string)$origem);
    if ($s === '') return 0.0;

    // Remove rótulos e unidades mais comuns
    $s = str_ireplace(['R$', 'R', '$', 'm²', 'm2', 'm', ' '], '', $s);
    // Mantém só dígitos, . e , (remove letras, hífens, etc.)
    $s = preg_replace('/[^0-9\.,]/', '', $s);
    if ($s === '' || $s === ',' || $s === '.') return 0.0;

    // Se tiver vírgula, tratamos vírgula como separador decimal (pt-BR).
    if (strpos($s, ',') !== false) {
        // Remove separadores de milhar "." e troca vírgula por ponto
        $s = str_replace('.', '', $s);
        $s = str_replace(',', '.', $s);
        return (float)$s;
    }

    // Sem vírgula: assume inteiro (sem centavos). Remove qualquer ponto sobrando.
    $s = preg_replace('/\D/', '', $s);
    if ($s === '') return 0.0;

    return (float)$s;
}

function ajusteFotoPerfil($user_thumb)
{
    $localPath = "../uploads/{$user_thumb}";
    $fallbackURL = "https://www.resicore.com.br/uploads/{$user_thumb}";
    $fallbackURL2 = "https://www.resih.com.br/uploads/{$user_thumb}";

    if (remoteFileExists($fallbackURL)) {
        return $fallbackURL;
    } elseif (remoteFileExists($fallbackURL2)) {
        return $fallbackURL2;
    } else {
        return "admin/_img/no_avatar.jpg";
    }
}

function ajusteFotoCurso($course_cover)
{
    $localPath = "../uploads/{$course_cover}";
    $fallbackURL = "https://www.resih.com.br/uploads/{$course_cover}";
    $fallbackURL2 = "https://www.resicore.com.br/uploads/{$course_cover}";

    if (remoteFileExists($fallbackURL)) {
        return $fallbackURL;
    } elseif (remoteFileExists($fallbackURL2)) {
        return $fallbackURL2;
    } else {
        return "admin/_img/no_image.jpg";
    }
}


function remoteFileExists($url)
{
    $headers = @get_headers($url);
    return is_array($headers) && strpos($headers[0], '200') !== false;
}

/**
 * Retorna a versão para assets (CSS, JS, etc.)
 *
 * - Se você passar uma versão (ex: "1.0.0"), ela será usada.
 * - Se não passar nada, retorna o timestamp atual (time()) para forçar o navegador a não usar cache.
 *
 * @param string|null $v Versão opcional
 * @return string
 */
function assetVersion($v = null)
{
    return $v ?? time();
}

define('APP_LAUDOS', 1);
define('LEVEL_LAUDOS', 6);
define('DB_LAUDOS', 'laudos');
define('DB_LAUDOS_DOC', 'laudos_documentos');
define('DB_LAUDOS_REF', 'laudos_referencias');

function safeDiv($num, $den)
{
    // Se $num não for numérico ou não for finito, zera
    if (!is_numeric($num) || is_infinite($num) || is_nan($num)) {
        $num = 0;
    }
    // Se $den não for numérico, infinito, NaN ou zero → invalida
    if (!is_numeric($den) || is_infinite($den) || is_nan($den) || $den == 0) {
        return 0;
    }
    return $num / $den;
}

function envioZapResidere($destino)
{
    $url = "https://evolution.zapidere.com.br/message/sendText/RESIDERE";
    $headers = [
        "Content-Type: application/json",
        "apikey: 429683C4C977415CAAFCCE10F7D57E11"
    ];
    $payload = [
        "number" => "{$destino["numero"]}@s.whatsapp.net",
        "text"   => $destino["mensagem"]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = (array) json_decode(curl_exec($ch));
    curl_close($ch);
    return $response;
}

function envioZapParceiro($destino)
{
    $url = "https://evolution.zapidere.com.br/message/sendText/Parceiros";
    $headers = [
        "Content-Type: application/json",
        "apikey: 429683C4C977415CAAFCCE10F7D57E11"
    ];
    $payload = [
        "number" => "{$destino["numero"]}@s.whatsapp.net",
        "text"   => $destino["mensagem"]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = (array) json_decode(curl_exec($ch));
    curl_close($ch);
    return $response;
}

function selAcabamento($type)
{
    $txt = mb_strtolower($type);
    if (
        strripos($txt, "básico") !== false ||
        strripos($txt, "basico") !== false ||
        strripos($txt, "comercial") !== false

    ) {
        return 1;
    } elseif (
        strripos($txt, "médio") !== false ||
        strripos($txt, "medio") !== false ||
        strripos($txt, "normal") !== false
    ) {
        return 2;
    } elseif (
        strripos($txt, "alto") !== false
    ) {
        return 3;
    }
    return $type;
}

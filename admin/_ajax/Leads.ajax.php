<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_USERS;

if (empty($_SESSION['userLoginParceiros']) || empty($_SESSION['userLoginParceiros']['user_level']) || $_SESSION['userLoginParceiros']['user_level'] < $NivelAcess) :
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b><br> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Leads';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack) :
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    $Read = new Read;
    $Create = new Create;
    $Update = new Update;
    $Delete = new Delete;
    $Upload = new Upload('../../uploads/');

    //SELECIONA AÇÃO
    switch ($Case):
        case 'manager':
            $RegId = $PostData['leads_id'];
            $PostData['leads_level'] = 2;
            unset($PostData['leads_id'], $PostData['leads_thumb'], $PostData['conjuge_thumb']);

            if (isset($PostData['leads_datebirth'])) :
                $PostData['leads_datebirth'] = (!empty($PostData['leads_datebirth']) ? Check::Nascimento($PostData['leads_datebirth']) : null);
            endif;

            if (isset($PostData['leads_status'])) :
                $PostData['leads_status'] = (!empty($PostData['leads_status']) ? '1' : '0');
            endif;

            if (isset($PostData['leads_cell'])):
                $PostData['leads_cell'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_cell']);
            endif;

            if (isset($PostData['leads_telephone'])):
                $PostData['leads_telephone'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_telephone']);
            endif;

            $Update->ExeUpdate(DB_LEADS, $PostData, "WHERE leads_id = :id", "id={$RegId}");
            $jSON['trigger'] = AjaxErro("<b>REGISTRO ATUALIZADO COM SUCESSO!</b>");

            if ($PostData['leads_name']):
                //Webhook PipeDrive
                $url = 'https://n8n-webhook.zapidere.com.br/webhook/cadastralead';
                $url .= "?lead={$RegId}";

                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                    ));

                    $response = (array) json_decode(curl_exec($curl), true);
                    curl_close($curl);
                } catch (Exception $e) {
                    $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO:</b><br>" . $e->getMessage(), E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                }
            endif;
            $jSON['redirect'] = "dashboard.php?wc=leads/home";
            break;

        case 'delete':
            $RegId = $PostData['del_id'];
            $Read->ExeRead(DB_LEADS, "WHERE leads_id = :user", "user={$RegId}");
            if (!$Read->getResult()) :
                $jSON['trigger'] = AjaxErro("<b>REGISTRO NÃO EXISTE:</b><br>Você tentou deletar um registro que não existe ou já foi removido!", E_USER_WARNING);
            else :
                extract($Read->getResult()[0]);

                if (file_exists("../../uploads/{$leads_thumb}") && !is_dir("../../uploads/{$leads_thumb}")) :
                    unlink("../../uploads/{$leads_thumb}");
                endif;

                $Delete->ExeDelete(DB_LEADS, "WHERE leads_id = :user", "user={$leads_id}");
                $jSON['trigger'] = AjaxErro("<b>REGISTRO REMOVIDO COM SUCESSO!</b>");
                $jSON['redirect'] = "dashboard.php?wc=leads/home";
            endif;
            break;
    endswitch;

    //RETORNA O CALLBACK
    if ($jSON) :
        echo json_encode($jSON);
    else :
        $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b><br> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else :
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;

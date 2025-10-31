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
$CallBack = 'Leads2';
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
        case 'filtro':
            if (!isset($PostData["perdido"])):
                $lnk1 = "0";
            else:
                $lnk1 = "1";
            endif;
            if (!isset($PostData["aberto"])):
                $lnk2 = "0";
            else:
                $lnk2 = "1";
            endif;
            if (!isset($PostData["ganho"])):
                $lnk3 = "0";
            else:
                $lnk3 = "1";
            endif;
            $jSON['redirect'] = "dashboard.php?wc=leads/home&fil={$lnk1}-{$lnk2}-{$lnk3}";
            break;

           case 'manager':
                
                // Detecta edição ou novo
                $LeadId = !empty($PostData['leads_id']) ? (int) $PostData['leads_id'] : null;
                $isUpdate = ($LeadId > 0);
                
                // Remove campos de controle
                unset($PostData['callback'], $PostData['callback_action'], $PostData['leads_id']);
                
                // ⚠️⚠️⚠️ VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS
                $errors = [];
                
                if (empty($PostData['leads_name'])) {
                    $errors[] = 'Nome completo é obrigatório';
                }
                
                if (empty($PostData['leads_cell'])) {
                    $errors[] = 'Telefone é obrigatório';
                }
                
                if (empty($PostData['leads_cidade_interesse'])) {
                    $errors[] = 'Cidade de interesse é obrigatória';
                }
                
                if (empty($PostData['leads_terreno'])) {
                    $errors[] = 'Informação sobre terreno é obrigatória';
                }
                
                if (empty($PostData['leads_tipo_construcao'])) {
                    $errors[] = 'Tipo de construção é obrigatório';
                }
                
                if (empty($PostData['leads_faixa_investimento'])) {
                    $errors[] = 'Faixa de investimento é obrigatória';
                }
                
                if (empty($PostData['leads_parcela_bolso'])) {
                    $errors[] = 'Parcela no bolso é obrigatória';
                }
                
                if (empty($PostData['leads_expectativa_inicio'])) {
                    $errors[] = 'Expectativa de início é obrigatória';
                }
                
                if (empty($PostData['leads_conhece_residere'])) {
                    $errors[] = 'Campo "Conhece Residere" é obrigatório';
                }
                
                if (empty($PostData['leads_visitou_casa'])) {
                    $errors[] = 'Campo "Visitou casa" é obrigatório';
                }
                
                if (empty($PostData['leads_finalidade_imovel'])) {
                    $errors[] = 'Finalidade do imóvel é obrigatória';
                }
                
                if (empty($PostData['leads_prazo_contato'])) {
                    $errors[] = 'Prazo de contato é obrigatório';
                }
                
                if (empty($PostData['leads_credito_aprovado'])) {
                    $errors[] = 'Campo "Crédito aprovado" é obrigatório';
                }
                
                // ⚠️ Se houver erros, retorna
                if (!empty($errors)) {
                    $jSON['error'] = true;
                    $jSON['trigger'] = AjaxErro("<b class='icon-warning'>ERRO:</b><br>" . implode('<br>', $errors), E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                }
                
                // Campos que só vão no INSERT
                if (!$isUpdate) {
                    $PostData['leads_level'] = 2;
                    $PostData['leads_status'] = 1;
                    $PostData['leads_criacao'] = date('Y-m-d H:i:s');
                    $PostData['leads_data_envio'] = date('Y-m-d H:i:s');
                    $PostData['leads_etapa_atual'] = 'wizard';
                    $PostData['leads_origem'] = 'form_modal';
                }

                // Limpa telefone e celular
                if (!empty($PostData['leads_cell'])):
                    $PostData['leads_cell'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_cell']);
                endif;

                if (!empty($PostData['leads_telephone'])):
                    $PostData['leads_telephone'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_telephone']);
                endif;

                // Converte array de formas de pagamento
                if (!empty($PostData['leads_forma_pagamento']) && is_array($PostData['leads_forma_pagamento'])):
                    $PostData['leads_forma_pagamento'] = json_encode($PostData['leads_forma_pagamento']);
                endif;

                unset($PostData['leads_datebirth']);

                // Converte faixa de investimento
                if (!empty($PostData['leads_faixa_investimento'])) {
                    switch ($PostData['leads_faixa_investimento']) {
                        case 'ate_150k':
                            $PostData['faixa_preco_min'] = 0;
                            $PostData['faixa_preco_max'] = 150000;
                            break;
                        case '150k_250k':
                            $PostData['faixa_preco_min'] = 150000;
                            $PostData['faixa_preco_max'] = 250000;
                            break;
                        case '250k_400k':
                            $PostData['faixa_preco_min'] = 250000;
                            $PostData['faixa_preco_max'] = 400000;
                            break;
                        case '400k_mais':
                            $PostData['faixa_preco_min'] = 400000;
                            $PostData['faixa_preco_max'] = null;
                            break;
                    }
                }

                // Converte parcela
                if (!empty($PostData['leads_parcela_bolso'])) {
                    switch ($PostData['leads_parcela_bolso']) {
                        case 'ate_1k':
                            $PostData['parcela_min'] = 0;
                            $PostData['parcela_max'] = 1000;
                            break;
                        case '1k_2k':
                            $PostData['parcela_min'] = 1000;
                            $PostData['parcela_max'] = 2000;
                            break;
                        case '2k_3k':
                            $PostData['parcela_min'] = 2000;
                            $PostData['parcela_max'] = 3000;
                            break;
                        case '3k_5k':
                            $PostData['parcela_min'] = 3000;
                            $PostData['parcela_max'] = 5000;
                            break;
                        case '5k_mais':
                            $PostData['parcela_min'] = 5000;
                            $PostData['parcela_max'] = null;
                            break;
                    }
                }

                // INSERT ou UPDATE
                if ($isUpdate) {
                    $Update = new Update;
                    $Update->ExeUpdate(DB_LEADS, $PostData, "WHERE leads_id = :id", "id={$LeadId}");
                    
                    if (!$Update->getResult()):
                        $jSON['error'] = true;
                        $jSON['trigger'] = AjaxErro("<b class='icon-warning'>ERRO:</b> Falha ao atualizar o lead.", E_USER_WARNING);
                        echo json_encode($jSON);
                        return;
                    endif;
                    
                    $RegId = $LeadId;
                    $jSON['trigger'] = AjaxErro("<b>LEAD ATUALIZADO COM SUCESSO!</b>");
                    
                } else {
                    $Create = new Create;
                    $Create->ExeCreate(DB_LEADS, $PostData);
                    $RegId = $Create->getResult();

                    if (!$RegId):
                        $jSON['error'] = true;
                        $jSON['trigger'] = AjaxErro("<b class='icon-warning'>ERRO:</b> Falha ao salvar o lead.", E_USER_WARNING);
                        echo json_encode($jSON);
                        return;
                    endif;
                    
                    $jSON['trigger'] = AjaxErro("<b>LEAD CADASTRADO COM SUCESSO!</b>");
                }

                // Upload de arquivos
                if (!empty($_FILES['file'])) :
                    $File = $_FILES['file'];
                    $gbFiles = [];
                    $gbCount = count($File['type']);
                    $gbKeys = array_keys($File);

                    for ($gb = 0; $gb < $gbCount; $gb++):
                        foreach ($gbKeys as $Keys):
                            $gbFiles[$gb][$Keys] = $File[$Keys][$gb];
                        endforeach;
                    endfor;

                    foreach ($gbFiles as $gbLoop => $UploadFile):
                        $gbLoop++;
                        $Upload->Image($UploadFile, "lead_{$RegId}_{$gbLoop}_" . time(), 1200, 'leads');

                        if ($Upload->getResult()):
                            $gbCreate = [
                                'parceiros_id' => $RegId,
                                'file' => $Upload->getResult()
                            ];
                            $Create->ExeCreate(DB_LEADS_DOC, $gbCreate);
                        endif;
                    endforeach;
                endif;

                $jSON['success'] = true;
                echo json_encode($jSON);
                return;
            break;

            case 'load':
                $LeadId = (int) $PostData['lead_id'];
                
                $Read = new Read;
                $Read->ExeRead(DB_LEADS, "WHERE leads_id = :id", "id={$LeadId}");
                
                if (!$Read->getResult()):
                    $jSON['error'] = "Lead não encontrado!";
                    echo json_encode($jSON);
                    return;
                endif;
                
                $jSON['lead'] = $Read->getResult()[0];
                echo json_encode($jSON);
                return;
            break;


        // case 'manager':
        //     $RegId = $PostData['leads_id'];
        //     $PostData['leads_level'] = 2;
        //     unset($PostData['leads_id'], $PostData['leads_thumb'], $PostData['conjuge_thumb']);

        //     if (isset($PostData['leads_datebirth'])) :
        //         $PostData['leads_datebirth'] = (!empty($PostData['leads_datebirth']) ? Check::Nascimento($PostData['leads_datebirth']) : null);
        //     endif;

        //     if (!isset($PostData['leads_status'])) :
        //         $PostData['leads_status'] = 1;
        //     endif;

        //     if (isset($PostData['leads_cell'])):
        //         $PostData['leads_cell'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_cell']);
        //     endif;

        //     if (isset($PostData['leads_telephone'])):
        //         $PostData['leads_telephone'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_telephone']);
        //     endif;

        //     $Update->ExeUpdate(DB_LEADS, $PostData, "WHERE leads_id = :id", "id={$RegId}");
        //     $jSON['trigger'] = AjaxErro("<b>REGISTRO ATUALIZADO COM SUCESSO!</b>");

        //     if ($PostData['leads_name']):
        //         //Webhook PipeDrive
        //         $url = 'https://n8n-webhook.zapidere.com.br/webhook/cadastralead';
        //         $url .= "?lead={$RegId}";

        //         try {
        //             $curl = curl_init();
        //             curl_setopt_array($curl, array(
        //                 CURLOPT_URL => $url,
        //                 CURLOPT_RETURNTRANSFER => true,
        //                 CURLOPT_ENCODING => '',
        //                 CURLOPT_MAXREDIRS => 10,
        //                 CURLOPT_TIMEOUT => 0,
        //                 CURLOPT_FOLLOWLOCATION => true,
        //                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //                 CURLOPT_CUSTOMREQUEST => 'POST',
        //             ));

        //             $response = (array) json_decode(curl_exec($curl), true);
        //             curl_close($curl);
        //         } catch (Exception $e) {
        //             $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO:</b><br>" . $e->getMessage(), E_USER_WARNING);
        //             echo json_encode($jSON);
        //             return;
        //         }
        //     endif;
        //     $jSON['redirect'] = "dashboard.php?wc=leads/home";
        //     break;

        case 'delete':
            $RegId = $PostData['id'];
            $Read->ExeRead(DB_LEADS, "WHERE leads_id = :user", "user={$RegId}");
            if (!$Read->getResult()) :
                $jSON['trigger'] = AjaxErro("<b>REGISTRO NÃO EXISTE:</b><br>Você tentou deletar um registro que não existe ou já foi removido!", E_USER_WARNING);
            else :
                extract($Read->getResult()[0]);
                $Update->ExeUpdate(DB_LEADS, ["leads_status" => '0'], "WHERE leads_id = :id", "id={$RegId}");

                //Webhook PipeDrive
                $url = 'https://n8n-webhook.zapidere.com.br/webhook/lead-perdido-painel';
                $url .= "?lead={$RegId}";

                //sleep(3);

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

                //$Delete->ExeDelete(DB_LEADS, "WHERE leads_id = :user", "user={$leads_id}");
                $jSON['trigger'] = AjaxErro("<b>REGISTRO REMOVIDO COM SUCESSO!</b>");
                $jSON['redirect'] = "dashboard.php?wc=leads/home";
            endif;
            break;

        case 'reativar':
            $RegId = $PostData['id'];
            $Read->ExeRead(DB_LEADS, "WHERE leads_id = :user", "user={$RegId}");
            if (!$Read->getResult()) :
                $jSON['trigger'] = AjaxErro("<b>REGISTRO NÃO EXISTE:</b><br>Você tentou deletar um registro que não existe ou já foi removido!", E_USER_WARNING);
            else :
                extract($Read->getResult()[0]);

                $Update->ExeUpdate(DB_LEADS, ["leads_status" => 1], "WHERE leads_id = :id", "id={$RegId}");

                //Webhook PipeDrive
                $url = 'https://n8n-webhook.zapidere.com.br/webhook/lead-perdido-painel';
                $url .= "?lead={$RegId}";

                //sleep(3);

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

                //$Delete->ExeDelete(DB_LEADS, "WHERE leads_id = :user", "user={$leads_id}");
                $jSON['trigger'] = AjaxErro("<b>REGISTRO REATIVADO COM SUCESSO!</b>");
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

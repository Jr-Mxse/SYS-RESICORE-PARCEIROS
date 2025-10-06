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

            if (isset($PostData['conjuge_datebirth'])) :
                $PostData['conjuge_datebirth'] = (!empty($PostData['conjuge_datebirth']) ? Check::Nascimento($PostData['conjuge_datebirth']) : null);
            endif;

            if (isset($_FILES['leads_thumb'])) :
                if (!empty($_FILES['leads_thumb'])) :
                    $UserThumb = $_FILES['leads_thumb'];
                    $Read->FullRead("SELECT leads_thumb FROM " . DB_LEADS . " WHERE leads_id = :id", "id={$RegId}");
                    if ($Read->getResult()) :
                        if (file_exists("../../uploads/{$Read->getResult()[0]['leads_thumb']}") && !is_dir("../../uploads/{$Read->getResult()[0]['leads_thumb']}")) :
                            unlink("../../uploads/{$Read->getResult()[0]['leads_thumb']}");
                        endif;
                    endif;

                    $Upload->Image($UserThumb, $RegId . "-" . Check::Name($PostData['leads_name'] . $PostData['leads_lastname']) . '-' . time(), 600);
                    if ($Upload->getResult()) :
                        $PostData['leads_thumb'] = $Upload->getResult();
                    else :
                        $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR FOTO:</b><br>Selecione uma imagem JPG ou PNG para enviar como foto!", E_USER_WARNING);
                        echo json_encode($jSON);
                        return;
                    endif;
                endif;
            endif;

            if (isset($PostData['leads_password'])) :
                if (!empty($PostData['leads_password'])) :
                    if (strlen($PostData['leads_password']) >= 5) :
                        $PostData['leads_password'] = hash('sha512', $PostData['leads_password']);
                    else :
                        $jSON['trigger'] = AjaxErro("<b>ERRO DE SENHA:</b><br>A senha deve ter no mínimo 5 caracteres para ser redefinida!", E_USER_WARNING);
                        echo json_encode($jSON);
                        return;
                    endif;
                else :
                    unset($PostData['leads_password']);
                endif;
            endif;

            if (isset($PostData['leads_status'])) :
                $PostData['leads_status'] = (!empty($PostData['leads_status']) ? '1' : '0');
            endif;

            if (isset($PostData['leads_renda'])) :
                if (!empty($PostData['leads_renda'])) :
                    if (strpos($PostData['leads_renda'], ",") && strpos($PostData['leads_renda'], ".")) :
                        $PostData['leads_renda'] = str_replace(",", ".", str_replace(".", "", $PostData['leads_renda']));
                    elseif (strpos($PostData['leads_renda'], ",") && !strpos($PostData['leads_renda'], ".")) :
                        $PostData['leads_renda'] = str_replace(",", ".", $PostData['leads_renda']);
                    endif;
                else :
                    $PostData['leads_renda'] = null;
                endif;
            endif;

            if (isset($PostData['leads_patrimonio'])) :
                if (!empty($PostData['leads_patrimonio'])) :
                    if (strpos($PostData['leads_patrimonio'], ",") && strpos($PostData['leads_patrimonio'], ".")) :
                        $PostData['leads_patrimonio'] = str_replace(",", ".", str_replace(".", "", $PostData['leads_patrimonio']));
                    elseif (strpos($PostData['leads_patrimonio'], ",") && !strpos($PostData['leads_patrimonio'], ".")) :
                        $PostData['leads_patrimonio'] = str_replace(",", ".", $PostData['leads_patrimonio']);
                    endif;
                else :
                    $PostData['leads_patrimonio'] = null;
                endif;
            endif;

             if (isset($PostData['conjuge_renda'])) :
                if (!empty($PostData['conjuge_renda'])) :
                    if (strpos($PostData['conjuge_renda'], ",") && strpos($PostData['conjuge_renda'], ".")) :
                        $PostData['conjuge_renda'] = str_replace(",", ".", str_replace(".", "", $PostData['conjuge_renda']));
                    elseif (strpos($PostData['conjuge_renda'], ",") && !strpos($PostData['conjuge_renda'], ".")) :
                        $PostData['conjuge_renda'] = str_replace(",", ".", $PostData['conjuge_renda']);
                    endif;
                else :
                    $PostData['conjuge_renda'] = null;
                endif;
            endif;

            if (isset($PostData['conjuge_patrimonio'])) :
                if (!empty($PostData['conjuge_patrimonio'])) :
                    if (strpos($PostData['conjuge_patrimonio'], ",") && strpos($PostData['conjuge_patrimonio'], ".")) :
                        $PostData['conjuge_patrimonio'] = str_replace(",", ".", str_replace(".", "", $PostData['conjuge_patrimonio']));
                    elseif (strpos($PostData['conjuge_patrimonio'], ",") && !strpos($PostData['conjuge_patrimonio'], ".")) :
                        $PostData['conjuge_patrimonio'] = str_replace(",", ".", $PostData['conjuge_patrimonio']);
                    endif;
                else :
                    $PostData['conjuge_patrimonio'] = null;
                endif;
            endif;

            if (isset($PostData['leads_cell'])):
                $PostData['leads_cell'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_cell']);
            endif;

            if (isset($PostData['leads_telephone'])):
                $PostData['leads_telephone'] = str_replace(["(", ")", "-", " "], "", $PostData['leads_telephone']);
            endif;

            if (isset($_FILES['conjuge_thumb'])) :
                if (!empty($_FILES['conjuge_thumb'])) :
                    $conjugeThumb = $_FILES['conjuge_thumb'];
                    $Read->FullRead("SELECT conjuge_thumb FROM " . DB_LEADS . " WHERE leads_id = :id", "id={$RegId}");
                    if ($Read->getResult()) :
                        if (file_exists("../../uploads/{$Read->getResult()[0]['conjuge_thumb']}") && !is_dir("../../uploads/{$Read->getResult()[0]['conjuge_thumb']}")) :
                            unlink("../../uploads/{$Read->getResult()[0]['conjuge_thumb']}");
                        endif;
                    endif;

                    $Upload->Image($conjugeThumb, $RegId . "-" . Check::Name($PostData['conjuge_name'] . $PostData['conjuge_lastname']) . '-' . time(), 600);
                    if ($Upload->getResult()) :
                        $PostData['conjuge_thumb'] = $Upload->getResult();
                    else :
                        $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR FOTO:</b><br>Selecione uma imagem JPG ou PNG para enviar como foto!", E_USER_WARNING);
                        echo json_encode($jSON);
                        return;
                    endif;
                endif;
            endif;

            if (isset($PostData['conjuge_cell'])):
                $PostData['conjuge_cell'] = str_replace(["(", ")", "-", " "], "", $PostData['conjuge_cell']);
            endif;

            if (isset($PostData['conjuge_telephone'])):
                $PostData['conjuge_telephone'] = str_replace(["(", ")", "-", " "], "", $PostData['conjuge_telephone']);
            endif;

            $Update->ExeUpdate(DB_LEADS, $PostData, "WHERE leads_id = :id", "id={$RegId}");
            $jSON['trigger'] = AjaxErro("<b>REGISTRO ATUALIZADO COM SUCESSO!</b>");
            break;

        case 'delete':
            $RegId = $PostData['del_id'];
            $Read->ExeRead(DB_LEADS, "WHERE leads_id = :user", "user={$RegId}");
            if (!$Read->getResult()) :
                $jSON['trigger'] = AjaxErro("<b>REGISTRO NÃO EXISTE:</b><br>Você tentou deletar um registro que não existe ou já foi removido!", E_USER_WARNING);
            else :
                extract($Read->getResult()[0]);
                $Delete->ExeDelete(DB_LEADS_ADDR, "WHERE leads_id = :user", "user={$leads_id}");

                if (file_exists("../../uploads/{$leads_thumb}") && !is_dir("../../uploads/{$leads_thumb}")) :
                    unlink("../../uploads/{$leads_thumb}");
                endif;

                $Delete->ExeDelete(DB_LEADS, "WHERE leads_id = :user", "user={$leads_id}");
                $jSON['trigger'] = AjaxErro("<b>REGISTRO REMOVIDO COM SUCESSO!</b>");
                $jSON['redirect'] = "dashboard.php?wc=leads/home";
            endif;
            break;

        case 'addr_manage':
            $AddrId = $PostData['addr_id'];
            $especial = $PostData['especial'];
            unset($PostData['addr_id'], $PostData['especial']);

            $Update->ExeUpdate(DB_LEADS_ADDR, $PostData, "WHERE addr_id = :id", "id={$AddrId}");
            $jSON['trigger'] = AjaxErro("<b>REGISTRO ATUALIZADO COM SUCESSO!</b>");
            if(isset($especial)):
                $jSON['redirect'] = "dashboard.php?wc=leads/create&id={$PostData['leads_id']}#address";
            endif;
            break;

        case 'addr_delete':
            $Read->FullRead("SELECT leads_id FROM " . DB_LEADS_ADDR . " WHERE addr_id={$PostData['del_id']}", "");
            if ($Read->getResult()):
                $PostData['leads_id'] = $Read->getResult()[0]['leads_id'];
                $Delete->ExeDelete(DB_LEADS_ADDR, "WHERE addr_id = :id", "id={$PostData['del_id']}");
                $jSON['redirect'] = "dashboard.php?wc=leads/create&id={$PostData['leads_id']}#address";
            else:
                $jSON['trigger'] = AjaxErro("<b class='icon-warning'>OPSS:</b> Desculpe, mas você tentou excluir um registro que não existe ou que foi removido recentemente!", E_USER_WARNING);
            endif;
            break;

        case 'cnpj_manage':
            $CnpjId = $PostData['cnpj_id'];
            unset($PostData['cnpj_id']);

            $Update->ExeUpdate(DB_LEADS_CNPJ, $PostData, "WHERE cnpj_id = :id", "id={$CnpjId}");
            $jSON['trigger'] = AjaxErro("<b>REGISTRO ATUALIZADO COM SUCESSO!</b>");
            break;

        case 'cnpj_delete':
            $Read->FullRead("SELECT leads_id FROM " . DB_LEADS_CNPJ . " WHERE cnpj_id={$PostData['del_id']}", "");
            if ($Read->getResult()):
                $PostData['leads_id'] = $Read->getResult()[0]['leads_id'];
                $Delete->ExeDelete(DB_LEADS_CNPJ, "WHERE cnpj_id = :id", "id={$PostData['del_id']}");
                $jSON['redirect'] = "dashboard.php?wc=leads/create&id={$PostData['leads_id']}#empresas";
            else:
                $jSON['trigger'] = AjaxErro("<b class='icon-warning'>OPSS:</b> Desculpe, mas você tentou excluir um registro que não existe ou que foi removido recentemente!", E_USER_WARNING);
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

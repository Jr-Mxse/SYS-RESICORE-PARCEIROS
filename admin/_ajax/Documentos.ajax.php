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
$CallBack = 'Documentos';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
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
        case 'add':
            if (!empty($_FILES['file'])) :
                $CliId = $PostData['clientes_id'];
                $Read->ExeRead(DB_CLIENTES, "WHERE clientes_id = :id", "id={$CliId}");
                if (!$Read->getResult()) :
                    $jSON['trigger'] = AjaxErro("<b class='icon-warning'>Erro ao atualizar:</b> Desculpe {$_SESSION['userLoginParceiros']['user_name']}, mas não foi possível consultar o Leads. Experimente atualizar a página!", E_USER_WARNING);
                else :
                    unset($PostData['clientes_id'], $PostData['file']);

                    $File = $_FILES['file'];
                    $gbFile = array();
                    $gbCount = count($File['type']);
                    $gbKeys = array_keys($File);
                    $gbLoop = 0;

                    for ($gb = 0; $gb < $gbCount; $gb++) :
                        foreach ($gbKeys as $Keys) :
                            $gbFiles[$gb][$Keys] = $File[$Keys][$gb];
                        endforeach;
                    endfor;

                    $jSON['gallery'] = null;
                    foreach ($gbFiles as $UploadFile) :
                        $gbLoop++;
                        $Upload->Image($UploadFile, "{$CliId}-{$gbLoop}-" . time(), null, 'galeria');
                        if ($Upload->getResult()) :
                            $gbCreate = ['clientes_id' => $CliId, "file" => $Upload->getResult()];
                            $Create->ExeCreate(DB_CLIENTES_DOC, $gbCreate);
                        endif;
                    endforeach;

                    $jSON['redirect'] = "dashboard.php?wc=leads/create&id={$CliId}#galeria";
                endif;
            else :
                $jSON['trigger'] = AjaxErro("<b class='icon-file'>NENHUMA IMAGEM SELECIONADA:</b> Desculpe {$_SESSION['userLoginParceiros']['user_name']}, mas no mínimo selecione uma imagem!", E_USER_WARNING);
            endif;
            break;

        case 'ordem':
            if (is_array($PostData['Data'])) :
                foreach ($PostData['Data'] as $RE) :
                    $UpdateMod = ['file_order' => $RE[1]];
                    $Update->ExeUpdate(DB_CLIENTES_DOC, $UpdateMod, "WHERE id=:mod", "mod={$RE[0]}");
                endforeach;

                $jSON['sucess'] = true;
            endif;
            break;

        case 'delete':
            $Read->FullRead("SELECT file FROM " . DB_CLIENTES_DOC . " WHERE id = :id", "id={$PostData['del_id']}");
            if ($Read->getResult()) :
                $ImageRemove = "../../{$Read->getResult()[0]['file']}";
                if (file_exists($ImageRemove) && !is_dir($ImageRemove)) :
                    unlink($ImageRemove);
                endif;
                $Delete->ExeDelete(DB_CLIENTES_DOC, "WHERE id = :id", "id={$PostData['del_id']}");
                $jSON['success'] = true;
            endif;
            break;

        case 'doc_add':
            if (!empty($_FILES['file'])) :
                $CliId = $PostData['clientes_id'];
                $Read->ExeRead(DB_CLIENTES, "WHERE clientes_id = :id", "id={$CliId}");
                if (!$Read->getResult()) :
                    $jSON['trigger'] = AjaxErro("<b class='icon-warning'>Erro ao atualizar:</b> Desculpe {$_SESSION['userLoginParceiros']['user_name']}, mas não foi possível consultar o Leads. Experimente atualizar a página!", E_USER_WARNING);
                else :
                    unset($PostData['file']);

                    $UserDoc = $_FILES['file'];
                    $Upload->File($UserDoc, "{$CliId}-" . time(), null, 'documentos');
                    if ($Upload->getResult()):
                        $PostData['file'] = $Upload->getResult();
                    else:
                        $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR DOC</b>", E_USER_WARNING);
                        echo json_encode($jSON);
                        return;
                    endif;

                    $Create->ExeCreate(DB_CLIENTES_DOC, $PostData);
                    $jSON['redirect'] = "dashboard.php?wc=leads/create&id={$CliId}#documentos";
                endif;
            else :
                $jSON['trigger'] = AjaxErro("<b class='icon-file'>NENHUMA ARQUIVO SELECIONADO:</b>", E_USER_WARNING);
            endif;
            break;

            case 'doc_delete':
            $Read->FullRead("SELECT clientes_id FROM " . DB_CLIENTES_DOC . " WHERE id = :id", "id={$PostData['del_id']}");
            if ($Read->getResult()) :
                $CliId = $Read->getResult()[0]['clientes_id'];
                $Delete->ExeDelete(DB_CLIENTES_DOC, "WHERE id = :id", "id={$PostData['del_id']}");
                $jSON['redirect'] = "dashboard.php?wc=leads/create&id={$CliId}#documentos";
            endif;
            break;

    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;

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
$CallBack = 'Membro';
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

        case 'convite':
            if (isset($PostData['id'])) :
                $Content = ''
                    . '<form name="task" action="" method="post" enctype="multipart/form-data" id="mdl-' . time() . '">'
                    . '<input type="hidden" name="callback" value="Membro"/>'
                    . '<input type="hidden" name="callback_action" value="convidar"/>'
                    . "<input type='hidden' id='user_id' name='user_id' value='{$PostData['id']}'>"
                    . '<div class="box100">'
                    . '<label class="label">'
                    . '<span class="legend"><b>Nome do Integrante / Membro * </b></span>'
                    . "<input type='text' name='user_name' value='' required></label>"
                    . "</label>"
                    . '<label class="label">'
                    . '<span class="legend">E-mail</span>'
                    . "<input type='email' name='user_email' value=''></label>"
                    . "</label>"
                    . "<div class='label_50'>"
                    . '<label class="label">'
                    . '<span class="legend"><b>Whatsapp *</b></span>'
                    . "<input type='text' class='formPhone' name='user_cell' required/></label>"
                    . "</label>"
                    . "</div>"
                    . "<img class='form_load none' load='true' style='margin-left:10px;' alt='Enviando Requisição!' title='Enviando Requisição!' src='./_img/load.gif'/>"
                    . '</form>'
                    . "<div class='clear'></div>";

                $jSON['modal'] = [
                    'icon' => 'user-plus',
                    'size' => '',
                    'title' => 'Novo Integrante Equipe / Empresa',
                    'content' => $Content,
                    'footer' => "<div class='fl_right'><a class='btn btn_green btn-rounded j_sendFormModal'>CONVIDAR AGORA</a></div>",
                    'callback' => ['plugginMaskDefault', 'plugginTiny', 'plugginAutosize', 'plugginDatepicker', 'plugginSelect2']
                ];
            endif;
            break;

        case 'convidar':
            $sqlWhere = "";
            $PostData['user_cell'] = str_replace(["(", ")", " ", "-"], "", $PostData['user_cell']);

            if (isset($PostData['user_email'])):
                $sqlWhere = " OR user_email='{$PostData['user_email']}'";
            endif;

            $Read->ExeRead(DB_USERS, "WHERE user_cell={$PostData['user_cell']} {$sqlWhere}", "");
            if ($Read->getResult()) :
                if ($Read->getResult()[0]["user_associado"] == $PostData['user_id']):
                    $jSON['trigger'] = AjaxErro("<b>INTEGRANTE JÁ CADASTRADO:</b><br>Este Integrante já faz parte da sua equipe.", E_USER_WARNING);
                else:
                    $jSON['trigger'] = AjaxErro("<b>NÃO FOI POSSSÍVEL CONVIDAR:</b><br>Este E-mail ou Whatsapp já participa de uma Empresa ou Equipe", E_USER_WARNING);
                endif;
            else :
                $RegCreate = [
                    'user_associado' => $PostData['user_id'],
                    "user_email" => $PostData['user_email'],
                    "user_cell" => $PostData['user_cell'],
                    "user_name" => $PostData['user_name'],
                    "user_convite" => $_SESSION['userLoginParceiros']['user_id'],
                ];
                $Create->ExeCreate(DB_PARCEIROS_CONVITE, $RegCreate);
                $convite = $Create->getResult();

                $token = base64_encode($PostData['user_name'] . '-|-' . $PostData['user_email'] . '-|-' . $PostData['user_cell']);
                $tokenOrg = base64_encode($convite);
                $link = "https://painel.residere.com.br/admin/convite.php?tk={$token}&org={$tokenOrg}";

                $nome = explode(" ", $PostData['user_name'])[0];
                $destino["numero"] = "55" . $PostData['user_cell'];
                $destino["mensagem"] = "Bom dia {$nome}! Tudo bem?\nNosso parceiro {$_SESSION['userLoginParceiros']['user_name']} cadastrou você para participar de sua equipe em nosso Painel de Parceiros.\nPara ativar o seu cadastro, basta clicar no link abaixo e completar o seu cadastro.\nAo ativar, você terá acesso à todos os treinamentos e a Plataforma de Parceiros Residere.\n👉 {$link}\nFicamos à disposição para o que precisar.\nUm grande abraço,\nEquipe Grupo Residere";

                $envio = envioZapParceiro($destino);
                if ($envio["status"] == "PENDING"):
                    if ($_SESSION['userLoginParceiros']['user_cell']):
                        $nome2 = explode(" ", $_SESSION['userLoginParceiros']['user_name'])[0];
                        $destino2["numero"] = "55" . str_replace(["(", ")", " ", "-"], "", $_SESSION['userLoginParceiros']['user_cell']);
                        $destino2["mensagem"] = "Olá {$nome2}, você convidou {$nome} ({$destino["numero"]}) para se cadastrar como membro de sua equipe no Painel de Parceiros da Residere.";
                        $envio = envioZapParceiro($destino2);
                    endif;
                    $jSON['trigger'] = AjaxErro("<b>USUÁRIO CONVIDADO COM SUCESSO!</b>");
                    $jSON['redirect'] = "dashboard.php?wc=parceiros/rodas";
                else:
                    $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR CONVITE:</b><br>Whatsapp Não entregue", E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                endif;
            endif;
            break;

    /*
        case 'manager':
            $RegId = $PostData['user_id'];
            $PostData['user_level'] = 20;
            unset($PostData['user_id'], $PostData['user_thumb'], $PostData['conjuge_thumb']);

            if (isset($PostData['user_cell'])):
                $PostData['user_cell'] = str_replace(["(", ")", "-", " "], "", $PostData['user_cell']);
            endif;

            if (isset($PostData['user_telephone'])):
                $PostData['user_telephone'] = str_replace(["(", ")", "-", " "], "", $PostData['user_telephone']);
            endif;
            
            $Update->ExeUpdate(DB_USERS, $PostData, "WHERE user_id = :id", "id={$RegId}");
            $jSON['trigger'] = AjaxErro("<b>REGISTRO ATUALIZADO COM SUCESSO!</b>");
            break;

        case 'delete':
            $RegId = $PostData['del_id'];
            $Read->ExeRead(DB_USERS, "WHERE user_id = :user", "user={$RegId}");
            if (!$Read->getResult()) :
                $jSON['trigger'] = AjaxErro("<b>REGISTRO NÃO EXISTE:</b><br>Você tentou deletar um registro que não existe ou já foi removido!", E_USER_WARNING);
            else :
                extract($Read->getResult()[0]);

                if (file_exists("../../uploads/{$user_thumb}") && !is_dir("../../uploads/{$user_thumb}")) :
                    unlink("../../uploads/{$user_thumb}");
                endif;

                $Delete->ExeDelete(DB_USERS, "WHERE user_id = :user", "user={$user_id}");
                $jSON['trigger'] = AjaxErro("<b>REGISTRO REMOVIDO COM SUCESSO!</b>");
                $jSON['redirect'] = "dashboard.php?wc=organizacao/home";
            endif;
            break;
            */
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

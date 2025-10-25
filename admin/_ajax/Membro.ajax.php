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
                    'title' => 'Novo Integrante da minha Equipe / Empresa',
                    'content' => $Content,
                    'footer' => "<div class='fl_right'><a class='btn btn_green btn-rounded j_sendFormModal'>CONVIDAR AGORA</a></div>",
                    'callback' => ['plugginMaskDefault', 'plugginTiny', 'plugginAutosize', 'plugginDatepicker', 'plugginSelect2']
                ];
            endif;
            break;

        case 'convidar':
var_dump($PostData);
            /*
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
            $jSON['trigger'] = AjaxErro("<b>REGISTRO ATUALIZADO COM SUCESSO!</b>");*/
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

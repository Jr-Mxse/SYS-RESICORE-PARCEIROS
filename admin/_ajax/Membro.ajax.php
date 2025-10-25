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
                $Content = ''
                    . '<form name="task" action="" method="post" enctype="multipart/form-data" id="mdl-' . time() . '">'
                    . '<input type="hidden" name="callback" value="GerProjects"/>'
                    . '<input type="hidden" name="callback_action" value="taskNewUpdate"/>'
                    . "<input type='hidden' id='sel_cliente' name='sel_cliente' value='0'>"
                    . '<div class="box100">'
                    . '<div id="div_user" class="label_50">'
                    . '<label  class="label labelx_80">'
                    . '<span class="legend">Selecione o Cliente</span>'
                    . '<select name="user_id" class="select2">'
                    . "<option value=''></option>"
                    . "</select></label>"
                    . "<label class='label labelx_20'><span class='legend'>&nbsp;</span>"
                    . "<span id='bt_cli_add' class='icon-plus btn btn_green icon-notext' onclick='selCliente(1)'></span>"
                    . "</label></div>"
                    . '<div id="div_new" class="label_50" style="display: none">'
                    . '<label class="label labelx_80">'
                    . '<span class="legend">Nome do Cliente</span>'
                    . "<input type='text' name='new_name' value=''></label>"
                    . "<label class='label labelx_20'><span class='legend'>&nbsp;</span>"
                    . "<span id='bt_cli_list' class='icon-list btn btn_yellow icon-notext' onclick='selCliente(0)'></span>"
                    . "</label></div>"
                    . '<div id="div_new2" class="label_50" style="display: none">'
                    . '<label class="label">'
                    . '<span class="legend">E-mail</span>'
                    . "<input type='email' name='new_email' value=''></label>"
                    . '<label class="label">'
                    . '<span class="legend">Telefone</span>'
                    . "<input type='email' name='new_tel' value=''></label>"
                    . "</div>"
                    . '<label class="label">'
                    . '<span class="legend">Selecione o Template</span>'
                    . '<select name="temp_id" class="select2">'
                    . '<option value="">Nenhum Template</option>'
                    . '</select></label>'
                    . '</div>'
                    . "<img class='form_load none' load='true' style='margin-left:10px;' alt='Enviando Requisição!' title='Enviando Requisição!' src='./_img/load.gif'/>"
                    . '</form>'
                    . "<div class='clear'></div>";

                $jSON['modal'] = [
                    'icon' => 'move-down',
                    'size' => '',
                    'title' => 'Novo CARD',
                    'content' => $Content,
                    'footer' => "<div class='fl_right'><a class='btn btn_green btn-rounded j_sendFormModal'>CADASTRAR</a></div>",
                    'callback' => ['plugginTiny', 'plugginAutosize', 'plugginDatepicker', 'plugginSelect2']
                ];
            break;

        case 'convidar':
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

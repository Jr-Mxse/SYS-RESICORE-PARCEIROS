<?php

session_start();
require '../../_app/Config.inc.php';

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;
$Email = new Email;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Login';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    //ELIMINA CÓDIGOS
    $PostData = array_map('strip_tags', $PostData);

    //SELECIONA AÇÃO
    switch ($Case):
        case 'admin_ativar':
            $Read->ExeRead(DB_USERS, "WHERE user_email = '{$PostData["user_email"]}' OR user_cell = '{$PostData["user_cell"]}'", "");
            if (!$Read->getResult()):
                $PostData["user_cell"] = str_replace(["(", ")", " ", "-", ".", "/"], "", $PostData["user_cell"]);
                $PostData["user_document"] = str_replace(["(", ")", " ", "-", ".", "/"], "", $PostData["user_document"]);

                $pass = rand(1000, 9999999);
                $PostData["user_password"] = hash('sha512', $pass);
                $PostData["user_status"] = 1;

                if (!isset($PostData["especialista_id"]) || !$PostData["especialista_id"]):
                    $PostData["especialista_id"] = 44;
                endif;

                $Create->ExeCreate(DB_USERS, $PostData);
                if (!$Create->getResult()):
                    $jSON['trigger'] = AjaxErro('<b>ERRO AO CADASTRAR:</b> Tente novamente por favor, ou entre em contato com o Administrador!', E_USER_WARNING);
                else:
                    $user_id = $Create->getResult();
                    setcookie('resiparceiros', $PostData['user_email'], time() + 2592000, '/');
                    $Read->ExeRead(DB_USERS, "WHERE user_id='{$user_id}'", "");
                    $_SESSION['userLoginParceiros'] = $Read->getResult()[0];

                    $nome = explode(" ",  $PostData["user_name"])[0];
                    $destino["numero"] = "55" . $PostData["user_cell"];
                    //$destino["numero"] = "5521979158558";
                    //$destino["numero"] = "5518996653770";
                    $destino["mensagem"] = "Parabéns {$nome}!\n 

Agradecemos pela sua confiança e seu cadastro já está ativo. Segue sua senha que pode ser alterada a qualquer momento:\n
👉 {$pass}\n
Ficamos à disposição para o que precisar.\n
Um grande abraço,\n
Equipe Grupo Residere";

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

                    $response = curl_exec($ch);
                    if ($response === false) {
                        $msg = curl_error($ch);
                    } else {
                        $msg = $response;
                    }
                    curl_close($ch);
                    $jSON['trigger'] = AjaxErro("<b>Olá {$nome},</b> conta ativada com sucesso.");

                    if (isset($PostData['redirect']) && !empty($PostData['redirect'])):
                        $jSON['redirect'] = BASE2 . "/" . base64_decode($PostData['redirect']);
                    else:
                        $jSON['redirect'] = 'dashboard.php?wc=home';
                    endif;

                    //Webhook PipeDrive
                    $url = 'https://n8n-webhook.zapidere.com.br/webhook/cadastroparceiro';
                    $url .= "?parceiro={$user_id}&zap=0";

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
            else:
                $jSON['trigger'] = AjaxErro('<b>ERRO:</b> E-mail ou Celular já cadastrados!', E_USER_WARNING);
            endif;
            break;

        case 'admin_cadastro':
            $Read->ExeRead(DB_USERS, "WHERE user_email = '{$PostData["user_email"]}' OR user_cell = '{$PostData["user_cell"]}'", "");
            if (!$Read->getResult()):
                $PostData["user_cell"] = str_replace(["(", ")", " ", "-", ".", "/"], "", $PostData["user_cell"]);
                $PostData["user_document"] = str_replace(["(", ")", " ", "-", ".", "/"], "", $PostData["user_document"]);

                $Create->ExeCreate(DB_USERS, $PostData);
                if (!$Create->getResult()):
                    $jSON['trigger'] = AjaxErro('<b>ERRO AO CADASTRAR:</b> Tente novamente por favor, ou entre em contato com o Administrador!', E_USER_WARNING);
                else:
                    $user_id = $Create->getResult();
                    $Read->ExeRead(DB_USERS, "WHERE user_id='{$user_id}'", "");
                    $_SESSION['userLoginParceiros'] = $Read->getResult()[0];

                    $nome = explode(" ",  $PostData["user_name"])[0];
                    $destino["numero"] = "55" . $PostData["user_cell"];
                    //$destino["numero"] = "5521979158558";
                    //$destino["numero"] = "5518996653770";
                    $destino["mensagem"] = "Parabéns {$nome}!\n 
Agradecemos pela sua confiança e seu cadastro já está em análise de nossa equipe de especialistas:\n
Ficamos à disposição para o que precisar.\n
Um grande abraço,\n
Equipe Grupo Residere";

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

                    $response = curl_exec($ch);
                    if ($response === false) {
                        $msg = curl_error($ch);
                    } else {
                        $msg = $response;
                    }
                    curl_close($ch);

                    $jSON['trigger'] = AjaxErro("<b>Olá {$nome},</b> cadastro realizado com sucesso. Em breve entraremos em contato.");
                    $jSON['redirect'] = 'https://painel.residere.com.br';

                    //Webhook PipeDrive
                    $url = 'https://n8n-webhook.zapidere.com.br/webhook/cadastroparceiro';
                    $url .= "?parceiro={$user_id}&zap=1";

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
            else:
                $jSON['trigger'] = AjaxErro('<b>ERRO:</b> E-mail ou Celular já cadastrados!', E_USER_WARNING);
            endif;
            break;

        case 'admin_login':
            if (in_array('', $PostData)):
                $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Informe seu e-mail e senha para logar!', E_USER_NOTICE);
            else:
                if (!Check::Email($PostData['user_email']) || !filter_var($PostData['user_email'], FILTER_VALIDATE_EMAIL)):
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> E-mail informado não é válido!', E_USER_NOTICE);
                elseif (strlen($PostData['user_password']) < 5):
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Senha informada não é compatível!', E_USER_NOTICE);
                else:
                    $Read->FullRead("SELECT user_id FROM " . DB_USERS . " WHERE user_email = :email", "email={$PostData['user_email']}");
                    if (!$Read->getResult()):
                        $jSON['trigger'] = AjaxErro('<b>ERRO:</b> E-mail informado não é cadastrado!', E_USER_WARNING);
                    else:
                        //CRIPTIGRAFA A SENHA
                        $PostData['user_password'] = hash('sha512', $PostData['user_password']);

                        $Read->FullRead("SELECT user_id FROM " . DB_USERS . " WHERE user_email = :email AND user_password = :pass", "email={$PostData['user_email']}&pass={$PostData['user_password']}");
                        if (!$Read->getResult()):
                            $jSON['trigger'] = AjaxErro('<b>ERRO:</b> E-mail e senha não conferem!', E_USER_ERROR);
                        else:
                            $Read->ExeRead(DB_USERS, "WHERE user_email = :email AND user_password = :pass AND user_status=1 AND user_level >= :level", "email={$PostData['user_email']}&pass={$PostData['user_password']}&level=6");
                            if (!$Read->getResult()):
                                $jSON['trigger'] = AjaxErro('<b>ERRO:</b> Você não tem permissão para acessar o painel!', E_USER_ERROR);
                            else:
                                $Remember = (isset($PostData['user_remember']) ? 1 : null);
                                if ($Remember):
                                    setcookie('resiparceiros', $PostData['user_email'], time() + 2592000, '/');
                                else:
                                    setcookie('resiparceiros', '', 60, '/');
                                endif;

                                $wc_ead_login_cookie = hash("sha512", time());

                                $UpdateUserLogin = ['user_lastaccess' => date('Y-m-d H:i:s'), 'user_login' => time(), 'user_login_cookie' => $wc_ead_login_cookie];
                                $Update->ExeUpdate(DB_USERS, $UpdateUserLogin, "WHERE user_id = :user", "user={$Read->getResult()[0]['user_id']}");

                                $_SESSION['userLoginParceiros'] = $Read->getResult()[0];
                                $jSON['trigger'] = AjaxErro("<b>Olá {$Read->getResult()[0]['user_name']},</b> Seja bem-vindo(a) de volta!");

                                if (isset($PostData['redirect']) && !empty($PostData['redirect'])):
                                    $jSON['redirect'] = BASE2 . "/" . base64_decode($PostData['redirect']);
                                else:
                                    $jSON['redirect'] = 'dashboard.php?wc=home';
                                endif;
                            endif;
                        endif;
                    endif;
                endif;
            endif;
            break;

        case 'admin_recover':
            if (isset($PostData['user_email']) && Check::Email($PostData['user_email']) && filter_var($PostData['user_email'], FILTER_VALIDATE_EMAIL)):
                $Read->FullRead("SELECT user_id, user_name, user_email, user_password FROM " . DB_USERS . " WHERE user_email = :email AND user_level >= :level", "email={$PostData['user_email']}&level=6");
                if (!$Read->getResult()):
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> E-mail não cadastrado ou não tem permissão para o painel!', E_USER_WARNING);
                else:
                    $CodeReset = "user_id={$Read->getResult()[0]['user_id']}&user_email={$Read->getResult()[0]['user_email']}&user_password={$Read->getResult()[0]['user_password']}";
                    $CodePass = base64_encode($CodeReset);

                    require '../_tpl/Mail.email.php';
                    $BodyMail = "
                    <p style='font-size: 1.5em;'>Olá {$Read->getResult()[0]['user_name']}, recupere sua senha do " . ADMIN_NAME . "!</p>
                    <p>Caso não tenha feito essa solicitação. Por favor ignore esse e-mail e nenhuma ação será tomada quanto aos dados de acesso!</p>
                    <p>Ou para criar uma nova senha de acesso <a title='Criar Nova Senha' href='" . BASE2 . "/admin/newpass.php?key={$CodePass}'>CLIQUE AQUI!</a>!</p>
                    <p>Você será redirecionado para uma página onde poderá definir uma nova senha de acesso ao painel! Cuide bem dos seus dados.</p>
                    ";
                    $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);

                    $Email->EnviarMontando('Recupere sua Senha', $Mensagem, ADMIN_NAME, MAIL_USER, $Read->getResult()[0]['user_name'], $Read->getResult()[0]['user_email']);

                    $_SESSION['trigger_login'] = AjaxErro("<b>SUCESSO:</b> Olá {$Read->getResult()[0]['user_name']}, confira o link enviado em seu e-mail para recuperar sua senha!");
                    $jSON['trigger'] = AjaxErro('<b>SUCESSO:</b> O link foi enviado para seu e-mail!');
                    $jSON['redirect'] = './';
                endif;
            else:
                $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Infor seu e-mail para recuperar a senha!', E_USER_WARNING);
            endif;
            break;

        case 'admin_newpass':
            if (empty($_SESSION['RecoverPass'])):
            else:
                if (in_array('', $PostData)):
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Para redefinir uma nova senha, você deve informar e repetir a mesma logo abaixo!', E_USER_NOTICE);
                elseif (strlen($PostData['user_password']) < 5):
                    $jSON['trigger'] = AjaxErro('<b>ALERTA:</b> Informe uma senha com no mínimo 5 caracteres!', E_USER_WARNING);
                elseif ($PostData['user_password'] != $PostData['user_password_re']):
                    $jSON['trigger'] = AjaxErro('<b>ALERTA:</b> Você deve informar e repetir a mesma senha. Você informou senhas diferentes!', E_USER_WARNING);
                else:
                    $DecodeValidate = base64_decode($_SESSION['RecoverPass']);
                    parse_str($DecodeValidate, $Validate);

                    $Read->FullRead("SELECT user_name, user_id FROM " . DB_USERS . " WHERE user_id = :id AND user_email = :email AND user_password = :pass", "id={$Validate['user_id']}&email={$Validate['user_email']}&pass={$Validate['user_password']}");
                    if ($Read->getResult()):
                        $UpdatePass = ['user_password' => hash('sha512', $PostData['user_password'])];
                        $Update = new Update;
                        $Update->ExeUpdate(DB_USERS, $UpdatePass, "WHERE user_id = :id", "id={$Read->getResult()[0]['user_id']}");

                        $_SESSION['trigger_login'] = AjaxErro("<b>INFO:</b> Olá {$Read->getResult()[0]['user_name']}, para logar informe seu e-mail e sua NOVA SENHA de acesso!");
                        $jSON['trigger'] = AjaxErro('<b>SUCESSO:</b> Sua senha foi redefinida!');
                        $jSON['redirect'] = './';
                    else:
                        $_SESSION['trigger_login'] = AjaxErro("<b>OPPSSS:</b> Você tentou recuperar sua senha com um código de acesso expirado!", E_USER_ERROR);
                        $jSON['trigger'] = AjaxErro('<b>ERRO:</b> Não foi possível redefinir!', E_USER_WARNING);
                        $jSON['redirect'] = './';
                    endif;
                endif;
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

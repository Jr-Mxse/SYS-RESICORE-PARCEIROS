<?php

ob_start();
session_start();

require '../../_app/Config.inc.php';

$Read = new Read;
$Update = new Update;
$Create = new Create;
$Delete = new Delete;

$jSON = null;
$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
unset($POST['user_level']);

if ($POST && $POST['callback']):
    //PRESERVE TINYMCE
    $TinyText = (!empty($POST['ticket_content']) ? $POST['ticket_content'] : null);

    //STRIP SCRIPTS
    $DataStrip = array_map("strip_tags", $POST);

    //NORMALIZE TINYTEXT
    if ($TinyText):
        $DataStrip['ticket_content'] = $TinyText;
    endif;

    //REMOVE W SPACES
    $DataTrim = array_map("trim", $DataStrip);
    $DataRTrim = array_map("rtrim", $DataTrim);

    //MAKE Callback
    $Callback = $DataRTrim['callback'];
    unset($DataRTrim['callback']);

    //MAKE DATA
    $DATA = $DataRTrim;

    /*
     * EAD RETURN
     * MODAL: $jSON['modal'] = ["Color", "Icon", "Title", "Content"];
     * ALERT: $jSON['alert'] = ["Color", "Title", "Content"];
     */
    switch ($Callback):

        case 'wc_ead_login_fix':
            $jSON['login'] = true;
            break;
        
        case 'wc_ead_student_task_manager':
            $studend_class_id = (!empty($_SESSION['wc_student_class']) ? $_SESSION['wc_student_class'] : null);
            $start_time = (!empty($_SESSION['wc_student_task']) ? $_SESSION['wc_student_task'] : null);
            $user_id = (!empty($_SESSION['userLoginParceiros']['user_id']) ? $_SESSION['userLoginParceiros']['user_id'] : null);
            $end_time = 0;

            //var_dump($studend_class_id, $start_time, $user_id, $end_time);

            if ($studend_class_id && $start_time && $user_id):
                $Read->FullRead("SELECT class_time, class_video FROM " . DB_EAD_CLASSES . " WHERE class_id = (SELECT class_id FROM " . DB_EAD_STUDENT_CLASSES . " WHERE user_id = :user AND student_class_id = :class AND student_class_free IS NULL)", "user={$user_id}&class={$studend_class_id}");
                if ($Read->getResult()):

                    //SAVE CLASS TIME
                    $ClassTotalTime = $Read->getResult()[0]['class_time'];
                    if (!$Read->getResult()[0]['class_video'] || $Read->getResult()[0]['class_video'] == ""):
                        $Read->getResult()[0]['class_video'] = 0;
                        $ClassTotalTime = 0;
                    endif;

                    if (EAD_STUDENT_CLASS_PERCENT):
                        $end_time = ceil(($start_time + ($Read->getResult()[0]['class_time'] * 60) * (EAD_STUDENT_CLASS_PERCENT / 100)));
                        $ClassTotalTime = floor($ClassTotalTime * (EAD_STUDENT_CLASS_PERCENT / 100));
                    endif;

                    if (EAD_STUDENT_CLASS_AUTO_CHECK):
                        if (time() >= $end_time):
                            $UpdateStudenClass = ['student_class_free' => 1, "student_class_check" => date("Y-m-d H:i:s")];
                            $Update->ExeUpdate(DB_EAD_STUDENT_CLASSES, $UpdateStudenClass, "WHERE student_class_id = :class", "class={$studend_class_id}");
                            $jSON['check'] = "<span class='a active icon-checkmark jwc_ead_task_uncheck'>" . date("d/m/Y H\hi") . "</span>";
                            $jSON['stop'] = true;
                            break;
                        endif;
                    endif;

                    if (time() > $end_time && !EAD_STUDENT_CLASS_AUTO_CHECK):
                        $UpdateStudenClass = ['student_class_free' => 1];
                        $Update->ExeUpdate(DB_EAD_STUDENT_CLASSES, $UpdateStudenClass, "WHERE student_class_id = :class", "class={$studend_class_id}");
                        $jSON['aprove'] = "<span class='a check icon-checkmark2 jwc_ead_task_check'>Concluir Tarefa</span>";
                        $jSON['stop'] = true;
                        break;
                    else:
                        $Read->FullRead("SELECT student_class_seconds FROM " . DB_EAD_STUDENT_CLASSES . " WHERE student_class_id = :st_class", "st_class={$studend_class_id}");
                        if ($Read->getResult()):
                            $student_class_seconds = ['student_class_seconds' => $Read->getResult()[0]['student_class_seconds'] + 10];
                            $Update->ExeUpdate(DB_EAD_STUDENT_CLASSES, $student_class_seconds, "WHERE student_class_id = :st_class", "st_class={$studend_class_id}");

                            //RECOVER USER LAST VIEW TIME
                            $ClassFreeTime = floor($Read->getResult()[0]['student_class_seconds'] / 60);
                            if ($ClassFreeTime >= $ClassTotalTime):
                                if (EAD_STUDENT_CLASS_AUTO_CHECK):
                                    $UpdateStudenClass = ['student_class_free' => 1, "student_class_check" => date("Y-m-d H:i:s")];
                                    $Update->ExeUpdate(DB_EAD_STUDENT_CLASSES, $UpdateStudenClass, "WHERE student_class_id = :class", "class={$studend_class_id}");
                                    $jSON['check'] = "<span class='a active icon-checkmark jwc_ead_task_uncheck'>" . date("d/m/Y H\hi") . "</span>";
                                    $jSON['stop'] = true;
                                    break;
                                else:
                                    $UpdateStudenClass = ['student_class_free' => 1];
                                    $Update->ExeUpdate(DB_EAD_STUDENT_CLASSES, $UpdateStudenClass, "WHERE student_class_id = :class", "class={$studend_class_id}");
                                    $jSON['aprove'] = "<span class='a check icon-checkmark2 jwc_ead_task_check'>Concluir Tarefa</span>";
                                    $jSON['stop'] = true;
                                    break;
                                endif;
                            else:
                                $jSON['waiting'] = true;
                            endif;
                        else:
                            $jSON['waiting'] = true;
                        endif;
                    endif;
                else:
                    $jSON['stop'] = true;
                    break;
                endif;
            else:
                $jSON['stop'] = true;
                break;
            endif;
        break;

        case 'wc_ead_student_task_manager_check':
            $studend_class_id = $_SESSION['wc_student_class'] ?? null;
            $user_id = $_SESSION['userLoginParceiros']['user_id'] ?? null;

            if (!$user_id || !$studend_class_id):
                $jSON['trigger'] = AjaxErro("Erro ao concluir tarefa: <p>Desculpe, não foi possível identificar seu login ou a tarefa acessada. As aulas devem ser feitas uma de cada vez.</p>", E_USER_WARNING);
                echo json_encode($jSON); return;
            endif;

            $Read->FullRead("SELECT class_title FROM " . DB_EAD_CLASSES . " WHERE class_id = (SELECT class_id FROM " . DB_EAD_STUDENT_CLASSES . " WHERE student_class_id = :class)", "class={$studend_class_id}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("Tarefa não identificada {$_SESSION['userLoginParceiros']['user_name']}, atualize a página para que a tarefa seja identificada!", E_USER_WARNING);
                echo json_encode($jSON); return;
            endif;

            $UpdateStudenClass = ['student_class_free' => 1, 'student_class_check' => date('Y-m-d H:i:s')];
            $Update->ExeUpdate(DB_EAD_STUDENT_CLASSES, $UpdateStudenClass, "WHERE student_class_id = :class", "class={$studend_class_id}");

            $classTitle = $Read->getResult()[0]['class_title'];
            $userName = $_SESSION['userLoginParceiros']['user_name'];

            $jSON['check'] = "<span class='a active icon-checkmark jwc_ead_task_uncheck'>" . date('d/m/Y H\hi') . "</span>";
            $jSON['trigger'] = AjaxErro("Tarefa concluída {$userName}! Parabéns, você concluiu a tarefa <b>{$classTitle}</b>!");
            echo json_encode($jSON); return;
        break;

        case 'wc_ead_student_task_manager_uncheck':
            $studend_class_id = $_SESSION['wc_student_class'] ?? null;
            $user_id = $_SESSION['userLoginParceiros']['user_id'] ?? null;

            if (!$user_id || !$studend_class_id):
                $jSON['trigger'] = AjaxErro("Erro ao concluir tarefa: <p>Desculpe, não foi possível identificar seu login ou a tarefa acessada. As aulas devem ser feitas uma de cada vez.</p>", E_USER_WARNING);
                echo json_encode($jSON); return;
            endif;

            $Read->FullRead("SELECT class_title FROM " . DB_EAD_CLASSES . " WHERE class_id = (SELECT class_id FROM " . DB_EAD_STUDENT_CLASSES . " WHERE student_class_id = :class)", "class={$studend_class_id}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("Tarefa não identificada {$_SESSION['userLoginParceiros']['user_name']}, atualize a página para que a tarefa seja identificada!", E_USER_WARNING);
                echo json_encode($jSON); return;
            endif;

            $UpdateStudenClass = ['student_class_free' => 1, 'student_class_check' => null];
            $Update->ExeUpdate(DB_EAD_STUDENT_CLASSES, $UpdateStudenClass, "WHERE student_class_id = :class", "class={$studend_class_id}");

            $classTitle = $Read->getResult()[0]['class_title'];
            $userName = $_SESSION['userLoginParceiros']['user_name'];

            $jSON['check'] = "<span class='a check icon-checkmark2 jwc_ead_task_check'>Concluir Tarefa</span>";
            $jSON['trigger'] = AjaxErro("Volte aqui depois {$userName}, {$classTitle}!", E_USER_NOTICE);
            echo json_encode($jSON); return;
        break;

        case 'wc_ead_studend_certification':
            sleep(1);

            if (empty($_SESSION['userLoginParceiros']) || empty($_SESSION['userLoginParceiros']['user_id'])):
                $jSON['trigger'] = AjaxErro("Oppsss, perdemos algo: <p>Sua conta não está mais conectada, recarregando!</p>", E_USER_WARNING);
                $jSON['reload'] = true;
                echo json_encode($jSON); return;
            endif;

            $Read->FullRead("SELECT certificate_id FROM " . DB_EAD_STUDENT_CERTIFICATES . " WHERE enrollment_id = :enrol AND user_id = :user", "enrol={$POST['enrollment_id']}&user={$_SESSION['userLoginParceiros']['user_id']}");
            if ($Read->getResult()):
                $jSON['trigger'] = AjaxErro("Oppsss, perdemos algo: <p>Seu certificado para este curso já foi emitido {$_SESSION['userLoginParceiros']['user_name']}!</p>", E_USER_NOTICE);
                echo json_encode($jSON); return;
            endif;

            $Read->ExeRead(DB_EAD_ENROLLMENTS, "WHERE enrollment_id = :enrol AND user_id = :user", "enrol={$POST['enrollment_id']}&user={$_SESSION['userLoginParceiros']['user_id']}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("Erro ao Emitir Certificado: <p>Desculpe {$_SESSION['userLoginParceiros']['user_name']}, mas não foi possível ler a matrícula deste curso. Atualize a página e tente novamente, ou entre em contato via " . SITE_ADDR_EMAIL . ".</p>", E_USER_WARNING);
                echo json_encode($jSON); return;
            endif;

            extract($Read->getResult()[0]);

            $Read->FullRead("SELECT COUNT(class_id) AS ClassCount, SUM(class_time) AS ClassTime FROM " . DB_EAD_CLASSES . " WHERE module_id IN (SELECT module_id FROM " . DB_EAD_MODULES . " WHERE course_id = :cs)", "cs={$course_id}");
            $ClassCount = $Read->getResult()[0]['ClassCount'] ?? 0;

            $Read->FullRead("SELECT COUNT(student_class_id) AS ClassStudentCount FROM " . DB_EAD_STUDENT_CLASSES . " WHERE user_id = :user AND course_id = :course AND student_class_check IS NOT NULL", "user={$user_id}&course={$course_id}");
            $ClassStudenCount = $Read->getResult()[0]['ClassStudentCount'] ?? 0;

            $CourseCompletedPercent = ($ClassStudenCount && $ClassCount ? round(($ClassStudenCount * 100) / $ClassCount) : 0);

            $Read->LinkResult(DB_EAD_COURSES, "course_id", $course_id, "course_title, course_certification_request");
            extract($Read->getResult()[0]);

            if ($_SESSION['userLoginParceiros']['user_level'] < 6 && $CourseCompletedPercent < $course_certification_request):
                $jSON['trigger'] = AjaxErro("Oppsss {$_SESSION['userLoginParceiros']['user_name']}: <p>Para solicitar seu certificado, complete pelo menos <b>{$course_certification_request}%</b> do curso!</p>", E_USER_NOTICE);
                echo json_encode($jSON); return;
            endif;

            $CreateCertification = [
                'user_id' => $user_id,
                'course_id' => $course_id,
                'enrollment_id' => $enrollment_id,
                'certificate_key' => "{$user_id}{$course_id}" . date('Ym'),
                'certificate_issued' => date('Y-m-d'),
                'certificate_status' => 1
            ];
            $Create->ExeCreate(DB_EAD_STUDENT_CERTIFICATES, $CreateCertification);

            $jSON['certification'] = [
                "Image" => "<div class='wc_ead_win_image'><span class='wc_ead_win_image_icon icon-trophy icon-notext'></span></div>",
                "Icon" => "heart",
                "Title" => "Parabéns {$_SESSION['userLoginParceiros']['user_name']} :)",
                "Content" => "Mais uma conquista em sua carreira. Seu certificado para o curso <b>{$course_title}</b> foi emitido com sucesso!",
                "Link" => BASE . "/campus/imprimir/{$CreateCertification['certificate_key']}",
                "LinkIcon" => "printer",
                "LinkTitle" => "Imprimir Certificado!"
            ];

            echo json_encode($jSON); return;
        break;

    endswitch;
endif;

if ($jSON):
    echo json_encode($jSON);
else:
    $jSON['modal'] = ['red', 'warning', 'Erro inesperado!', '<p><b>Opppssss:</b> Um erro inesperado foi encontrado no sistema. Favor atualize a página e tente novamente!</p><p>Caso o erro persista, não deixe de nos avisar enviando um e-mail para ' . SITE_ADDR_EMAIL . '!</p><p>Obrigado. Atenciosamente ' . SITE_NAME . '!</p>'];
    echo json_encode($jSON);
endif;

ob_end_flush();

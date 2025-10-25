$(function () {

    //GET PROJETC BASE
    BASE = $("link[rel='base']").attr("href");


// FORM SUBMIT - EAD + Loader novo
$('.wc_ead').on("submit", "form", function (event) {
    event.preventDefault();

    var Form = $(this);
    var Btn = Form.find('button[type="submit"], input[type="submit"]');
    var BtnOriginalText = Btn.html();

    Form.ajaxSubmit({
        url: BASE + '/_ead/wc_ead.ajax.php',
        data: {callback: Form.attr("name")},
        dataType: 'json',
        beforeSubmit: function () {

            Form.find('.form_load').fadeIn('fast');
            $('.trigger_ajax').fadeOut('fast');
            Btn.prop('disabled', true).html('<span class="spinner"></span>');
        },
        uploadProgress: function (evento, posicao, total, completo) {
            var porcento = completo + '%';
            $('.jwc_ead_upload_progress').text(porcento);

            if (completo <= '80') {
                $('.jwc_ead_upload').fadeIn().css('display', 'flex');
            }
            if (completo >= '99') {
                $('.jwc_ead_upload').fadeOut();
            }

            Form.find('input[name="image[]"]').replaceWith($('input[name="image[]"]').clone());
        },
        success: function (data) {
            // Esconde o loader
            Form.find('.form_load').fadeOut('slow', function () {

                // MODAL
                if (data.modal) {
                    wc_ead_modal(data.modal[0], data.modal[1], data.modal[2], data.modal[3]);
                }

                // ALERT
                if (data.alert) {
                    wc_ead_alert(data.alert[0], data.alert[1], data.alert[2]);
                }

                // TASK FORUM
                if (data.ead_support) {
                    if (data.ead_support_id) {
                        if (!$('#' + data.ead_support_id + ' .wc_ead_course_task_forum_response .wc_ead_course_task_forum_ticket').length) {
                            $('.jwc_allsupport #' + data.ead_support_id + ' .wc_ead_course_task_forum_response').html(data.ead_support_content);
                            $('.jwc_mysupport #' + data.ead_support_id + ' .wc_ead_course_task_forum_response').html(data.ead_support_content);
                        } else {
                            $(data.ead_support_content).insertAfter('.jwc_allsupport #' + data.ead_support_id + ' .wc_ead_course_task_forum_response .wc_ead_course_task_forum_ticket:last-child');
                            $(data.ead_support_content).insertAfter('.jwc_mysupport #' + data.ead_support_id + ' .wc_ead_course_task_forum_response .wc_ead_course_task_forum_ticket:last-child');
                        }
                    } else {
                        $('.wc_ead_course_task_forum_none').fadeOut(200);
                        $('.jwc_content').html(data.ead_support_content).fadeIn(300);
                    }
                }

                // HIGHLIGHT FIX
                setTimeout(function () {
                    if ($('*[class="brush: php;"]').length) {
                        $("head").append('<link rel="stylesheet" href="../../_cdn/highlight.min.css">');
                        $.getScript('../../_cdn/highlight.min.js', function () {
                            $('*[class="brush: php;"]').each(function (i, block) {
                                hljs.highlightBlock(block);
                            });
                        });
                    }
                }, 500);

                // REVIEW
                if (data.review) {
                    $('.jwc_review_target').html(data.review);
                }

                // REDIRECT
                if (data.redirect) {
                    $('.workcontrol_upload p').html("Atualizando dados, aguarde!");
                    $('.workcontrol_upload').fadeIn().css('display', 'flex');
                    window.setTimeout(function () {
                        window.location.href = data.redirect;
                        if (window.location.hash) {
                            window.location.reload();
                        }
                    }, 1500);
                }

                // CLOSE MODAL
                if (data.close) {
                    $(data.close).fadeOut();
                }

                // CLEAR FORM
                if (data.clear) {
                    Form.trigger('reset');
                }

                // TRIGGER CUSTOM (compatível com seu sistema)
                if (data.trigger) {
                    Trigger(data.trigger);
                }
            });
        },
        complete: function () {
            Btn.prop('disabled', false).html(BtnOriginalText);
        }
    });

    return false;
});

     // ************ PASSWORD VIEW ************
  const $input = $('#login_password');
  const $btn   = $('.toggle_password');

  const eyeOpen = `
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
      <path d="M14.122 9.87999C15.293 11.051 15.293 12.952 14.122 14.125C12.951 15.296 11.05 15.296 9.87703 14.125C8.70603 12.954 8.70603 11.053 9.87703 9.87999C11.05 8.70699 12.95 8.70699 14.122 9.87999" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
      <path d="M3 12C3 11.341 3.152 10.689 3.446 10.088V10.088C4.961 6.991 8.309 5 12 5C15.691 5 19.039 6.991 20.554 10.088V10.088C20.848 10.689 21 11.341 21 12C21 12.659 20.848 13.311 20.554 13.912V13.912C19.039 17.009 15.691 19 12 19C8.309 19 4.961 17.009 3.446 13.912V13.912C3.152 13.311 3 12.659 3 12Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill-rule="evenodd" clip-rule="evenodd"></path>
  </svg>`;

  const eyeClosed = `
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
      <path d="M14.5582 13.5577C13.9186 14.6361 12.6764 15.2036 11.4426 14.9811C10.2087 14.7585 9.24301 13.7928 9.02048 12.559C8.79795 11.3251 9.36544 10.0829 10.4438 9.44336" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
      <path d="M17.9966 16.9963C16.2719 18.3046 14.1649 19.0097 12.0001 19.0031C8.41297 19.0669 5.09862 17.0955 3.44251 13.9129C2.84761 12.7071 2.84761 11.2932 3.44251 10.0873C4.27076 8.43797 5.59106 7.08671 7.2208 6.22046" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
      <path d="M20.4275 14.1345C20.4677 14.0585 20.5199 13.9903 20.5578 13.9128C21.1527 12.707 21.1527 11.293 20.5578 10.0872C18.9017 6.90465 15.5873 4.93323 12.0002 4.99711C11.7753 4.99711 11.5567 5.02712 11.3347 5.04175" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
      <path d="M21.0039 20.0034L3.99683 2.99634" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
  </svg>`;

  $(document).on('click', '.toggle_password', function () {
    const $btn   = $(this);
    const $input = $btn.siblings('input'); // pega apenas o input irmão

    const show = $input.attr('type') === 'password';
    $input.attr('type', show ? 'text' : 'password');
    $btn.html(show ? eyeOpen : eyeClosed);
    $btn.attr('aria-pressed', show ? 'true' : 'false');
    $btn.attr('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
    $input.trigger('focus');
  });


    //ALL SUPPORT
    $('.wc_ead').on('click', '.wc_ead_allsupport', function () {
        if (!$(this).hasClass('btn_green')) {
            $(this).removeClass('btn_blue').addClass('btn_green');
            $('.wc_ead_mysupport').removeClass('btn_green').addClass('btn_blue');
            $('.jwc_mysupport').fadeOut(function () {
                $('.jwc_allsupport').fadeIn(400);
            });
        }
    });

    //MY SUPPORT
    $('.wc_ead').on('click', '.wc_ead_mysupport', function () {
        if (!$(this).hasClass('btn_green')) {
            $(this).removeClass('btn_blue').addClass('btn_green');
            $('.wc_ead_allsupport').removeClass('btn_green').addClass('btn_blue');
            $('.jwc_allsupport').fadeOut(function () {
                $('.jwc_mysupport').fadeIn(400);
            });
        }
    });

    //ALERT SETUP
    $('body').on('click', '.wc_ead_alert_close', function () {
        var EadAlert = $(".wc_ead_alert_box");
        $('.wc_ead_alert').fadeOut(200, function () {
            setTimeout(function () {
                EadAlert.removeClass("blue green red yellow");
                EadAlert.find('.wc_ead_alert_title').html("{TITLE}");
                EadAlert.find('.wc_ead_alert_content').html("{CONTENT}");
            }, 210);
        });
    });

    //ALERT DISPLAY
    function wc_ead_alert(Color, Title, Content) {
        var EadAlert = $(".wc_ead_alert_box");

        //REMOVE LOAD
        $(".jwc_load").fadeOut(200);

        EadAlert.addClass(Color);
        EadAlert.find('.wc_ead_alert_title').html(Title);
        EadAlert.find('.wc_ead_alert_content').html(Content);
        $('.wc_ead_alert').fadeIn(200).css('display', 'flex');
    }

    //MODAL SETUP
    $('.wc_ead').on('click', '.wc_ead_modal_close', function () {
        var Modal = $(".wc_ead_modal_box");
        $('.wc_ead_modal').fadeOut(200, function () {
            setTimeout(function () {
                Modal.find('.wc_ead_modal_title').removeClass("blue green red yellow");
                Modal.find('.wc_ead_modal_title span').removeClass().html("{TITLE}");
                Modal.find('.wc_ead_modal_content').html("{CONTENT}");
            }, 210);
        });
    });

    //MODAL DISPLAY
    function wc_ead_modal(Color, Icon, Title, Content) {
        var Modal = $(".wc_ead_modal_box");

        //REMOVE LOAD
        $(".jwc_load").fadeOut(200);

        Modal.find('.wc_ead_modal_title').addClass(Color);
        Modal.find('.wc_ead_modal_title span').addClass("icon-" + Icon).html(Title);
        Modal.find('.wc_ead_modal_content').html(Content);
        $('.wc_ead_modal').fadeIn(200).css('display', 'flex');
    }

    //CERTIFICATION GET
    $('.jwc_ead_certification').click(function () {
        $('.jwc_ead_load').fadeIn().css('display', 'flex');
        $.post(BASE + "/_ead/wc_ead.ajax.php", {callback: 'wc_ead_studend_certification', enrollment_id: $(this).attr('id')}, function (data) {
            $('.jwc_ead_load').fadeOut(function () {
                if (data.alert) {
                    wc_ead_alert(data.alert[0], data.alert[1], data.alert[2]);
                }

                if (data.reload) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }

                if (data.modal) {
                    wc_ead_modal(data.modal[0], data.modal[1], data.modal[2], data.modal[3]);
                }

                if (data.certification) {
                    wcEadWin(data.certification);
                }
            });
        }, 'json');
    });

    //WC EAD DEFAULT WIN
    function wcEadWin(WinSetupObject) {
        var WC_WIN = $('.jwc_ead_win');
        var WC_WIN_CONTENT = WC_WIN.html();

        WC_WIN.html(
                WC_WIN_CONTENT.replace("{{IMAGE}}", WinSetupObject.Image)
                .replace("{{ICON}}", WinSetupObject.Icon)
                .replace("{{TITLE}}", WinSetupObject.Title)
                .replace("{{CONTENT}}", WinSetupObject.Content)
                .replace("{{LINK}}", WinSetupObject.Link)
                .replace("{{LINK_ICON}}", WinSetupObject.LinkIcon)
                .replace("{{LINK_TITLE}}", WinSetupObject.LinkTitle)
                .replace("{{LINK_NAME}}", WinSetupObject.LinkTitle)
                );
        WC_WIN.fadeIn().css('display', 'flex');
    }

    //IMAGE LOAD
    $('.wc_loadimage').change(function () {
        var input = $(this);
        var target = $('.' + input.attr('id'));
        var fileDefault = target.attr('default');

        if (!input.val()) {
            target.fadeOut('fast', function () {
                $(this).attr('src', fileDefault).fadeIn('slow');
            });
            return false;
        }

        if (this.files && (this.files[0].type.match("image/jpeg") || this.files[0].type.match("image/png"))) {
            var reader = new FileReader();
            reader.onload = function (e) {
                target.fadeOut('fast', function () {
                    $(this).attr('src', e.target.result).fadeIn('fast');
                });
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            wc_ead_alert("yellow", "Imagem Inválida:", "Envie uma imagem JPG ou PNG com 500x500px!");

            target.fadeOut('fast', function () {
                $(this).attr('src', fileDefault).fadeIn('slow');
            });
            input.val('');
            return false;
        }
    });

    //TASK MANAGER
    if ($('.jwc_ead_task').length) {
        var TaskTarget = $('.jwc_ead_task');
        var TaskRepeat = setInterval(function () {
            $.post(BASE + '/_ead/wc_ead.ajax.php', {callback: 'wc_ead_student_task_manager'}, function (data) {
                if (data.aprove) {
                    TaskTarget.fadeTo(400, 0.5, function () {
                        TaskTarget.html(data.aprove).fadeTo(400, 1);
                    });
                }

                if (data.check) {
                    TaskTarget.fadeTo(400, 0.5, function () {
                        TaskTarget.html(data.check).fadeTo(400, 1);
                    });
                }

                if (data.stop) {
                    clearTimeout(TaskRepeat);
                }
            }, 'json');
        }, 10000);
    }

    //TASK MANAGER :: MANUAL CHECK
    $('.wc_ead').on('click', '.jwc_ead_task_check', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var TaskTarget = $('.jwc_ead_task');
        $.post(BASE + '/_ead/wc_ead.ajax.php', {callback: 'wc_ead_student_task_manager_check'}, function (data) {
            if (data.check) {
                TaskTarget.fadeTo(400, 0.5, function () {
                    TaskTarget.html(data.check).fadeTo(400, 1);
                });
            }

            if (data.modal) {
                wc_ead_modal(data.modal[0], data.modal[1], data.modal[2], data.modal[3]);
            }

            if (data.alert) {
                wc_ead_alert(data.alert[0], data.alert[1], data.alert[2]);
            }
        }, 'json');
    });

    //TASK MANAGER :: MANUAL UNCHECK
    $('.wc_ead').on('click', '.jwc_ead_task_uncheck', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var TaskTarget = $('.jwc_ead_task');
        $.post(BASE + '/_ead/wc_ead.ajax.php', {callback: 'wc_ead_student_task_manager_uncheck'}, function (data) {
            if (data.check) {
                TaskTarget.fadeTo(400, 0.5, function () {
                    TaskTarget.html(data.check).fadeTo(400, 1);
                });
            }

            if (data.modal) {
                wc_ead_modal(data.modal[0], data.modal[1], data.modal[2], data.modal[3]);
            }

            if (data.alert) {
                wc_ead_alert(data.alert[0], data.alert[1], data.alert[2]);
            }
        }, 'json');
    });

    //TASK MANAGER :: CLOSE MODAL REPLY
    $('.wc_ead').on('click', '.j_wc_ticket_close', function () {
        $('.wc_ead_course_task_modal').fadeOut(200);
    });

    //TASK MANAGER :: OPEN MODAL REPLY
    $('.wc_ead').on('click', '.jwc_ticket_review', function () {
        $('.jwc_ticket_review_content').find("input[name='support_id']").val($(this).attr("id"));
        $('.jwc_ticket_review_content').fadeIn(200).css('display', 'flex');
    });

    //TASK MANAGER :: OPEN MODAL REVIEW
    $('.wc_ead').on('click', '.jwc_ticket_reply', function () {
        $('.jwc_ticket_reply_content').find("input[name='support_id']").val($(this).attr("id"));
        $('.jwc_ticket_reply_content').fadeIn(200).css('display', 'flex');
    });

    //STUDENT FIX LOGIN ON PLAY
    if ($('.jwc_ead_restrict').length) {
        setInterval(function () {
            $.post(BASE + '/_ead/wc_ead.ajax.php', {callback: 'wc_ead_login_fix'}, function (data) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            }, 'json');
        }, 60000);
    }

    //WC EAD BONUS CLOSE
    $('.wc_ead').on('click', '.jwc_ead_close_bonus', function () {
        $('.wc_ead_win').fadeOut(200, function () {
            window.location.reload();
        });
    });

    //WC EAD TAB AUTOCLICK
    if (window.location.hash) {
        $("a[href='" + window.location.hash + "']").click();
        if (window.location.hash == '#orders') {
            $('html, body').animate({scrollTop: 0}, 300);
        }
    }

    //NEW LINE ACTION
    $('textarea').keypress(function (event) {
        if (event.which === 13) {
            var s = $(this).val();
            $(this).val(s + "\n");
        }
    });

    //WC EAD TEXT EDITOR
    if ($('.jwc_ead_editor').length) {
        tinyMCE.init({
            selector: "textarea.jwc_ead_editor",
            language: 'pt_BR',
            menubar: false,
            theme: "modern",
            height: 200,
            verify_html: true,
            skin: 'light',
            entity_encoding: "raw",
            theme_advanced_resizing: true,
            plugins: [
                "paste autolink link"
            ],
            toolbar: "bold | italic | link",
            content_css: BASE + "/admin/_css/tinyMCE.css",
            style_formats: [
                {title: 'Normal', block: 'p'},
                {title: 'Código', block: 'pre', classes: 'brush: php;'}
            ],
            link_title: false,
            target_list: false,
            media_dimensions: false,
            media_poster: false,
            media_alt_source: false,
            media_embed: false,
            extended_valid_elements: "a[href|target=_blank|rel|class]",
            image_dimensions: false,
            relative_urls: false,
            remove_script_host: false,
            resize: false,
            paste_as_text: true
        });
    }
});
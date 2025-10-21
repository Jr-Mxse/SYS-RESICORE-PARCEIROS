
$(function () {
    $('body').addClass('custom-wc');

    //############## Textareas dinamicas
    if ($('textarea[data-autoresize]').length) {
        plugginAutosize();
    }

    //############## Mascaras
    if ($(".money-input").length) {
        plugginMaskMoney();
    }
    if ($(".formDate").length || $(".formTime").length || $('.formHour').length || $(".formCep").length || $(".formCpf").length || $(".formPhone").length) {
        plugginMaskDefault();
    }

    //############## CUSTOM INPUT [radio/checkbox]
    if ($('.iCheck').length) {
        plugginICheck();
    }

    //############## LIGHTGALLERY Expandir imagem
    if ($('#lightgallery').length) {
        plugginLightgallery();
    }

    //############## INPUT TAG's
    if ($('.input-tag').length) {
        if ($('.input-tag[name="realty_particulars"]').length) Text = 'Características';
        else Text = 'Tags';
        $('.input-tag').tagsInput({
            'defaultText': Text,
            'onAddTag': sendCall,
            'onRemoveTag': sendCall
        });
        function sendCall() {
            $('.input-tag').change();
        }
    }

    //############## COPY TO CLIPBOARD
    if ($('.copy').length) {
        $('html').on('click', '.copy', function () {
            var target = $(this).attr('data-clipboard-target');
            var copyText = (target) ? $(target).text() : $(this).text();

            var temp = $("<input>");
            $("body").append(temp);
            temp.val(copyText).select();
            document.execCommand("copy");
            temp.remove();

            if (copyText) {
                Trigger("<div class='trigger trigger_ajax trigger_success'><b>" + copyText + "</b> copiado para área de transferência</div>");
                window.setTimeout(function () {
                    TriggerClose();
                }, 2000);
            }
        });
    }

    //############## UPLOAD FIVE (imagens em massa)
    if ($('#file_upload').length) {
        plugginUploadFive();
    }

    //############## SWET ALERT
    $('html, body').on('click', '.j_swal_action', function (e) {
        var button = $(this),
            Prevent = button,
            Id = button.attr('id'),
            RelTo = button.attr('rel'),
            RelId = button.attr('data-rel'),
            RelRel = button.attr('data-relrel'),
            Callback = button.attr('callback'),
            Callback_action = button.attr('callback_action'),
            CustomCallback = button.attr('customCallback'),
            Message = button.attr('data-confirm-text'),
            MessageContent = button.attr('data-confirm-message');

        if (CustomCallback != undefined) $URL = CustomCallback;
        else $URL = '_ajax/' + Callback + '.ajax.php';

        if (MessageContent != undefined) $MessageContent = MessageContent;
        else $MessageContent = 'Uma vez excluído esse registro não poderá ser recuperado!';

        swal({
            backdrop: false,
            title: Message,
            html: $MessageContent,
            type: "warning",
            reverseButtons: true,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (result.value) {
                $('.workcontrol_upload p').html("Processando ... aguarde !");
                $('.workcontrol_upload').fadeIn().css('display', 'flex');

                $.post($URL, { callback: Callback, callback_action: Callback_action, id: Id }, function (data) {
                    $('.workcontrol_upload').fadeOut();
                    if (data.trigger) {
                        Trigger(data.trigger);
                    }

                    // Mensagens de erro ou sucesso
                    if (data.error) {
                        swal({
                            backdrop: false,
                            type: 'error',
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Entendi',
                            title: 'ERRO',
                            html: data.error,
                        });
                    } else if (data.success) {
                        if (data.refresh || data.redirect) { var confirmButton = false; }
                        else { var confirmButton = true; }

                        swal({
                            backdrop: false,
                            type: "success",
                            showConfirmButton: confirmButton,
                            title: "SUCESSO",
                            html: data.success,
                        });
                        // Remove registro excluído
                        if (RelId != undefined) {
                            $('.' + RelTo + '#' + RelId).fadeOut('fast');
                            console.log('.' + RelTo + '#' + RelId);
                        } else if (RelRel != undefined) {
                            $('.' + RelTo + '[data-rel="' + RelRel + '"]').fadeOut('fast');
                            console.log('.' + RelTo + '[data-rel="' + RelRel + '"]');
                        } else {
                            $('html').find('.' + RelTo + '#' + Id).fadeOut('fast');
                            console.log('.' + RelTo + '#' + Id);
                        }
                    }

                    if (data.forceclick) {
                        if (typeof (data.forceclick) === 'string') {
                            setTimeout(function () {
                                $(data.forceclick).click();
                            }, 250);
                        } else if (typeof (data.forceclick) === 'object') {
                            $.each(data.forceclick, function (key, value) {
                                setTimeout(function () {
                                    $(value).click();
                                }, 250);
                            });
                        }
                    }

                    if (data.divcontent) {
                        if (typeof (data.divcontent) === 'string') {
                            $(data.divcontent[0]).html(data.divcontent[1]);
                        } else if (typeof (data.divcontent) === 'object') {
                            $.each(data.divcontent, function (key, value) {
                                $(key).html(value);
                            });
                        }
                    }

                    if (data.refresh) {
                        window.setTimeout(function () {
                            location.reload();
                        }, 2200);
                    }
                    if (data.redirect) {
                        if (data.redirect_timer != undefined) { var TIMER = data.redirect_timer; } else { var TIMER = 1500; }
                        window.setTimeout(function () {
                            window.location.replace(data.redirect);
                        }, TIMER);
                    }
                }, 'json');
            }
        });
    });

    //############## MODAL NATIVA :: Botao para enviar requisição
    $('body, html').on('click', '.j_ajaxModal', function () {
        var Action = $(this).attr('callback_action');
        var CallBack = $(this).attr('callback');
        var Src = CallBack;
        var Id = $(this).attr('callback_id');

        Data = (Id != undefined) ? { callback: CallBack, callback_action: Action, id: Id } : { callback: CallBack, callback_action: Action };

        $.post('_ajax/' + Src + '.ajax.php', Data, function (data) {
            if (data.modal) {
                ajaxModal(data.modal.icon, data.modal.theme, data.modal.title, data.modal.content, data.modal.footer, data.modal.size, data.modal.callback);
            }
            if (data.divcontent) {
                if (typeof (data.divcontent) === 'string') {
                    $(data.divcontent[0]).html(data.divcontent[1]);
                } else if (typeof (data.divcontent) === 'object') {
                    $.each(data.divcontent, function (key, value) {
                        $(key).html(value);
                    });
                }
            }
            if (data.trigger) {
                Trigger(data.trigger);
            }
        }, 'json');
        return false;
    });

    //############## MODAL NATIVA :: Botao para enviar requisição
    $('html, body').on('click', '.j_sendFormModal', function (e) {
        var Form = $(this).closest('.ajax_modal').find('form:first'),
            Load = Form.find('.form_load'),
            Footer = $(this).closest('div');

        Load.fadeIn(function () {
            ajaxModalLoad(Form, Footer);
            Form.submit();
        });

        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    //############## MODAL NATIVA :: Ação após submeter formulario da modal
    $('html').on('submit', 'form[name="modal-form"]', function () {
        setTimeout(function () {
            //############## MCE extra basic
            if ($('.work_mce_extra-basic').length) {
                wc_tinyMCE_extrabasic();
            }

            //############## Textareas dinamicas
            if ($('textarea[data-autoresize]').length) {
                plugginAutosize();
            }

            //############## Mascaras
            if ($(".money-input").length) {
                plugginMaskMoney();
            }
            if ($(".formDate").length || $(".formTime").length || $(".formCep").length || $(".formCpf").length || $(".formPhone").length) {
                plugginMaskDefault();
            }

            //############## CUSTOM INPUT [radio/checkbox]
            if ($('.iCheck').length) {
                plugginICheck();
            }

            if ($('.jwc_datepicker').length) {
                plugginDatepicker();
            }
        }, 2000);
    });
});

//NATIVE MODAL
function ajaxModal(Icon, Theme, Title, Content, Footer, Size, Call) {
    if ($('.ajax_modal:not(.nodom)').length) { $('.ajax_modal:not(.nodom)').remove(); }

    var Theme = (Theme) ? Theme : null;

    if (Size) {
        if (Size === 'medium') { Size = 'style="width:760px"'; }
        else if (Size === 'large') { Size = 'style="width:1100px"'; }
    }
    else { Size = null; }



    $("body").append('<div class="ajax_modal"><div class="ajax_modal_box" ' + Size + '><div class="ajax_modal_head skin_' + Theme + '"><span class="ajax_modal_close j_ajaxModalClose icon-cross icon-notext"></span><p class="ajax_modal_title"><span>{TITLE}</span></p></div><div class="ajax_modal_content">{CONTENT}</div><div class="ajax_modal_footer">{FOOTER}</div></div></div>');

    var Modal = $('.ajax_modal:not(.nodom)').find(".ajax_modal_box");
    var FooHeight = Modal.find('.ajax_modal_footer').height();
    Modal.find('.ajax_modal_title span').addClass("icon-" + Icon).html(Title);
    Modal.find('.ajax_modal_content').html(Content).css('margin-bottom', FooHeight);
    Modal.find('.ajax_modal_footer').html(Footer + '<div class="clear"></div>');

    $('.ajax_modal:not(.nodom)').fadeIn(200, function () {
        $('body, html').css('overflow', 'hidden');
    }).css('display', 'block');

    //PLUGGINS CUSTOMIZADOS    
    if (Call) {
        if (typeof (Call) === 'string') {
            var myobj = JSON.parse(JSON.stringify(Call + '()'));
            myobj.callPluggins = new Function(myobj)();
        } else if (typeof (Call) === 'object') {
            $.each(Call, function (Key, Value) {
                var myobj = JSON.parse(JSON.stringify(Value + '()'));
                myobj.callPluggin = new Function(myobj)();
            });
        }
    }

    //MODAL Close
    $('html').on('click', '.j_ajaxModalClose', function () {
        $('body, html').css('overflow', 'inherit');
        $(this).closest('.ajax_modal:not(.nodom)').fadeOut(400, function () {
            // if (typeof tinyMCE !== 'undefined') { plugginTiny(true); }
            $(this).closest('.ajax_modal:not(.nodom)').remove();
        });
    });
}

function ajaxModalLoad(Form, Content) {
    // Clona load do formulario e exclui quando o original some
    Form.closest('.ajax_modal_box').find('.ajax_modal_footer').find('.form_load').remove();
    var LoadObject = Form.find('.form_load');
    var Load = Object.values(LoadObject)[0];
    var Observer = new MutationObserver(handleMutationObserver);
    var Config = { childList: true, attributes: true };
    Content.append(LoadObject.clone());

    function handleMutationObserver(mutations) {
        mutations.forEach(function (mutation) {
            if (
                mutation.target.style.cssText.match(/display: none/) ||
                mutation.target.style.cssText.match(/display:none/)) {
                Content.find('.form_load').remove();
            }
        });
    } Observer.observe(Load, Config);
}

function plugginTiny(Destroy) {
    if (Destroy) {
        tinyMCE.remove();
    } else {
        // TinyMCE
        if ($('.work_mce').length) {
            wc_tinyMCE();
        }
    }
}

function plugginAutosize() {
    if ($('textarea[data-autoresize]').length) {
        autosize($('textarea[data-autoresize]'));
    }
}

function plugginICheck() {
    if ($('.iCheck.iCheck-purple').length) {
        $('.iCheck.iCheck-purple input').iCheck({
            checkboxClass: 'icheckbox_square-purple',
            radioClass: 'iradio_square-purple',
            increaseArea: '20px'
        }).on('ifChecked', function () {
            $(this).change();
        }).on('ifUnchecked', function () {
            $(this).change();
        });
    } else {
        $('.iCheck input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20px'
        }).on('ifChecked', function () {
            $(this).change();
        }).on('ifUnchecked', function () {
            $(this).change();
        });
    }
}

function plugginMaskMoney() {
    $(".money-input").maskMoney({
        prefix: 'R$ ',
        allowNegative: true,
        thousands: '.',
        decimal: ',',
        affixesStay: false
    });
}

function plugginMaskDefault() {
    $(".formDate").mask("99/99/9999");
    $(".formTime").mask("99/99/9999 99:99");
    $(".formHour").mask("99:99");
    $(".formCep").mask("99999-999");
    $(".formCpf").mask("999.999.999-99");

    //$('.formPhone').focusout(function () {
        //var phone, element;
        //element = $(this);
        //element.unmask();
        //phone = element.val().replace(/\D/g, '');
        //if (phone.length > 10) {
            //element.mask("(99) 99999-999?9");
        //} else {
            //element.mask("(99) 9999-9999?9");
        //}
    //}).trigger('focusout');
}

function plugginUploadFive() {
    if (!$('#queue').length) { $('body').append('<div id="queue" class="ajax-photos"></div>'); }

    var Data = $('#file_upload').attr('data-gallery'),
        Callback = $('#file_upload').attr('callback'),
        CallbackAction = $('#file_upload').attr('callback_action');

    $('#file_upload').uploadifive({
        'auto': true,
        'removeCompleted': true,
        'buttonText': "<i class='icon-upload'></i>SELECIONAR FOTOS<br/>",
        'formData': {
            'callback': Callback,
            'callback_action': CallbackAction,
            'gallery': Data
        },
        'queueID': 'queue',
        'uploadScript': '_ajax/' + Callback + '.ajax.php',
        'onUploadComplete': function (file, data) {
            //CONVERSÃO DE JSON TO OBJECT       
            var obj = jQuery.parseJSON(data);

            //DATA DINAMIC CONTENT
            if (obj.j_fotos) {
                $('.j_photos').append(obj.j_fotos);
                if ($('.j_photos .trigger').length) {
                    $('.j_photos .trigger').fadeOut(100);
                }
            }
            if (obj.trigger) {
                Trigger(obj.trigger);
            }
        }
    });
}

function plugginLightgallery() {
    $('#lightgallery').lightGallery({
        selector: '.post_single_cover'
    });
}


function plugginDatepicker() {
    $('.jwc_datepicker').datepicker({
        language: 'pt-BR',
        autoClose: true
    });
}

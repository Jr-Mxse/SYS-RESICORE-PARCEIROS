$(function(){
    
  $('html').on('submit', 'form:not(.ajax_off)', function (e) {
    e.preventDefault(); 

    var form = $(this);
    var btn = form.find('button[type="submit"], input[type="submit"]');
    var btnOriginalText = btn.html();
    var callback = form.find('input[name="callback"]').val();
    var callback_action = form.find('input[name="callback_action"]').val();

    form.ajaxSubmit({
      url: '_ajax/' + callback + '.ajax.php',
      data: { callback_action: callback_action },
      dataType: 'json',
      beforeSubmit: function () {
        form.find('.form_load').fadeIn('fast');
        $('.trigger_ajax').fadeOut('fast');

        btn.prop('disabled', true).html('<span class="spinner"></span>');
      },
      success: function (data) {
        form.find('.form_load').fadeOut('slow', function () {

          if (data.trigger) {
            Trigger(data.trigger);
          }

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

        });
      },
      complete: function () {
        btn.prop('disabled', false).html(btnOriginalText);
      }
    });

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
    

    // ***** FRASES MOTIVACIONAIS *********
    const frases = [
      "Cada atendimento é uma oportunidade de transformar um sonho em realidade.",
      "Vender é servir: quem serve melhor, vende mais.",
      "Acredite: você é o diferencial que o cliente procura.",
      "Pequenas ações diárias geram grandes resultados.",
      "A persistência separa os vencedores dos desistentes.",
      "Nosso time cresce junto com cada conquista."
    ];

    const $fraseEl = $(".motivational_text");
    let index = 0;

    setInterval(function() {
      index = (index + 1) % frases.length;
      $fraseEl.text(`"${frases[index]}"`);
    }, 6000);

        //############## MASK INPUT
    $(".formPhone").mask("(00) 0000-00009");
    $(".formDate").mask("99/99/9999");
    $(".formTime").mask("99/99/9999 99:99");
    $(".formCep").mask("99999-999");
    $(".formCpf").mask("999.999.999-99");
    $(".formCnpj").mask("99.999.999/9999-99");
    $(".formDecimal").mask("##0,00", { reverse: true });
    $(".formInt").mask("0000");
    $(".formMoney").mask("000.000.000.000.000,00", { reverse: true });
        
    $('.formPhone').mask('(00) 0000-00009', {
        onKeyPress: function(phone, e, field, options) {
            var masks = ['(00) 0000-00009', '(00) 00000-0000'];
            var mask = phone.length > 14 ? masks[1] : masks[0];
            $('.formPhone').mask(mask, options);
        }
    });



});

//############## MODAL MESSAGE
let currentTimer1 = null;
let currentTimer2 = null;

function Trigger(Message) {

    if (currentTimer1) clearTimeout(currentTimer1);
    if (currentTimer2) clearTimeout(currentTimer2);

    $('.trigger_toast, .triggercontroller').stop(true, true).remove();

    $('body').before("<div class='trigger_toast'>" + Message + "</div>");
    const toast = $('.trigger_ajax');

    toast.stop(true, true).fadeIn('fast', function () {
        $(this).addClass("active");
    });

    const progress = $('.progress');
    if (progress.length) {
        progress.stop(true, true).addClass("active");
    }

    currentTimer1 = setTimeout(() => {
        toast.removeClass("active"); 
        setTimeout(() => { 
            $('.trigger_toast').stop(true, true).fadeOut('fast', function() {
                $(this).remove(); 
            });
        }, 200);
    }, 5000);

    currentTimer2 = setTimeout(() => {
        if (progress.length) {
            progress.removeClass("active");
        }
    }, 5300);

    $('.trigger_ajax').on('click', function () {
        toast.removeClass("active"); 
        setTimeout(() => {
            $('.trigger_toast').stop(true, true).fadeOut('fast', function () {
                $(this).remove();
            });
        }, 200);
    });
}

function TriggerClose() {
    $('.trigger_modal').animate({'right': '-100%','opacity': '0'}, 300, function() {
        $(this).remove();
    });
}
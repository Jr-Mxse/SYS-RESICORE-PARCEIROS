// wizard.js (JavaScript Puro - PADRÃO SIMPLIFICADO)
(function() {
    'use strict';
    
    var currentStep = 1;
    var totalSteps = 3;
    var uploadedFiles = [];
    
    // Elementos do DOM
    var btnProximo, btnVoltar, progressFill, uploadArea, fileInput, fileList, wizardForm, wizardContainer;
    
    // Função de inicialização
    function init() {
        btnProximo = document.getElementById('btnProximo');
        btnVoltar = document.getElementById('btnVoltar');
        progressFill = document.getElementById('progressFill');
        uploadArea = document.querySelector('.wizard_upload_area');
        fileInput = document.getElementById('fileUpload');
        fileList = document.getElementById('fileList');
        wizardForm = document.getElementById('wizardForm');
        wizardContainer = document.querySelector('.wizard_container');
        
        if (!btnProximo || !btnVoltar || !wizardForm) {
            console.error('Elementos principais não encontrados');
            return;
        }
        
        setupEvents();
        updateUI();
    }
    
    // Configurar eventos
    function setupEvents() {
        btnProximo.addEventListener('click', function(e) {
            e.preventDefault();
            handleNext();
        });
        
        btnVoltar.addEventListener('click', function(e) {
            e.preventDefault();
            handleBack();
        });
        
        document.body.addEventListener('click', function(e) {
            var target = e.target;
            
            if (target.classList.contains('wizard_option_btn')) {
                e.preventDefault();
                handleOptionButton(target);
            }
            
            if (target.id === 'btnNovoLead') {
                e.preventDefault();
                resetForm();
            }
        });
        
        if (uploadArea && fileInput) {
            uploadArea.addEventListener('click', function() {
                fileInput.click();
            });
            
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    handleFiles(this.files);
                }
            });
            
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('dragleave', handleDragLeave);
            uploadArea.addEventListener('drop', handleDrop);
        }
    }
    
    // Manipular próximo
    function handleNext() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateUI();
        } else if (currentStep === totalSteps) {
            showReview();
            currentStep = 4;
            updateUI();
        } else if (currentStep === 4) {
            submitForm();
        }
    }
    
    // Manipular voltar
    function handleBack() {
        if (currentStep > 1 && currentStep <= 4) {
            currentStep--;
            updateUI();
        }
    }
    
    // Manipular botões de opção
    function handleOptionButton(button) {
        var group = button.parentElement;
        while (group && !group.classList.contains('wizard_button_group')) {
            group = group.parentElement;
        }
        
        if (!group) return;
        
        var hiddenInput = group.nextElementSibling;
        var allButtons = group.querySelectorAll('.wizard_option_btn');
        
        for (var i = 0; i < allButtons.length; i++) {
            allButtons[i].classList.remove('active');
        }
        
        button.classList.add('active');
        
        if (hiddenInput && hiddenInput.type === 'hidden') {
            var dataValue = button.getAttribute('data-value');
            hiddenInput.value = dataValue;
            
            if (hiddenInput.name === 'leads_conhece_residere') {
                var comoConheceuGroup = document.getElementById('comoConheceuGroup');
                if (comoConheceuGroup) {
                    comoConheceuGroup.style.display = dataValue === 'sim' ? 'block' : 'none';
                }
            }
        }
    }

    function openWizardModal(targetSel) {
        var $m = $(targetSel || '#wizardModal');
        var $backdrop = $('#wizardBackdrop');
        
        if (!$m.length) return;
        
        $backdrop.css('display', 'block');
        setTimeout(function() {
            $backdrop.addClass('is-open');
        }, 10);
        
        $m.addClass('is-open').attr('aria-hidden', 'false');
        $('body').addClass('modal-open');
    }

    function closeWizardModal(targetSel) {
        var $m = $(targetSel || '#wizardModal');
        var $backdrop = $('#wizardBackdrop');
        
        if (!$m.length) return;
        
        $m.removeClass('is-open').attr('aria-hidden', 'true');
        $backdrop.removeClass('is-open');
        $('body').removeClass('modal-open');
        
        setTimeout(function() {
            $backdrop.css('display', 'none');
        }, 300);
    }

    $(document).on('click', '[data-wizard="open"], .jOpenWizard', function (e) {
        e.preventDefault();
        openWizardModal($(this).data('wizard-target') || '#wizardModal');
    });

    $(document).on('click', '[data-wizard="close"], .jCloseWizard', function (e) {
        e.preventDefault();
        closeWizardModal($(this).data('wizard-target') || '#wizardModal');
    });

    $(document).on('click', '.wizard_backdrop', function () {
        closeWizardModal('#wizardModal');
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $('#wizardModal').hasClass('is-open')) {
            closeWizardModal('#wizardModal');
        }
    });
    
    // Atualizar interface
    function updateUI() {
        var allContents = document.querySelectorAll('.wizard_step_content');
        for (var i = 0; i < allContents.length; i++) {
            allContents[i].classList.remove('active');
        }
        
        var activeContent = document.querySelector('.wizard_step_content[data-step="' + currentStep + '"]');
        if (activeContent) {
            activeContent.classList.add('active');
        }
        
        if (currentStep === 5) {
            wizardContainer.classList.add('success_mode');
        } else {
            wizardContainer.classList.remove('success_mode');
        }
        
        var allSteps = document.querySelectorAll('.wizard_step');
        for (var j = 0; j < allSteps.length; j++) {
            allSteps[j].classList.remove('active', 'completed');
            var stepNum = j + 1;
            
            if (stepNum < currentStep && currentStep <= 3) {
                allSteps[j].classList.add('completed');
            } else if (stepNum === currentStep && currentStep <= 3) {
                allSteps[j].classList.add('active');
            } else if (currentStep > 3) {
                allSteps[j].classList.add('completed');
            }
        }
        
        var allLines = document.querySelectorAll('.wizard_step_line');
        for (var k = 0; k < allLines.length; k++) {
            var lineNum = k + 1;
            if (lineNum < currentStep || currentStep > 3) {
                allLines[k].classList.add('active');
            } else {
                allLines[k].classList.remove('active');
            }
        }
        
        var progressPercent = currentStep >= 4 ? 100 : (currentStep / totalSteps) * 100;
        if (progressFill) {
            progressFill.style.width = progressPercent + '%';
        }
        
        var navigation = document.querySelector('.wizard_navigation');
        if (navigation) {
            navigation.style.display = currentStep === 5 ? 'none' : 'flex';
        }
        
        btnVoltar.disabled = currentStep === 1;
        updateNextButton();
        window.scrollTo(0, 0);
    }
    
    // Atualizar botão próximo
    function updateNextButton() {
        if (currentStep < totalSteps) {
            btnProximo.innerHTML = 'Próximo <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
        } else if (currentStep === 3) {
            btnProximo.innerHTML = 'Revisar dados <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
        } else if (currentStep === 4) {
            btnProximo.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Enviar Lead';
        }
    }
    
    // Mostrar resumo
    function showReview() {
        setReviewValue('review_nome_completo', getFieldValue('leads_name'));
        setReviewValue('review_telefone', getFieldValue('leads_cell'));
        setReviewValue('review_email', getFieldValue('leads_email'));
        setReviewValue('review_cidade_interesse', getFieldValue('leads_cidade_interesse'));
        setReviewValue('review_possui_terreno', formatValue(getFieldValue('leads_terreno')));
        setReviewValue('review_tipo_construcao', formatValue(getFieldValue('leads_tipo_construcao')));
        setReviewValue('review_faixa_investimento', getSelectText('leads_faixa_investimento'));
        setReviewValue('review_parcela_bolso', getSelectText('leads_parcela_bolso'));
        setReviewValue('review_forma_pagamento', getCheckboxValues('leads_forma_pagamento[]'));
        setReviewValue('review_expectativa_inicio', getSelectText('leads_expectativa_inicio'));
        setReviewValue('review_conhece_residere', formatValue(getFieldValue('leads_conhece_residere')));
        setReviewValue('review_visitou_casa', formatValue(getFieldValue('leads_visitou_casa')));
        setReviewValue('review_finalidade_imovel', formatValue(getFieldValue('leads_finalidade_imovel')));
        setReviewValue('review_prazo_contato', getSelectText('leads_prazo_contato'));
        setReviewValue('review_credito_aprovado', formatValue(getFieldValue('leads_credito_aprovado')));
    }
    
    function getFieldValue(name) {
        var field = document.querySelector('[name="' + name + '"]');
        return field ? field.value : '';
    }
    
    function getSelectText(name) {
        var select = document.querySelector('[name="' + name + '"]');
        if (select && select.selectedIndex >= 0) {
            return select.options[select.selectedIndex].text;
        }
        return '-';
    }
    
    function getCheckboxValues(name) {
        var checkboxes = document.querySelectorAll('[name="' + name + '"]:checked');
        var values = [];
        for (var i = 0; i < checkboxes.length; i++) {
            var label = checkboxes[i].nextElementSibling;
            if (label) values.push(label.textContent);
        }
        return values.length > 0 ? values.join(', ') : '-';
    }
    
    function setReviewValue(id, value) {
        var element = document.getElementById(id);
        if (element) {
            element.textContent = value || '-';
        }
    }
    
    function formatValue(value) {
        if (!value) return '-';
        
        var map = {
            'sim_proprio': 'Sim próprio',
            'sim_familiar': 'Sim familiar',
            'em_negociacao': 'Em negociação',
            'nao': 'Não',
            'sim': 'Sim',
            'casa_terrea': 'Casa térrea',
            'sobrado': 'Sobrado',
            'geminada': 'Geminada',
            'alto_padrao': 'Alto padrão',
            'morar': 'Morar',
            'investir': 'Investir',
            'a_decidir': 'A decidir'
        };
        
        return map[value] || value;
    }
    
    // Drag and drop
    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        this.style.borderColor = '#45b7d1';
        this.style.background = 'rgba(69, 183, 209, 0.05)';
    }
    
    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        this.style.borderColor = '#3d4255';
        this.style.background = 'transparent';
    }
    
    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        this.style.borderColor = '#3d4255';
        this.style.background = 'transparent';
        
        var files = e.dataTransfer.files;
        if (files && files.length > 0) {
            handleFiles(files);
        }
    }
    
    function handleFiles(files) {
        for (var i = 0; i < files.length; i++) {
            addFile(files[i]);
        }
    }
    
    function addFile(file) {
        uploadedFiles.push(file);
        
        var fileItem = document.createElement('div');
        fileItem.className = 'wizard_file_item';
        
        var fileName = document.createElement('span');
        fileName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
        
        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'wizard_file_remove';
        removeBtn.textContent = 'Remover';
        
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            removeFile(file, fileItem);
        });
        
        fileItem.appendChild(fileName);
        fileItem.appendChild(removeBtn);
        fileList.appendChild(fileItem);
    }
    
    function removeFile(file, fileItem) {
        for (var i = 0; i < uploadedFiles.length; i++) {
            if (uploadedFiles[i].name === file.name && uploadedFiles[i].size === file.size) {
                uploadedFiles.splice(i, 1);
                break;
            }
        }
        fileItem.parentNode.removeChild(fileItem);
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    // Submeter formulário final usando o padrão com jQuery ajaxSubmit
    function submitForm() {
        var $form = $(wizardForm);
        var callback = $form.find('input[name="callback"]').val();
        var callback_action = $form.find('input[name="callback_action"]').val();
        
        // Adicionar arquivos ao formulário
        for (var i = 0; i < uploadedFiles.length; i++) {
            var fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'leads_anexos[]';
            fileInput.files = uploadedFiles[i];
            $form.append(fileInput);
        }
        
        $form.ajaxSubmit({
            url: '_ajax/' + callback + '.ajax.php',
            data: {callback_action: callback_action},
            dataType: 'json',
            uploadProgress: function (evento, posicao, total, completo) {
                var porcento = completo + '%';
                console.log('Upload:', porcento);
            },
            success: function (data) {
                if (data.trigger) {
                    Trigger(data.trigger);
                }
                
                if (!data.error) {
                    currentStep = 5;
                    updateUI();
                } else {
                    alert('Erro ao enviar formulário');
                }
            },
            error: function() {
                alert('Erro ao enviar formulário');
            }
        });
    }
    
    // Resetar formulário
    function resetForm() {
        wizardForm.reset();
        uploadedFiles = [];
        fileList.innerHTML = '';
        
        var allButtons = document.querySelectorAll('.wizard_option_btn');
        for (var i = 0; i < allButtons.length; i++) {
            allButtons[i].classList.remove('active');
        }
        
        var firstBtn = document.querySelector('.wizard_option_btn[data-value="sim_proprio"]');
        if (firstBtn) {
            firstBtn.classList.add('active');
        }
        
        var possuiTerreno = document.querySelector('[name="leads_terreno"]');
        if (possuiTerreno) {
            possuiTerreno.value = 'sim_proprio';
        }
        
        var comoConheceuGroup = document.getElementById('comoConheceuGroup');
        if (comoConheceuGroup) {
            comoConheceuGroup.style.display = 'none';
        }
        
        currentStep = 1;
        wizardContainer.classList.remove('success_mode');
        updateUI();
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

//############## MODAL MESSAGE
function Trigger(Message) {
    $('.trigger_ajax').fadeOut('fast', function () {
        $(this).remove();
    });
    $('body').before("<div class='trigger_modal'>" + Message + "</div>");
    $('.trigger_ajax').fadeIn();
}

function TriggerClose() {
    $('.trigger_ajax').fadeOut('fast', function () {
        $(this).remove();
    });
}
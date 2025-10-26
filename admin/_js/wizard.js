// wizard.js (JavaScript Puro - COM OPÇÃO DE AUTO-SAVE)
(function() {
    'use strict';
    
    // ====================================
    // CONFIGURAÇÕES
    // ====================================
    var CONFIG = {
        autoSave: true,  // true = salva a cada etapa | false = salva apenas no final
        autoSaveUrl: 'salvar_etapa.php',  // URL para auto-save
        finalSubmitUrl: 'processar_formulario.php'  // URL para envio final
    };
    // ====================================
    
    var currentStep = 1;
    var totalSteps = 3;
    var uploadedFiles = [];
    var leadId = null;  // ID do lead salvo no banco
    
    // Elementos do DOM
    var btnProximo, btnVoltar, progressFill, uploadArea, fileInput, fileList, wizardForm, wizardContainer;
    
    // Função de inicialização
    function init() {
        // Obter elementos
        btnProximo = document.getElementById('btnProximo');
        btnVoltar = document.getElementById('btnVoltar');
        progressFill = document.getElementById('progressFill');
        uploadArea = document.querySelector('.wizard_upload_area');
        fileInput = document.getElementById('fileUpload');
        fileList = document.getElementById('fileList');
        wizardForm = document.getElementById('wizardForm');
        wizardContainer = document.querySelector('.wizard_container');
        
        // Verificar se elementos existem
        if (!btnProximo || !btnVoltar || !wizardForm) {
            console.error('Elementos principais não encontrados');
            return;
        }
        
        // Adicionar eventos
        setupEvents();
        updateUI();
    }
    
    // Configurar eventos
    function setupEvents() {
        // Botão Próximo
        btnProximo.addEventListener('click', function(e) {
            e.preventDefault();
            handleNext();
        });
        
        // Botão Voltar
        btnVoltar.addEventListener('click', function(e) {
            e.preventDefault();
            handleBack();
        });
        
        // Delegação de eventos para botões de opção
        document.body.addEventListener('click', function(e) {
            var target = e.target;
            
            // Botões de opção
            if (target.classList.contains('wizard_option_btn')) {
                e.preventDefault();
                handleOptionButton(target);
            }
            
            // Botão novo lead
            if (target.id === 'btnNovoLead') {
                e.preventDefault();
                resetForm();
            }
        });
        
        // Upload de arquivos
        if (uploadArea && fileInput) {
            uploadArea.addEventListener('click', function(e) {
                console.log('Upload area clicada');
                fileInput.click();
            });
            
            var uploadChildren = uploadArea.querySelectorAll('*');
            for (var i = 0; i < uploadChildren.length; i++) {
                uploadChildren[i].addEventListener('click', function(e) {
                    e.stopPropagation();
                    fileInput.click();
                });
            }
            
            fileInput.addEventListener('change', function() {
                console.log('Arquivos selecionados:', this.files.length);
                if (this.files && this.files.length > 0) {
                    handleFiles(this.files);
                }
            });
            
            // Drag and drop
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('dragleave', handleDragLeave);
            uploadArea.addEventListener('drop', handleDrop);
        } else {
            console.error('Upload area ou file input não encontrados');
        }
    }
    
    // Manipular próximo
    function handleNext() {
        if (currentStep < totalSteps) {
            // Salvar etapa atual se auto-save estiver ativo
            if (CONFIG.autoSave) {
                saveCurrentStep(function() {
                    currentStep++;
                    updateUI();
                });
            } else {
                currentStep++;
                updateUI();
            }
        } else if (currentStep === totalSteps) {
            // Salvar etapa 3 antes de mostrar resumo
            if (CONFIG.autoSave) {
                saveCurrentStep(function() {
                    showReview();
                    currentStep = 4;
                    updateUI();
                });
            } else {
                showReview();
                currentStep = 4;
                updateUI();
            }
        } else if (currentStep === 4) {
            submitForm();
        }
    }
    
    // Salvar etapa atual via AJAX
    function saveCurrentStep(callback) {
        var formData = new FormData();
        
        // Adicionar ID do lead se já existir
        if (leadId) {
            formData.append('lead_id', leadId);
        }
        
        // Adicionar etapa atual
        formData.append('etapa', currentStep);
        
        // Coletar dados da etapa atual
        var stepData = getStepData(currentStep);
        for (var key in stepData) {
            if (stepData.hasOwnProperty(key)) {
                if (Array.isArray(stepData[key])) {
                    for (var i = 0; i < stepData[key].length; i++) {
                        formData.append(key + '[]', stepData[key][i]);
                    }
                } else {
                    formData.append(key, stepData[key]);
                }
            }
        }
        
        // Adicionar arquivos se estiver na etapa 3
        if (currentStep === 3) {
            for (var j = 0; j < uploadedFiles.length; j++) {
                formData.append('anexo_' + j, uploadedFiles[j]);
            }
        }
        
        console.log('=== SALVANDO ETAPA ' + currentStep + ' ===');
        
        // Fazer requisição AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', CONFIG.autoSaveUrl, true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log('Etapa salva com sucesso:', response);
                    
                    // Salvar ID do lead se retornado
                    if (response.lead_id) {
                        leadId = response.lead_id;
                    }
                    
                    // Executar callback
                    if (callback) callback();
                    
                } catch (e) {
                    console.error('Erro ao processar resposta:', e);
                    if (callback) callback();
                }
            } else {
                console.error('Erro ao salvar etapa:', xhr.status);
                // Continuar mesmo com erro
                if (callback) callback();
            }
        };
        
        xhr.onerror = function() {
            console.error('Erro de rede ao salvar etapa');
            // Continuar mesmo com erro
            if (callback) callback();
        };
        
        xhr.send(formData);
    }
    
    // Obter dados da etapa específica
    function getStepData(step) {
        var data = {};
        
        if (step === 1) {
            // Etapa 1: Dados do Cliente
            data.nome_completo = getFieldValue('nome_completo');
            data.telefone = getFieldValue('telefone');
            data.email = getFieldValue('email');
            data.cidade_interesse = getFieldValue('cidade_interesse');
            data.possui_terreno = getFieldValue('possui_terreno');
            data.endereco_terreno = getFieldValue('endereco_terreno');
            
        } else if (step === 2) {
            // Etapa 2: Perfil do Interesse
            data.tipo_construcao = getFieldValue('tipo_construcao');
            data.faixa_investimento = getFieldValue('faixa_investimento');
            data.parcela_bolso = getFieldValue('parcela_bolso');
            data.forma_pagamento = getCheckboxValuesArray('forma_pagamento[]');
            data.expectativa_inicio = getFieldValue('expectativa_inicio');
            data.conhece_residere = getFieldValue('conhece_residere');
            data.como_conheceu = getFieldValue('como_conheceu');
            data.comentarios_parceiro = getFieldValue('comentarios_parceiro');
            
        } else if (step === 3) {
            // Etapa 3: Qualificação
            data.visitou_casa = getFieldValue('visitou_casa');
            data.finalidade_imovel = getFieldValue('finalidade_imovel');
            data.prazo_contato = getFieldValue('prazo_contato');
            data.credito_aprovado = getFieldValue('credito_aprovado');
            data.casas_interesse = getFieldValue('casas_interesse');
        }
        
        return data;
    }
    
    // Obter valores de checkbox como array
    function getCheckboxValuesArray(name) {
        var checkboxes = document.querySelectorAll('[name="' + name + '"]:checked');
        var values = [];
        for (var i = 0; i < checkboxes.length; i++) {
            values.push(checkboxes[i].value);
        }
        return values;
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
        
        // Remove active de todos
        var allButtons = group.querySelectorAll('.wizard_option_btn');
        for (var i = 0; i < allButtons.length; i++) {
            allButtons[i].classList.remove('active');
        }
        
        // Adiciona active no clicado
        button.classList.add('active');
        
        // Atualiza hidden input
        if (hiddenInput && hiddenInput.type === 'hidden') {
            var dataValue = button.getAttribute('data-value');
            hiddenInput.value = dataValue;
            
            // Lógica especial para "conhece_residere"
            if (hiddenInput.name === 'conhece_residere') {
                var comoConheceuGroup = document.getElementById('comoConheceuGroup');
                if (comoConheceuGroup) {
                    if (dataValue === 'sim') {
                        comoConheceuGroup.style.display = 'block';
                    } else {
                        comoConheceuGroup.style.display = 'none';
                    }
                }
            }
        }
    }
    
    // Atualizar interface
    function updateUI() {
        // Atualizar conteúdo das etapas
        var allContents = document.querySelectorAll('.wizard_step_content');
        for (var i = 0; i < allContents.length; i++) {
            allContents[i].classList.remove('active');
        }
        
        var activeContent = document.querySelector('.wizard_step_content[data-step="' + currentStep + '"]');
        if (activeContent) {
            activeContent.classList.add('active');
        }
        
        // Gerenciar classe success_mode
        if (currentStep === 5) {
            wizardContainer.classList.add('success_mode');
        } else {
            wizardContainer.classList.remove('success_mode');
        }
        
        // Atualizar indicadores
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
        
        // Atualizar linhas
        var allLines = document.querySelectorAll('.wizard_step_line');
        for (var k = 0; k < allLines.length; k++) {
            var lineNum = k + 1;
            if (lineNum < currentStep || currentStep > 3) {
                allLines[k].classList.add('active');
            } else {
                allLines[k].classList.remove('active');
            }
        }
        
        // Barra de progresso
        var progressPercent = currentStep >= 4 ? 100 : (currentStep / totalSteps) * 100;
        if (progressFill) {
            progressFill.style.width = progressPercent + '%';
        }
        
        // Navegação
        var navigation = document.querySelector('.wizard_navigation');
        if (navigation) {
            navigation.style.display = currentStep === 5 ? 'none' : 'flex';
        }
        
        // Botão voltar
        btnVoltar.disabled = currentStep === 1;
        
        // Botão próximo
        updateNextButton();
        
        // Scroll
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
        setReviewValue('review_nome_completo', getFieldValue('nome_completo'));
        setReviewValue('review_telefone', getFieldValue('telefone'));
        setReviewValue('review_email', getFieldValue('email'));
        setReviewValue('review_cidade_interesse', getFieldValue('cidade_interesse'));
        setReviewValue('review_possui_terreno', formatValue(getFieldValue('possui_terreno')));
        
        setReviewValue('review_tipo_construcao', formatValue(getFieldValue('tipo_construcao')));
        setReviewValue('review_faixa_investimento', getSelectText('faixa_investimento'));
        setReviewValue('review_parcela_bolso', getSelectText('parcela_bolso'));
        setReviewValue('review_forma_pagamento', getCheckboxValues('forma_pagamento[]'));
        setReviewValue('review_expectativa_inicio', getSelectText('expectativa_inicio'));
        setReviewValue('review_conhece_residere', formatValue(getFieldValue('conhece_residere')));
        
        setReviewValue('review_visitou_casa', formatValue(getFieldValue('visitou_casa')));
        setReviewValue('review_finalidade_imovel', formatValue(getFieldValue('finalidade_imovel')));
        setReviewValue('review_prazo_contato', getSelectText('prazo_contato'));
        setReviewValue('review_credito_aprovado', formatValue(getFieldValue('credito_aprovado')));
    }
    
    // Auxiliar para pegar valor de campo
    function getFieldValue(name) {
        var field = document.querySelector('[name="' + name + '"]');
        return field ? field.value : '';
    }
    
    // Auxiliar para pegar texto de select
    function getSelectText(name) {
        var select = document.querySelector('[name="' + name + '"]');
        if (select && select.selectedIndex >= 0) {
            return select.options[select.selectedIndex].text;
        }
        return '-';
    }
    
    // Auxiliar para pegar valores de checkbox
    function getCheckboxValues(name) {
        var checkboxes = document.querySelectorAll('[name="' + name + '"]:checked');
        var values = [];
        for (var i = 0; i < checkboxes.length; i++) {
            var label = checkboxes[i].nextElementSibling;
            if (label) {
                values.push(label.textContent);
            }
        }
        return values.length > 0 ? values.join(', ') : '-';
    }
    
    // Auxiliar para setar valor no resumo
    function setReviewValue(id, value) {
        var element = document.getElementById(id);
        if (element) {
            element.textContent = value || '-';
        }
    }
    
    // Formatar valores
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
    
    // Drag and drop handlers
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
    
    // Processar arquivos
    function handleFiles(files) {
        console.log('Processando', files.length, 'arquivo(s)');
        for (var i = 0; i < files.length; i++) {
            addFile(files[i]);
        }
    }
    
    function addFile(file) {
        uploadedFiles.push(file);
        console.log('Arquivo adicionado:', file.name);
        
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
    
    // Submeter formulário final
    function submitForm() {
        var formData = new FormData(wizardForm);
        
        // Adicionar ID do lead se existir
        if (leadId) {
            formData.append('lead_id', leadId);
        }
        
        // Adicionar arquivos
        for (var i = 0; i < uploadedFiles.length; i++) {
            formData.append('anexo_' + i, uploadedFiles[i]);
        }
        
        formData.append('finalizar', '1');
        
        console.log('=== ENVIANDO FORMULÁRIO FINAL ===');
        console.log('Lead ID:', leadId);
        console.log('Total de arquivos:', uploadedFiles.length);
        
        // Envio real
        var xhr = new XMLHttpRequest();
        xhr.open('POST', CONFIG.finalSubmitUrl, true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Sucesso:', xhr.responseText);
                currentStep = 5;
                updateUI();
            } else {
                console.error('Erro:', xhr.status);
                alert('Erro ao enviar formulário');
            }
        };
        
        xhr.onerror = function() {
            console.error('Erro de rede');
            alert('Erro ao enviar formulário');
        };
        
        xhr.send(formData);
        
        // Remova o comentário abaixo se quiser testar sem PHP
        // setTimeout(function() { currentStep = 5; updateUI(); }, 1000);
    }
    
    // Resetar formulário
    function resetForm() {
        wizardForm.reset();
        uploadedFiles = [];
        fileList.innerHTML = '';
        leadId = null;
        
        var allButtons = document.querySelectorAll('.wizard_option_btn');
        for (var i = 0; i < allButtons.length; i++) {
            allButtons[i].classList.remove('active');
        }
        
        var firstBtn = document.querySelector('.wizard_option_btn[data-value="sim_proprio"]');
        if (firstBtn) {
            firstBtn.classList.add('active');
        }
        
        var possuiTerreno = document.querySelector('[name="possui_terreno"]');
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
    
    // Inicializar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

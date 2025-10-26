// wizard.js
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;
    
    const btnVoltar = document.getElementById('btnVoltar');
    const btnProximo = document.getElementById('btnProximo');
    const progressFill = document.getElementById('progressFill');
    
    // Inicialização
    updateUI();
    
    // Botão Próximo
    btnProximo.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateUI();
        } else {
            // Enviar formulário
            submitForm();
        }
    });
    
    // Botão Voltar
    btnVoltar.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateUI();
        }
    });
    
    // Atualizar interface
    function updateUI() {
        // Atualizar etapas visuais
        document.querySelectorAll('.wizard_step_content').forEach(content => {
            content.classList.remove('active');
        });
        document.querySelector(`.wizard_step_content[data-step="${currentStep}"]`).classList.add('active');
        
        // Atualizar indicadores de passo
        document.querySelectorAll('.wizard_step').forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 < currentStep) {
                step.classList.add('completed');
            } else if (index + 1 === currentStep) {
                step.classList.add('active');
            }
        });
        
        // Atualizar linhas entre os passos
        document.querySelectorAll('.wizard_step_line').forEach((line, index) => {
            line.classList.remove('active');
            if (index + 1 < currentStep) {
                line.classList.add('active');
            }
        });
        
        // Atualizar barra de progresso
        const progressPercent = (currentStep / totalSteps) * 100;
        progressFill.style.width = progressPercent + '%';
        
        // Atualizar botão Voltar - sempre visível mas desabilitado na primeira etapa
        if (currentStep === 1) {
            btnVoltar.disabled = true;
        } else {
            btnVoltar.disabled = false;
        }
        
        // Atualizar botão Próximo
        if (currentStep < totalSteps) {
            btnProximo.innerHTML = `Próximo <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>`;
        } else {
            btnProximo.innerHTML = 'Finalizar';
        }
        
        // Scroll para o topo
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Botões de opção (Possui terreno, Tipo de construção, etc.)
    document.querySelectorAll('.wizard_option_btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const group = this.parentElement;
            const hiddenInput = group.nextElementSibling;
            
            // Remover active de outros botões do mesmo grupo
            group.querySelectorAll('.wizard_option_btn').forEach(b => {
                b.classList.remove('active');
            });
            
            // Adicionar active no botão clicado
            this.classList.add('active');
            
            // Atualizar hidden input
            if (hiddenInput && hiddenInput.type === 'hidden') {
                hiddenInput.value = this.dataset.value;
            }
            
            // Lógica especial para "Já conhece a Residere?"
            if (hiddenInput && hiddenInput.name === 'conhece_residere') {
                const comoConheceuGroup = document.getElementById('comoConheceuGroup');
                if (this.dataset.value === 'sim') {
                    comoConheceuGroup.style.display = 'block';
                } else {
                    comoConheceuGroup.style.display = 'none';
                }
            }
        });
    });
    
    // Upload de arquivos
    const uploadArea = document.querySelector('.wizard_upload_area');
    const fileInput = document.getElementById('fileUpload');
    const fileList = document.getElementById('fileList');
    const uploadedFiles = [];
    
    if (uploadArea) {
        uploadArea.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#45b7d1';
            uploadArea.style.background = 'rgba(69, 183, 209, 0.05)';
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#3d4255';
            uploadArea.style.background = 'transparent';
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#3d4255';
            uploadArea.style.background = 'transparent';
            handleFiles(e.dataTransfer.files);
        });
    }
    
    function handleFiles(files) {
        Array.from(files).forEach(file => {
            uploadedFiles.push(file);
            
            const fileItem = document.createElement('div');
            fileItem.className = 'wizard_file_item';
            fileItem.innerHTML = `
                <span>${file.name} (${formatFileSize(file.size)})</span>
                <button type="button" class="wizard_file_remove" onclick="removeFile(this, '${file.name}')">Remover</button>
            `;
            fileList.appendChild(fileItem);
        });
    }
    
    window.removeFile = function(btn, fileName) {
        const index = uploadedFiles.findIndex(f => f.name === fileName);
        if (index > -1) {
            uploadedFiles.splice(index, 1);
        }
        btn.parentElement.remove();
    };
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    // Enviar formulário
    function submitForm() {
        const formData = new FormData(document.getElementById('wizardForm'));
        
        // Adicionar arquivos
        uploadedFiles.forEach((file, index) => {
            formData.append(`anexo_${index}`, file);
        });
        
        console.log('Dados do formulário:', Object.fromEntries(formData));
        
        // Aqui você vai integrar com PHP
        alert('Formulário pronto para enviar! Dados disponíveis no console.');
        
        // Exemplo de envio com PHP:
        /*
        fetch('processar_formulario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Sucesso:', data);
        })
        .catch(error => {
            console.error('Erro:', error);
        });
        */
    }
});

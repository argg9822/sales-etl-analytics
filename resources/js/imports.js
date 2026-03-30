
const SUBMIT_BUTTON = document.querySelector('button[type="submit"]') || { disabled: false, textContent: '' };
const FILE_NAME_DISPLAY = document.getElementById('file-name');

export function sendFile() {
    const form = document.querySelector('form');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    if(!form || !errorMessage) return;
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        sendingMode();
        send();
    });

    const send = () => {
        const fileInput = document.getElementById('file');
        const formData = new FormData();

        if(fileInput.files.length === 0) {
            alert('Por favor, selecciona un archivo CSV para importar.');
            return;
        }

        formData.append('csv_file', fileInput.files[0]);
        const routeUrl = route('imports.store');        

        axios.post(routeUrl, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            fileInput.value = '';
            FILE_NAME_DISPLAY.textContent = '';
            const routeDetails = route('imports.index.web');

            SUBMIT_BUTTON.textContent = 'Cargar';
            successMessage.innerHTML = `${response?.data?.message}, <a href="${routeDetails}">ver detalles</a>`  || 'Archivo cargado exitosamente.';
            errorMessage.style.display = 'none';
            successMessage.style.display = 'block';
        })
        .catch(error => {
            SUBMIT_BUTTON.disabled = false;
            SUBMIT_BUTTON.textContent = 'Cargar';
            errorMessage.textContent = error.response?.data?.message || 'Ocurrió un error al enviar el archivo.';
            errorMessage.style.display = 'block';
            console.error(error);
            
        });
    }
}

function sendingMode() {
    SUBMIT_BUTTON.disabled = true;
    SUBMIT_BUTTON.textContent = 'Cargando archivo...';
}

export function UIEvents() {
    const fileInput = document.getElementById('file');
    const uploadActions = document.querySelectorAll('.upload-action');

    if(!fileInput || !uploadActions.length) return;

    uploadActions.forEach(action => {
        action.addEventListener('click', function() {
            fileInput.click();
        });
    });

    dragFile(fileInput);
}

function dragFile(fileInput) {
    const containerDragZone = document.querySelector('.container-dropzone');

    if(!containerDragZone || !FILE_NAME_DISPLAY) return;
    
    if(containerDragZone){  
        containerDragZone.addEventListener('dragover', function(event) {
            event.preventDefault();
            containerDragZone.classList.add('dragover');
        });

        containerDragZone.addEventListener('dragleave', function(event) {
            event.preventDefault();
            containerDragZone.classList.remove('dragover');
        });

        containerDragZone.addEventListener('drop', function(event) {
            event.preventDefault();
            containerDragZone.classList.remove('dragover');

            if(fileInput) {
                fileInput.files = event.dataTransfer.files;
            }

            if(FILE_NAME_DISPLAY && fileInput.files.length > 0) {
                FILE_NAME_DISPLAY.textContent = fileInput.files[0].name;
                SUBMIT_BUTTON.disabled = false;
            } else {
                FILE_NAME_DISPLAY.textContent = '';
                SUBMIT_BUTTON.disabled = true;
            }
        });
    }
}
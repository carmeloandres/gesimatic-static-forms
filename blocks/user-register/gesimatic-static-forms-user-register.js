const startTime = Date.now();

/**
 * Función genérica para enviar datos a la REST API de WordPress
 * @param {FormData} formData - Los datos del formulario capturados
 * @param {Object} config - El objeto de configuración (restUrl, nonce, labels)
 * @param {HTMLFormElement} form - Referencia al formulario para feedback visual
 */
const enviarDatosAPI = async (formData, config, form) => {
    // 1. Mostramos un estado de "Cargando" en el botón
    const submitButton = form.querySelector('.gesimatic-form__button');
    const originalButtonText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Enviando...';

    // 2. Convertimos FormData a un objeto simple para el cuerpo del JSON
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch(config.restUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': config.nonce // Seguridad obligatoria para la REST API
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // ÉXITO: WordPress respondió con wp_send_json_success
            alert(config.successLabel || 'Usuario registrado con éxito.');
            form.reset(); // Limpiamos el formulario
        } else {
            // ERROR: WordPress respondió con wp_send_json_error o error de red
            const errorMessage = result.data?.message || config.warningLabel || 'Error desconocido';
            alert(`Atención: ${errorMessage}`);
        }

    } catch (error) {
        // FALLO CRÍTICO: El servidor no responde o hay un error de red
        console.error('Error en la petición:', error);
        alert('Error de conexión con el servidor. Inténtalo más tarde.');
    } finally {
        // 3. Restauramos el botón pase lo que pase
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
    }
};

const gesimaticStaticFormsFormSubmit = (e) => {
    const form = e.target;
    console.log('form :',form);
    
    if (!form.checkValidity()) {
        form.reportValidity(); // Muestra el mensaje de error (el "title") al usuario
        return; // Detiene la ejecución y no envía a la API    
    }
    
    e.preventDefault();
    const formData = new FormData(form);
    console.log ('formData :',formData);
    let config = JSON.parse(form.dataset.config);
    
    const notice = form.querySelector('[data-gesimatic="alert"]');
 //   console.log('notice :', notice);
 //   console.log ('waringLabel:',config.warningLabel)
    notice.classList.replace('display-none','gesimatic-alert-warning');
    notice.innerHTML = config.warningLabel;
    
//    console.log('form :', form);
//    console.log('formData :',formData)
    alert(config.warningLabel + ": El nombre es demasiado corto.");
    // time trap
/*    if ((Date.now() - startTime) < 2000 ){
        return
    }
 /*   // check validation, this field must be empty
    const gesimaticWebsite = formData.get('gesimatic_website').trim();
    if(gesimaticWebsite != ''){
        return;
    }
/*
    // 1. Obtener valores
    const userName = formData.get('user_name').trim();
    const userEmail = formData.get('user_email').trim();

    // 2. Validar Nombre (mínimo 3 caracteres)
    if (userName.length < 3) {
        alert(config.warningLabel + ": El nombre es demasiado corto.");
        return; // Detenemos la ejecución
    }

    // 3. Validar Email con RegEx (Refuerzo del type="email")
    const emailRegEx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegEx.test(userEmail)) {
        alert(config.warningLabel + ": El formato del correo no es válido.");
        return;
    }
// 4. Si todo está bien, procedemos al Fetch
  //  enviarDatosAPI(formData, config, form);
  */
};

window.onload = () => {
    // Adding the submit handler to all gesimatic-static-forms
    let forms = Array.from(document.getElementsByClassName('wp-block-gesimatic-static-forms-user-register'));

    forms.forEach( form => {
        let formId = form.id;
        let config = JSON.parse(form.dataset.config);
//        console.log('formId :',formId);
//        gesimaticStaticFormsUserRegister = [...gesimaticStaticFormsUserRegister,{formId:{'name':'','email':''}}]
        console.log('config : ', config)
       form.addEventListener('submit',gesimaticStaticFormsFormSubmit);
       console.log('form submit handler added');      
    });
//console.log('gesimaticStaticFormsUserRegister :',gesimaticStaticFormsUserRegister);
}

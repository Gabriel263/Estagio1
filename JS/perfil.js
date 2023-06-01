function openFileExplorer() {
    var fileInput = document.getElementById('fileInput');
    fileInput.click();
}

function handleFileChange() {
    var fileInput = document.getElementById('fileInput');
    var submitButton = document.getElementById('submitButton');
    var clearButton = document.getElementById('clearButton');
    var hasImage = document.getElementById('hasImage').value === 'true';
    
    //aplicação de estilização
    var buttonStyles = {
        display: 'none',
        backgroundColor: '#f9f9f9',
        border: 'none',
        borderRadius: '5px',
        padding: '5px',
        margin: '5px',
        cursor: 'pointer',
        boxShadow: '0px 4px 8px 0px rgba(0,0,0,0.2)',
        transition: '0.3s',
        color: 'black',
    };
    
    // Apply the styles
    for (var style in buttonStyles) {
        submitButton.style[style] = buttonStyles[style];
        clearButton.style[style] = buttonStyles[style];
    }
    
    // Change text color on mouse over
    submitButton.onmouseover = clearButton.onmouseover = function() {
        this.style.color = 'red';
    };
    submitButton.onmouseout = clearButton.onmouseout = function() {
        this.style.color = 'black';
    };
    if (fileInput.files.length > 0) {
        var selectedFile = fileInput.files[0];
        var imageName = selectedFile.name;

        if (imageName !== '' || hasImage) {
            submitButton.style.display = 'block';
            clearButton.style.display = 'block';
        } else {
            submitButton.style.display = 'none';
            clearButton.style.display = 'none';
        }
    } else {
        submitButton.style.display = 'none';
        clearButton.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', handleFileChange);

document.getElementById("imageForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('../PHP/upload_img.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            return response.text();
        } else {
            throw new Error('Error: ' + response.statusText);
        }
    }).then(data => {
        if (data === "Imagem enviada com sucesso.") {
            // You can now update the user's image on the page using JavaScript
            location.reload(); // Alternatively, refresh the page to show the updated image
        } else {
            console.error('Error:', data);
        }
    }).catch(error => console.error('Error:', error));
});

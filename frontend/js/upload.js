document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.getElementById('uploadForm');

    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(uploadForm);
        

        fetch('http://localhost:5001/backend/api/upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json)
        .then(result => {
            console.log(result);
            if (result.success) {
                alert('Video uploaded successfully')
            } else {
                alert('Upload failed: ' + result.message)
            }
        })
        .catch(error => {
            console.error('Error:', error )
        })
        
    })
})
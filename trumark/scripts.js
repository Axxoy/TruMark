const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');
let drawing = false;

setWhiteBackground();

canvas.addEventListener('mousedown', () => {
    drawing = true;
});

canvas.addEventListener('mouseup', () => {
    drawing = false;
    ctx.beginPath();
});

canvas.addEventListener('mousemove', draw);

function draw(event) {
    if (!drawing) return;
    ctx.lineWidth = 10;
    ctx.lineCap = 'round';
    ctx.strokeStyle = 'black';
    ctx.lineTo(event.clientX - canvas.offsetLeft, event.clientY - canvas.offsetTop);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(event.clientX - canvas.offsetLeft, event.clientY - canvas.offsetTop);
}

function saveSignature() {
    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = 576;
    tempCanvas.height = 343;
    const tempCtx = tempCanvas.getContext('2d');
    tempCtx.fillStyle = "white";
    tempCtx.fillRect(0, 0, 576, 343);
    tempCtx.drawImage(canvas, 0, 0, canvas.width, canvas.height, 0, 0, 576, 343);
    
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://127.0.0.1:65431', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let responseMessage = xhr.responseText;
            
            // Populate the hidden form and submit it
            document.getElementById('hiddenInput').value = responseMessage;
            document.getElementById('hiddenForm').submit();
        }
    };
        
    let formData = new FormData();
    formData.append('file', dataURLtoBlob(tempCanvas.toDataURL("image/png")));
    xhr.send(formData);
}

function dataURLtoBlob(dataURL) {
    let byteString = atob(dataURL.split(',')[1]);
    let ab = new ArrayBuffer(byteString.length);
    let ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {type: 'image/png'});
}

function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    setWhiteBackground();
}

function setWhiteBackground() {
    ctx.fillStyle = "white";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
}

document.addEventListener('DOMContentLoaded', function () {
    if (os == 'linux') {
        const downloadLink = document.querySelector('.actions > a');
        if (downloadLink) {
            downloadLink.href = repositoryUrl + '/releases/latest';
            downloadLink.target = '_blank';
            downloadLink.removeAttribute('onclick');
        }
    }
});
function start_download() {
    const progressBar = document.getElementById('progress-bar');
    const downloadCompleteMessage = document.getElementById('downloadCompleteMessage');
    
    // Set the download complete message based on OS and method
    if (os == 'win32') {
        if(method == 'installed') {
            var downloadUrl = `uploads/Sploder-Setup-${version}-${arch}.exe`;
            downloadCompleteMessage.innerHTML = 'The update has been downloaded<br>To install it, click save and run the setup file';
        } else {
            var downloadUrl = `uploads/Sploder-Portable-${version}-${arch}.zip`;
            downloadCompleteMessage.innerHTML = 'The update has been downloaded<br>To run it, extract the zip file and run the executable';
        }
    } else if (os == 'darwin') {
        var downloadUrl = `uploads/Sploder-macOS-${version}.zip`;
        downloadCompleteMessage.innerHTML = 'The update has been downloaded<br>To install it, extract the zip file and drag to Applications folder';
    } else if (os == 'linux') {
        // Redirect users to the GitHub releases page for Linux in a new tab
        // This is done by setting the anchor element's href and target attributes
        // on DOM content load
        return;
    }
    fetch(downloadUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const progressContainer = document.getElementById('progress-container');
            progressContainer.style.display = 'block';
            const contentLength = +response.headers.get('Content-Length');
            const reader = response.body.getReader();
            let receivedLength = 0;
            const chunks = [];

            return new ReadableStream({
                start(controller) {
                    function push() {
                        reader.read().then(({ done, value }) => {
                            if (done) {
                                controller.close();
                                const blob = new Blob(chunks); // Combine all chunks into a Blob
                                const url = URL.createObjectURL(blob);
                                setTimeout(() => {
                                    const progressContainer = document.getElementById('finished');
                                    progressContainer.style.display = 'flex';

                                    // Trigger the browser save dialog
                                    const a = document.createElement('a');
                                    a.href = url;
                                    
                                    // Set appropriate filename based on OS and method
                                    if (os == 'win32') {
                                        if(method == 'installed') {
                                            a.download = `Sploder-Setup-${version}-${arch}.exe`;
                                        } else {
                                            a.download = `Sploder-Portable-${version}-${arch}.zip`;
                                        }
                                    } else if (os == 'darwin') {
                                        a.download = `Sploder-macOS-${version}.zip`;
                                    }
                                    
                                    document.body.appendChild(a);
                                    a.click();
                                    document.body.removeChild(a);

                                    // Revoke the object URL after the download
                                    URL.revokeObjectURL(url);
                                }, 1000); // Wait for 1 second (1000 milliseconds)
                            } else {
                                if (value) {
                                    chunks.push(value);
                                    receivedLength += value.length;

                                    const percentComplete = (receivedLength / contentLength) * 100;
                                    progressBar.style.width = `${percentComplete}%`;

                                    controller.enqueue(value);
                                    push();
                                }
                            }
                        }).catch(err => console.error(err));
                    }
                    push();
                }
            });
        })
        .catch(err => console.error('Error during fetch:', err));
}
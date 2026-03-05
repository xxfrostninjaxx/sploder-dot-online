window.onload = function () {

    document.getElementById("login_username").focus();
    setUsername();

}

// Open IndexedDB connection
function openDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('loginDB', 1);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            db.createObjectStore('loginStore', { keyPath: 'id' });
        };

        request.onsuccess = (event) => {
            resolve(event.target.result);
        };

        request.onerror = (event) => {
            reject(event.target.error);
        };
    });
}

// Store username in IndexedDB
async function storeUsername() {
    const db = await openDatabase();
    const transaction = db.transaction('loginStore', 'readwrite');
    const store = transaction.objectStore('loginStore');
    const username = document.getElementById('login_username').value;
    store.put({ id: 'username', value: username });
}

// Set username upon re-login
async function setUsername() {
    try {
        const db = await openDatabase();
        const transaction = db.transaction('loginStore', 'readonly');
        const store = transaction.objectStore('loginStore');
        const request = store.get('username');

        const result = await new Promise((resolve, reject) => {
            request.onsuccess = (event) => resolve(event.target.result);
            request.onerror = (event) => reject(event.target.error);
        });

        if (result) {
            const username = result.value;
            document.getElementById('login_username').value = username;
            // If username is not null, focus on password
            if (username != null) {
                document.getElementById('login_password').focus();
            }
        }
    } catch (err) {
        console.error('Error retrieving username:', err);
    }
}
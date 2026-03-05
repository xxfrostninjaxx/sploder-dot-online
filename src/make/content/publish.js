function showDescription() {
    document.getElementById('description').style.display = 'block';
}

function hideDescription() {
    document.getElementById('description').style.display = 'none';
}

function showDescriptionBox() {
    document.getElementById('descriptionBox').style.display = 'block';
}

function hideDescriptionBox() {
    document.getElementById('descriptionBox').style.display = 'none';
}

function sendDescription() {
    var description = document.getElementById('descriptionTextarea').value;
    if (!/^[a-zA-Z0-9 !@#$%^&*()_+{}|:"<>?`\-=\[\]\\;\',.\/\n\r]*$/.test(description)) {
        setMessageType('alert');
        document.getElementById('message').innerHTML = 'Description contains invalid characters. Please use only alphabets, numbers, spaces and standard symbols.';
        showMessage();
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'description.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('id=' + id + '&description=' + description);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Compress more than 2 newlines to 2 newlines
                description = description.replace(/[\r\n]{3,}/g, "\n\n");
                // Trim trailing newlines and spaces
                description = description.replace(/[\r\n\s]+$/, '');
                // Escape HTML
                description = escapeHtml(description);
                // Change newline to <br>
                description = description.replace(/\n/g, '<br>');
                document.getElementsByClassName('description')[0].innerHTML = description;
                hideDescription();
                setMessageType('prompt');
                document.getElementById('message').innerHTML = 'Game Description saved.';
                if (description.length > 0) {
                    showDescriptionBox();
                } else {
                    hideDescriptionBox();
                }
            } else {
                setMessageType('alert');
                document.getElementById('message').innerHTML = 'Failed to save description. Please try again later.';
            }
            showMessage();
        }
    }
}

function sendTags() {
    var tags = document.getElementById('tagsText').value;
    // Check whether each tag is valid
    // For a tag to be valid, it must be less than 30 characters long
    // It also must have only letters and numbers
    var tagArray = tags.split(' ');
    var cleanTagArray = [];

    for (let tag of tagArray) {
        // If a tag is empty, remove it from the array
        if (tag == '') {
            continue;
        }
        // Change tag to lowercase
        tag = tag.toLowerCase();
        if (tag.length > 30) {
            setMessageType('alert');
            document.getElementById('message').innerHTML = 'Tags must be less than 30 characters long.';
            showMessage();
            return;
        }
        if (!/^[a-z0-9]*$/.test(tag)) {
            setMessageType('alert');
            document.getElementById('message').innerHTML = 'Tags must contain only letters and numbers. Use spaces to separate tags';
            showMessage();
            return;
        }
        cleanTagArray.push(tag);
    }
    tagArray = cleanTagArray;
    // There must not be more than 25 tags
    if (tagArray.length > 25) {
        setMessageType('alert');
        document.getElementById('message').innerHTML = 'You can only have up to 25 tags.';
        showMessage();
        return;
    }
    // There cannot be 2 tags with the same name
    var tagSet = new Set(tagArray);
    if (tagSet.size != tagArray.length) {
        setMessageType('alert');
        document.getElementById('message').innerHTML = 'You cannot have 2 tags with the same name.';
        showMessage();
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'tags.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('id=' + id + '&tags=' + tags);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                setMessageType('prompt');
                document.getElementById('message').innerHTML = 'Game Tags saved.';
            } else {
                setMessageType('alert');
                document.getElementById('message').innerHTML = 'Failed to save tags. Please try again later.';
            }
            showMessage();
        }
    }
}

function showMessage() {
    document.getElementById('promptBr').style.display = 'none';
    document.getElementById('message').style.display = 'block';
}

function setMessageType(type) {
    document.getElementById('message').className = type;
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
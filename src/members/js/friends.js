function handleAddFriend(e, username) {
    // Prevent default link behavior
    e.preventDefault();
    
    // Store the clicked link element
    const linkElement = e.currentTarget;
    
    // Show loading state
    linkElement.innerHTML = 'Working...';
    linkElement.style.cssText = 'color: #fff; cursor: wait;';
    
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    
    // Configure the request
    xhr.open('GET', `/friends/php/request.php?username=${encodeURIComponent(username)}`, true);
    
    // Set up the callback for when the request completes
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            // Check the response URL for error parameters
            const responseUrl = xhr.responseURL;
            const urlParams = new URL(responseUrl).searchParams;
            const error = urlParams.get('err');
            
            switch(error) {
                case 'no':
                    alert('User not found');
                    // Reset the button
                    linkElement.innerHTML = 'ADD FRIEND';
                    linkElement.style.cssText = 'cursor: pointer;';
                    break;
                case 'sent':
                    linkElement.innerHTML = 'Request already sent!';
                    linkElement.style.cssText = 'color: #fff; cursor: default;';
                    linkElement.onclick = null;
                    break;
                case 'you':
                    alert('You cannot add yourself as a friend');
                    // Reset the button
                    linkElement.innerHTML = 'ADD FRIEND';
                    linkElement.style.cssText = 'cursor: pointer;';
                    break;
                case 'suc':
                    linkElement.innerHTML = 'Friend request sent!';
                    linkElement.style.cssText = 'color: #fff; cursor: default;';
                    linkElement.onclick = null;
                    break;
                case 'that':
                    linkElement.innerHTML = 'You are already friends!';
                    linkElement.style.cssText = 'color: #fff; cursor: default;';
                    linkElement.onclick = null;
                    break;
                default:
                    alert('An error occurred while processing your request');
                    // Reset the button
                    linkElement.innerHTML = 'ADD FRIEND';
                    linkElement.style.cssText = 'cursor: pointer;';
            }
        }
    };
    
    // Send the request
    xhr.send();
}

function handleRemoveFriend(e, username) {
    // Prevent default link behavior
    e.preventDefault();
    
    // Confirm before removing friend
    if (!confirm(`Are you sure you want to remove ${username} from your friends list?`)) {
        return;
    }
    
    // Store the clicked link element
    const linkElement = e.currentTarget;
    
    // Show loading state
    linkElement.innerHTML = 'Working...';
    linkElement.style.cssText = 'color: #fff; cursor: wait;';
    
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    
    // Configure the request
    xhr.open('GET', `/friends/php/unfriend.php?u=${encodeURIComponent(username)}`, true);
    
    // Set up the callback for when the request completes
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Replace the Remove Friend button with Add Friend button
                linkElement.innerHTML = 'ADD FRIEND';
                linkElement.style.cssText = 'cursor: pointer;';
                linkElement.onclick = (e) => handleAddFriend(e, username);
            } else {
                // On error, reset the button
                alert('An error occurred while removing friend');
                linkElement.innerHTML = 'REMOVE FRIEND';
                linkElement.style.cssText = 'cursor: pointer;';
            }
        }
    };
    
    // Send the request
    xhr.send();
}

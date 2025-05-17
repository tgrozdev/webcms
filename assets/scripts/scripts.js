// Set up global error handler
window.onerror = function(message, source, lineno, colno, error) {
    // Create error data object
    const errorData = {
        action: 'errorlogger', // Added action parameter
        message: message,
        source: source,
        line: lineno,
        column: colno,
        stack: error ? error.stack : 'No stack trace available',
        timestamp: new Date().toISOString(),
        userAgent: navigator.userAgent,
        url: window.location.href
    };

    // Send error data to server via AJAX
    sendErrorToServer(errorData);

    // Return false to prevent default browser error handling
    return false;
};

// Function to send error data to server
function sendErrorToServer(errorData) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/log-error.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Handle AJAX errors
    xhr.onerror = function() {
        console.error('Failed to send error to server');
    };

    // Send the error data
    xhr.send(JSON.stringify(errorData));
}

// Optional: Handle unhandled promise rejections
window.addEventListener('unhandledrejection', function(event) {
    const errorData = {
        action: 'errorlogger', // Added action parameter
        message: event.reason.message || 'Unhandled promise rejection',
        source: 'Promise',
        line: 0,
        column: 0,
        stack: event.reason.stack || 'No stack trace available',
        timestamp: new Date().toISOString(),
        userAgent: navigator.userAgent,
        url: window.location.href
    };

    sendErrorToServer(errorData);
});
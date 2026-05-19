(function() {
    var trackerUrl = 'http://localhost:8000/api/track';
    
    if (!sessionStorage.getItem('visit_tracked')) {
        fetch(trackerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                url: window.location.href,
                referrer: document.referrer
            })
        }).then(function(response) {
            if (response.ok) {
                sessionStorage.setItem('visit_tracked', 'true');
            }
        }).catch(function(error) {
            console.error('Tracker error:', error);
        });
    }
})();

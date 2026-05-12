document.addEventListener('DOMContentLoaded', function() {
    function updateTime() {
        const now = new Date();
        const timeDisplay = document.getElementById('timeDisplay');
        
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();
        
        // Add leading zeros
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        
        timeDisplay.textContent = `${hours}:${minutes}:${seconds} WITA`;
    }
    
    // Update time every second
    setInterval(updateTime, 1000);
    updateTime(); // Initial call
});
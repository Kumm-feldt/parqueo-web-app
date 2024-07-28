
function updateTimeDisplay(hour, minute) {
    document.getElementById('hour').innerText = hour.toString().padStart(2, '0');
    document.getElementById('minute').innerText = minute.toString().padStart(2, '0');
    document.getElementById('timeDisplay').innerText = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
}

function increaseHour() {
    hour = (hour + 1) % 24;
    updateTimeDisplay();
}

function decreaseHour() {
    hour = (hour - 1 + 24) % 24;
    updateTimeDisplay();
}

function increaseMinute() {
    minute = (minute + 1) % 60;
    updateTimeDisplay();
}

function decreaseMinute() {
    minute = (minute - 1 + 60) % 60;
    updateTimeDisplay();
}


const calendar = document.getElementById('calendar');
const today = new Date();
const firstDayOfWeek = today.getDay();

for (let i = 1; i < (firstDayOfWeek === 0 ? 7 : firstDayOfWeek); i++) {
    const emptyDiv = document.createElement('div');
    emptyDiv.className = 'calendar-day empty box';
    calendar.appendChild(emptyDiv);
}

for (let i = 0; i < 30; i++) {
    const day = new Date();
    day.setDate(today.getDate() + i);

    const dayElement = document.createElement('div');
    dayElement.className = 'calendar-day box';
    if (i === 0) dayElement.classList.add('today');
    dayElement.textContent = `${day.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long' })}\n${day.toLocaleDateString('tr-TR', {weekday: 'long'})}`;
    dayElement.onclick = () => {
        const selectedDate = day.toISOString().split('T')[0]; 
        window.location.href = `yemek_takvimi_info.php?date=${selectedDate}`;
    };

    calendar.appendChild(dayElement);
}
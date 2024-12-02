const calendar = document.getElementById('calendar');
const today = new Date();
const firstDayOfWeek = today.getDay();

for (let i = 1; i < (firstDayOfWeek === 0 ? 7 : firstDayOfWeek); i++) {
    const emptyDiv = document.createElement('div');
    emptyDiv.className = 'calendar-day empty';
    calendar.appendChild(emptyDiv);
}

for (let i = 0; i < 30; i++) {
    const day = new Date();
    day.setDate(today.getDate() + i);

    const dayElement = document.createElement('div');
    dayElement.className = 'calendar-day';
    if (i === 0) dayElement.classList.add('today');
    dayElement.textContent = `${day.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long' })}\n${day.toLocaleDateString('tr-TR', {weekday: 'long'})}`;

    const optionsContainer = document.createElement('div');
    optionsContainer.className = 'meal-options';

    const breakfastOption = document.createElement('label');
    breakfastOption.className = 'meal-option';
    const breakfastCheckbox = document.createElement('input');
    breakfastCheckbox.type = 'checkbox';
    breakfastCheckbox.name = `breakfast-${day.toISOString().split('T')[0]}`;
    breakfastOption.appendChild(breakfastCheckbox);
    breakfastOption.appendChild(document.createTextNode(' Kahvaltı'));
    optionsContainer.appendChild(breakfastOption);

    const lunchOption = document.createElement('label');
    lunchOption.className = 'meal-option';
    const lunchCheckbox = document.createElement('input');
    lunchCheckbox.type = 'checkbox';
    lunchCheckbox.name = `lunch-${day.toISOString().split('T')[0]}`;
    lunchOption.appendChild(lunchCheckbox);
    lunchOption.appendChild(document.createTextNode(' Öğle Yemeği'));
    optionsContainer.appendChild(lunchOption);

    const dinnerOption = document.createElement('label');
    dinnerOption.className = 'meal-option';
    const dinnerCheckbox = document.createElement('input');
    dinnerCheckbox.type = 'checkbox';
    dinnerCheckbox.name = `dinner-${day.toISOString().split('T')[0]}`;
    dinnerOption.appendChild(dinnerCheckbox);
    dinnerOption.appendChild(document.createTextNode(' Akşam Yemeği'));
    optionsContainer.appendChild(dinnerOption);

    dayElement.appendChild(optionsContainer);

    calendar.appendChild(dayElement);
}
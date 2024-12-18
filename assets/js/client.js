// partie calendar
let currentDate = new Date();
const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

function updateCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Update month display
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;

    // Clear previous days
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';

    // Get first day of month and total days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Add empty cells for days before first day of month
    for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'h-16';
        calendarDays.appendChild(emptyDay);
    }

    // Add days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('button');
        const isToday = new Date().toDateString() === new Date(year, month, day).toDateString();
        
        dayElement.className = `h-16 rounded-lg border border-gray-200 hover:border-blue-500 transition-colors ${
            isToday ? 'bg-blue-50 border-blue-500' : ''
        }`;
        
        dayElement.innerHTML = `<span class="block text-right p-2 ${
            isToday ? 'text-blue-600 font-semibold' : ''
        }">${day}</span>`;

        dayElement.addEventListener('click', () => {
            const selectedDate = new Date(year, month, day);
            openModal(selectedDate);
        });

        calendarDays.appendChild(dayElement);
    }
}

// Event listeners for navigation
document.getElementById('prevMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendar();
});

// Initialize calendar
updateCalendar();

// partie data
const lawyers = [
    {
        id: 1,
        name: "Me. Sophie Martin",
        specialties: ["Civil", "Family"]
    },
    {
        id: 2,
        name: "Me. Thomas Bernard",
        specialties: ["Criminal", "Administrative"]
    },
    {
        id: 3,
        name: "Me. Julie Dubois",
        specialties: ["Commercial", "Civil"]
    },
    {
        id: 4,
        name: "Me. Pierre Lambert",
        specialties: ["Criminal", "Administrative", "Civil"]
    }
];

// partie  modal de reservation
function openModal(date) {
    const modal = document.getElementById('appointmentModal');
    const modalDate = document.getElementById('modalDate');
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    
    modalDate.textContent = `Rendez-vous du ${date.toLocaleDateString('fr-FR', options)}`;
    modal.classList.remove('hidden');

    // Reset form
    document.getElementById('appointmentForm').reset();
    document.getElementById('lawyer').disabled = true;
    document.getElementById('lawyer').innerHTML = '<option value="">Sélectionnez d\'abord un type de jugement</option>';
}

// Close modal
document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('appointmentModal').classList.add('hidden');
});

// Handle judgment type selection
document.getElementById('judgmentType').addEventListener('change', (e) => {
    const selectedType = e.target.value;
    const lawyerSelect = document.getElementById('lawyer');
    
    if (!selectedType) {
        lawyerSelect.disabled = true;
        lawyerSelect.innerHTML = '<option value="">Sélectionnez d\'abord un type de jugement</option>';
        return;
    }

    // Filter lawyers by selected type
    const availableLawyers = lawyers.filter(lawyer => lawyer.specialties.includes(selectedType));
    
    lawyerSelect.disabled = false;
    lawyerSelect.innerHTML = '<option value="">Sélectionnez un avocat</option>' +
        availableLawyers.map(lawyer => 
            `<option value="${lawyer.id}">${lawyer.name}</option>`
        ).join('');
});

// Handle form submission
document.getElementById('appointmentForm').addEventListener('submit', (e) => {
    e.preventDefault();
    
    const appointment = {
        date: document.getElementById('modalDate').textContent,
        judgmentType: document.getElementById('judgmentType').value,
        lawyerId: document.getElementById('lawyer').value
    };
    
    console.log('Nouveau rendez-vous:', appointment);
    // Here you would typically save the appointment to a backend
    
    document.getElementById('appointmentModal').classList.add('hidden');
});
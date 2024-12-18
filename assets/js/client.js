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



// rendez vous 
// Current client ID (simulated authentication)
const currentClientId = 1;

function createAppointmentCard(appointment) {
    const formattedDateTime = dateFormatter.formatDateTime(appointment.date, appointment.time);
    const isUpcoming = dateFormatter.isUpcoming(appointment.date);

    return `
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-semibold text-lg">${appointment.lawyerName}</h3>
                    <p class="text-gray-600">${formattedDateTime}</p>
                    <p class="text-gray-600">Type: ${appointment.type}</p>
                    <p class="mt-2">
                        <span class="px-3 py-1 rounded-full text-sm ${getStatusStyle(appointment.status)}">
                            ${getStatusText(appointment.status)}
                        </span>
                    </p>
                </div>
                ${isUpcoming && appointment.status !== 'cancelled' ? `
                    <div class="space-x-2">
                        <button onclick="openEditModal(${appointment.id})" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Modifier
                        </button>
                        <button onclick="cancelAppointment(${appointment.id})"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Annuler
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

function getStatusStyle(status) {
    const styles = {
        pending: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
        completed: 'bg-gray-100 text-gray-800'
    };
    return styles[status] || '';
}

function getStatusText(status) {
    const texts = {
        pending: 'En attente',
        confirmed: 'Confirmé',
        cancelled: 'Annulé',
        completed: 'Terminé'
    };
    return texts[status] || status;
}

function openEditModal(appointmentId) {
    const appointment = appointments.find(apt => apt.id === appointmentId);
    if (appointment) {
        document.getElementById('editAppointmentId').value = appointmentId;
        document.getElementById('editDate').value = appointment.date;
        document.getElementById('editTime').value = appointment.time;
        document.getElementById('editModal').classList.remove('hidden');
    }
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function cancelAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        // Simulate API call to cancel appointment
        const appointmentIndex = appointments.findIndex(apt => apt.id === appointmentId);
        if (appointmentIndex !== -1) {
            appointments[appointmentIndex].status = 'cancelled';
            updateDashboard();
        }
    }
}

function updateDashboard() {
    const clientAppointments = appointments.filter(apt => apt.clientId === currentClientId);
    
    // Split appointments into upcoming and past
    const today = new Date();
    const upcomingAppointments = clientAppointments.filter(apt => dateFormatter.isUpcoming(apt.date));
    const pastAppointments = clientAppointments.filter(apt => !dateFormatter.isUpcoming(apt.date));

    // Update upcoming appointments
    document.getElementById('upcomingAppointments').innerHTML = upcomingAppointments.length > 0
        ? upcomingAppointments.map(createAppointmentCard).join('')
        : '<p class="text-gray-500">Aucun rendez-vous à venir</p>';

    // Update past appointments
    document.getElementById('pastAppointments').innerHTML = pastAppointments.length > 0
        ? pastAppointments.map(createAppointmentCard).join('')
        : '<p class="text-gray-500">Aucun rendez-vous passé</p>';
}

// Handle edit form submission
document.getElementById('editForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const appointmentId = parseInt(document.getElementById('editAppointmentId').value);
    const newDate = document.getElementById('editDate').value;
    const newTime = document.getElementById('editTime').value;

    // Simulate API call to update appointment
    const appointmentIndex = appointments.findIndex(apt => apt.id === appointmentId);
    if (appointmentIndex !== -1) {
        appointments[appointmentIndex].date = newDate;
        appointments[appointmentIndex].time = newTime;
        closeEditModal();
        updateDashboard();
    }
});

// Initialize dashboard
document.addEventListener('DOMContentLoaded', () => {
    const currentClient = { id: 1, name: "Jean Dupont" }; // Simulated client data
    document.getElementById('clientName').textContent = currentClient.name;
    updateDashboard();
});
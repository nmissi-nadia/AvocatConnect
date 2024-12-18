// Current lawyer ID (simulated authentication)
const currentLawyerId = 1;

function createAppointmentCard(appointment) {
    const isPending = appointment.status === 'pending';
    const formattedDateTime = dateFormatter.formatDateTime(appointment.date, appointment.time);

    return `
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-lg">${appointment.clientName}</h3>
                    <p class="text-gray-600">${formattedDateTime}</p>
                    <p class="text-gray-600">Type: ${appointment.type}</p>
                </div>
                <div class="space-x-2">
                    ${isPending ? `
                        <button onclick="handleAppointment(${appointment.id}, 'confirmed')" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Accepter
                        </button>
                        <button onclick="handleAppointment(${appointment.id}, 'cancelled')"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Refuser
                        </button>
                    ` : `
                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-md">
                            ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
                        </span>
                    `}
                </div>
            </div>
        </div>
    `;
}

function handleAppointment(appointmentId, newStatus) {
    // Simulate API call to update appointment status
    const appointmentIndex = appointments.findIndex(apt => apt.id === appointmentId);
    if (appointmentIndex !== -1) {
        appointments[appointmentIndex].status = newStatus;
        updateDashboard();
    }
}

function updateDashboard() {
    const lawyerAppointments = appointments.filter(apt => apt.lawyerId === currentLawyerId);
    
    // Update pending appointments
    const pendingAppointments = lawyerAppointments.filter(apt => apt.status === 'pending');
    document.getElementById('pendingAppointments').innerHTML = pendingAppointments.length > 0
        ? pendingAppointments.map(createAppointmentCard).join('')
        : '<p class="text-gray-500">Aucune demande en attente</p>';

    // Update confirmed appointments
    const confirmedAppointments = lawyerAppointments.filter(apt => apt.status === 'confirmed');
    document.getElementById('confirmedAppointments').innerHTML = confirmedAppointments.length > 0
        ? confirmedAppointments.map(createAppointmentCard).join('')
        : '<p class="text-gray-500">Aucun rendez-vous confirmé</p>';
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', () => {
    const currentLawyer = lawyers.find(lawyer => lawyer.id === currentLawyerId);
    if (currentLawyer) {
        document.getElementById('lawyerName').textContent = currentLawyer.name;
    }
    updateDashboard();
});
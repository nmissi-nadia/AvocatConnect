const dateFormatter = {
    formatDate(dateStr) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateStr).toLocaleDateString('fr-FR', options);
    },

    formatTime(timeStr) {
        return timeStr.replace(':', 'h');
    },

    formatDateTime(dateStr, timeStr) {
        return `${this.formatDate(dateStr)} à ${this.formatTime(timeStr)}`;
    },

    isUpcoming(dateStr) {
        const appointmentDate = new Date(dateStr);
        const today = new Date();
        return appointmentDate >= today;
    }
};
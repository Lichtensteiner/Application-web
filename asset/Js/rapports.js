document.addEventListener("DOMContentLoaded", function() {
    fetch('./data.php')
        .then(response => response.json())
        .then(data => {
            // Mise à jour de la section Suivi des Factures
            const facturesList = document.querySelector('.dashboard-card:nth-child(3) .document-list');
            data.factures.forEach(facture => {
                const item = document.createElement('div');
                item.classList.add('document-item');
                item.innerHTML = `<span>Fact_${facture.id}</span> <span class="document-status status-${facture.status.toLowerCase()}">${facture.status}</span>`;
                facturesList.appendChild(item);
            });

            // Mise à jour de la section Suivi des Devis
            const devisList = document.querySelector('.dashboard-card:nth-child(4) .document-list');
            data.devis.forEach(devis => {
                const item = document.createElement('div');
                item.classList.add('document-item');
                item.innerHTML = `<span>Devis #Dev_${devis.id}</span> <span class="document-status status-${devis.status.toLowerCase()}">${devis.status}</span>`;
                devisList.appendChild(item);
            });

            // Mise à jour de la section Suivi des Paiements
            const paiementsList = document.querySelector('.dashboard-card:nth-child(5) .document-list');
            data.paiements.forEach(paiement => {
                const item = document.createElement('div');
                item.classList.add('document-item');
                item.innerHTML = `<span>Paiement #Paie_${paiement.id}</span> <span class="document-status status-${paiement.status.toLowerCase()}">${paiement.status}</span>`;
                paiementsList.appendChild(item);
            });

            // Mise à jour de la section Emails Envoyés
            const emailsList = document.querySelector('.dashboard-card:nth-child(6) .email-list');
            data.emails.forEach(email => {
                const item = document.createElement('div');
                item.classList.add('email-item');
                item.innerHTML = `<div class="email-subject">${email.subject}</div> <div class="email-date">Envoyé le: ${new Date(email.sent_date).toLocaleDateString()}</div>`;
                emailsList.appendChild(item);
            });
        })
        .catch(error => console.error('Erreur:', error));
});

document.getElementById('addItemBtn').addEventListener('click', function() {
    const itemsContainer = document.getElementById('itemsContainer');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td><input type="text" name="description[]" required></td>
        <td><input type="number" name="quantity[]" required onchange="updateTotals(this)"></td>
        <td><input type="number" name="unitPrice[]" step="0.01" required onchange="updateTotals(this)"></td>
        <td><input type="text" name="total_ht[]" readonly></td>
        <td><input type="text" name="total_tva[]" readonly></td>
        <td><input type="text" name="total_ttc[]" readonly></td>
    `;

    itemsContainer.appendChild(newRow);
});

function updateTotals(input) {
    const row = input.closest('tr');
    const quantity = row.querySelector('input[name="quantity[]"]').value;
    const unitPrice = row.querySelector('input[name="unitPrice[]"]').value;

    const totalHT = quantity * unitPrice;
    const totalTVA = totalHT * 0.20; // TVA de 20 %
    const totalTTC = totalHT + totalTVA;

    row.querySelector('input[name="total_ht[]"]').value = totalHT.toFixed(2);
    row.querySelector('input[name="total_tva[]"]').value = totalTVA.toFixed(2);
    row.querySelector('input[name="total_ttc[]"]').value = totalTTC.toFixed(2);
}

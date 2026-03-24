document.addEventListener('DOMContentLoaded', () => {
  const typeSelect = document.getElementById('type');
  const categorySelect = document.getElementById('category');

  function handleTypeChange() {
    if (!typeSelect || !categorySelect) return;

    if (typeSelect.value === 'income') {
      categorySelect.value = 'salary';
      categorySelect.disabled = true;
    } else {
      categorySelect.disabled = false;
    }
  }

  if (typeSelect) {
    typeSelect.addEventListener('change', handleTypeChange);
    handleTypeChange();
  }

  if (typeof expenseLabels !== 'undefined' && expenseLabels.length > 0) {
    const ctx1 = document.getElementById('expenseChart').getContext('2d');
    new Chart(ctx1, {
      type: 'doughnut',
      data: {
        labels: expenseLabels,
        datasets: [{
          data: expenseData,
          backgroundColor: ['#0d6efd','#198754','#dc3545','#fd7e14','#6f42c1','#20c997']
        }]
      },
      options: {
        plugins: { legend: { position: 'bottom' } }
      }
    });
  }

  if (typeof trendLabels !== 'undefined' && trendLabels.length > 0) {
    const ctx2 = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: trendLabels,
        datasets: [
          { label: 'Income',  data: trendIncome,  backgroundColor: '#198754' },
          { label: 'Expense', data: trendExpense, backgroundColor: '#dc3545' }
        ]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  }
});

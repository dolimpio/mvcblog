class ExpensesService {
  constructor() {

  }

  findAllExpenses() {
    return $.get(AppConfig.backendServer + '/rest/expense');
  }

  getPieChart(dates) {
    return $.get(AppConfig.backendServer + '/rest/expense/piechart/' + dates);
  }

  getLineChart(dates) {
    return $.get(AppConfig.backendServer + '/rest/expense/linechart/' + dates);
  }

  findExpense(id) {
    return $.get(AppConfig.backendServer + '/rest/expense/' + id);
  }

  deleteExpense(id) {
    return $.ajax({
      url: AppConfig.backendServer + '/rest/expense/' + id,
      method: 'DELETE'
    });
  }

  saveExpense(expense) {

    return $.ajax({
      url: AppConfig.backendServer + '/rest/expense/' + expense.id,
      method: 'PUT',
      data: JSON.stringify(expense),
      contentType: 'application/json'
    });
  }

  addExpense(expense) {

    return $.ajax({
      url: AppConfig.backendServer + '/rest/expense',
      method: 'POST',
      data: JSON.stringify(expense),
      contentType: 'application/json'
    });

  }



}

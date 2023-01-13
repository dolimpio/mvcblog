class ExpensesService {
  constructor() {

  }

  downloadCsv(){
    return $.get(AppConfig.backendServer+'/rest/expense/csv');
  }
  findAllExpenses() {
    return $.get(AppConfig.backendServer+'/rest/expense');
  }

  findExpense(id) {
    return $.get(AppConfig.backendServer+'/rest/expense/' + id);
  }

  deleteExpense(id) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/expense/' + id,
      method: 'DELETE'
    });
  }

  saveExpense(expense) {
    console.log("Esto es un file de un expense en el expense-service " + expense.expense_file);
    console.log("Esto es un expense STRING en el expense-service " + JSON.stringify(expense));

    return $.ajax({
      url: AppConfig.backendServer+'/rest/expense/' + expense.id,
      method: 'PUT',
      data: JSON.stringify(expense),
      contentType: 'application/json'
    });
  }

  addExpense(expense) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/expense',
      method: 'POST',
      data: JSON.stringify(expense),
      contentType: 'application/json'
    });

  }
  
  

}

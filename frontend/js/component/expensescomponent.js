class ExpensesComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expensestable, expensesModel, null, null);
    
    this.expensesModel = expensesModel;
    this.userModel = userModel;
    this.addModel('user', userModel);
    this.router = router;

    this.expensesService = new ExpensesService();
    this.addEventListener('click', '#downloadCsv', () => {
      this.downloadCSV(this.expensesInCSV(),"expensesInCSV.csv")
    }); //se cierra el addEventListener
  }

  onStart() {
    this.updateExpenses();
  }
  expensesInCSV(){
    var expenses = this.expensesModel.expenses;
  
    for (var i=0; i < expenses.length; i++) {
      delete expenses[i].observers;
      delete expenses[i].name;
      delete expenses[i].id;
    }
      let csvDownload = '';
      let header = Object.keys(expenses[0]).join(',');
      let values = expenses.map(o => Object.values(o).join(',')).join('\n');
      csvDownload += header + '\n' + values;

    return(csvDownload); 
  }
  downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;
    csvFile = new Blob([csv], {type: "text/csv"});
    downloadLink = document.getElementById("downloadLink");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.click();
  } 
  updateExpenses() {
    this.expensesService.findAllExpenses().then((data) => {

      this.expensesModel.setExpenses(
        // create a Fronty.Model for each item retrieved from the backend
        data.map(
          (item) => new ExpenseModel(item.id, item.expense_type, item.expense_date, item.expense_quantity, item.expense_description, item.expense_file, item.expense_owner)
      ));
    });
  }
  
  createChildModelComponent(className, element, id, modelItem) {
    return new ExpenseRowComponent(modelItem, this.userModel, this.router, this);
  }
}

class ExpenseRowComponent extends Fronty.ModelComponent {
  constructor(expenseModel, userModel, router, expensesComponent) {
    super(Handlebars.templates.expenserow, expenseModel, null, null);
    
    this.expensesComponent = expensesComponent;
    
    this.userModel = userModel;
    this.addModel('user', userModel); // a secondary model
    
    this.router = router;

    this.addEventListener('click', '.remove-button', (event) => {
      if (confirm(I18n.translate('Are you sure?'))) {
        var expenseId = event.target.getAttribute('item');
        this.expensesComponent.expensesService.deleteExpense(expenseId)
          .fail(() => {
            alert('expense cannot be deleted')
          })
          .always(() => {
            this.expensesComponent.updateExpenses();
          });
      }
    });

    this.addEventListener('click', '.edit-button', (event) => {
      var expenseId = event.target.getAttribute('item');
      this.router.goToPage('edit-expense?id=' + expenseId);
    });

    
  }

}

class ExpenseAddComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expenseEdit, expensesModel);
    this.expensesModel = expensesModel; // expenses
    
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.expensesService = new ExpensesService();

    this.addEventListener('click', '#savebutton', () => {
      var newExpense = {};
      newExpense.type = $('#type').val();
      newExpense.date = $('#date').val();
      newExpense.quantity = $('#quantity').val();
      //que pasa si estan vacios??? funciona???
      newExpense.description = $('#description').val();
      newExpense.file = $('#file').val();
      newExpense.owner = this.userModel.currentUser;

      this.ExpensesService.addExpense(newExpense)
        .then(() => {
          this.router.goToPage('expenses');
        })
        .fail((xhr, errorThrown, statusText) => {
          if (xhr.status == 400) {
            this.expensesModel.set(() => {
              this.expensesModel.errors = xhr.responseJSON;
            });
          } else {
            alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
          }
        });
    });
  }
  
  onStart() {
    this.expensesModel.setSelectedExpense(new ExpenseModel());
  }
}

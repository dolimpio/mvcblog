class ExpenseEditComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expenseedit, expensesModel);
    this.expensesModel = expensesModel; // expenses
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.expensesService = new ExpensesService();

    console.log("prueba con selected expense " +this.expensesModel.selectedExpense);

    this.addEventListener('click', '#savebutton', () => {
      this.expensesModel.selectedExpense.expense_type = $('#type').val();
      this.expensesModel.selectedExpense.expense_date = $('#date').val();
      this.expensesModel.selectedExpense.expense_quantity = $('#quantity').val();
      console.log("este es el file en el edit-component "+ $('#file').val());
      this.expensesModel.selectedExpense.expense_file = $('#file').val();

      this.expensesModel.selectedExpense.expense_description = $('#description').val();

      this.expensesService.saveExpense(this.expensesModel.selectedExpense)
        .then(() => {
          this.expensesModel.set((model) => {
            model.errors = []
          });
          this.router.goToPage('expenses');
        })
        .fail((xhr, errorThrown, statusText) => {
          if (xhr.status == 400) {
            this.expensesModel.set((model) => {
              model.errors = xhr.responseJSON;
            });
          } else {
            alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
          }
        });

    });

    this.addEventListener('click', '#cancelbutton', () => {
      
      this.router.goToPage('expenses');
    });
  }

  onStart() {
    var selectedId = this.router.getRouteQueryParam('id');
    console.log("me llega un expense nulo? " + selectedId);
    if (selectedId != null) {
      this.expensesService.findExpense(selectedId)
        .then((expense) => {
          console.log("el expense que ha llegado es del tipo = " + expense.expense_type);
          console.log("el expense que ha llegado tiene el archivo = " + expense.expense_file);
          this.expensesModel.setSelectedExpense(expense);
        });
    }
  }

}

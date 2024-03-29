class ExpenseEditComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expenseedit, expensesModel);
    this.expensesModel = expensesModel; // expenses
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.expensesService = new ExpensesService();

    this.addEventListener('click', '#savebutton', (event) => {
      this.expensesModel.selectedExpense.expense_type = $('#type').val();
      this.expensesModel.selectedExpense.expense_date = $('#date').val();
      this.expensesModel.selectedExpense.expense_quantity = $('#quantity').val();
      let file = $('#file').val();
      file = file.substring($('#file').val().lastIndexOf("\\") + 1);
      this.expensesModel.selectedExpense.expense_file = file;

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

    this.addEventListener('click', '#cancelbutton', (event) => {

      this.router.goToPage('expenses');
    });
  }

  onStart() {

    var selectedId = this.router.getRouteQueryParam('id');
    if (selectedId != null) {
      this.expensesService.findExpense(selectedId)
        .then((expense) => {
          this.expensesModel.setSelectedExpense(expense);
        });
    }
  }

}

class ExpenseViewComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expenseview, expensesModel);

    this.expensesModel = expensesModel; // expenses
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.expensesService = new ExpensesService();
  }

  onStart() {
    var selectedId = this.router.getRouteQueryParam('id');
    this.loadExpense(selectedId);
  }

  loadExpense(expenseId) {
    if (expenseId != null) {
      this.expensesService.findExpense(expenseId)
        .then((expense) => {
          this.expensesModel.setSelectedExpense(expense);
        });
    }
  }
}

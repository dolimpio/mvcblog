class ExpenseViewComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expenseview, expensesModel);

    this.expensesModel = expensesModel; // expenses
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.expensesService = new ExpensesService();

    //there isn't comments on our app

    // this.addEventListener('click', '#savecommentbutton', () => {
    //   var selectedId = this.router.getRouteQueryParam('id');
    //   this.expensesService.createComment(selectedId, {
    //       content: $('#commentcontent').val()
    //     })
    //     .then(() => {
    //       $('#commentcontent').val('');
    //       this.loadExpense(selectedId);
    //     })
    //     .fail((xhr, errorThrown, statusText) => {
    //       if (xhr.status == 400) {
    //         this.expensesModel.set(() => {
    //           this.expensesModel.commentErrors = xhr.responseJSON;
    //         });
    //       } else {
    //         alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
    //       }
    //     });
    // });
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

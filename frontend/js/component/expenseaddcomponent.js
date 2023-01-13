class ExpenseAddComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expenseedit, expensesModel);
    this.expensesModel = expensesModel; // expenses
    
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.ExpensesService = new ExpensesService();

    this.addEventListener('click', '#savebutton', () => {
      var newExpense = {};
      newExpense.expense_type = $('#type').val();
      newExpense.expense_date = $('#date').val();
      newExpense.expense_quantity = $('#quantity').val();
      //que pasa si estan vacios??? funciona???
      newExpense.expense_description = $('#description').val();
      newExpense.expense_file = $('#file').val();
      newExpense.expense_owner = this.userModel.currentUser;

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

    this.addEventListener('click', '#cancelbutton', () => {
      
      this.router.goToPage('expenses');
    });
  }
  
  onStart() {
    var fecha = new Date(); //Fecha actual
    var mes = fecha.getMonth()+1; //obteniendo mes
    var dia = fecha.getDate(); //obteniendo dia
    var ano = fecha.getFullYear(); //obteniendo año
    if(dia<10)
      dia='0'+dia; //agrega cero si el menor de 10
    if(mes<10)
      mes='0'+mes //agrega cero si el menor de 10
      document.getElementById('date').value=ano+"-"+mes+"-"+dia;
    this.expensesModel.setSelectedExpense(new ExpenseModel());
  }


}

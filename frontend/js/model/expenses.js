class ExpensesModel extends Fronty.Model {

  constructor() {
    super('ExpensesModel'); //call super

    // model attributes
    this.expenses = [];
  }

  setSelectedExpense(expense) {
    this.set((self) => {
      self.selectedExpense = expense;
    });
  }

  setExpenses(expenses) {
    this.set((self) => {
      self.expenses = expenses;
    });
  }
}

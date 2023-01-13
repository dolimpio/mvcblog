class ExpensesModel extends Fronty.Model {

  constructor() {
    super('ExpensesModel'); 
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

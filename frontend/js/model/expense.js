class ExpenseModel extends Fronty.Model {

  constructor(id, expense_type, expense_date, expense_quantity, expense_description, expense_file, owner) {
    super('ExpenseModel'); //call super
    
    if (id) {
      this.id = id;
    }
    
    if (expense_type) {
      this.expense_type = expense_type;
    }

    if (expense_date) {
      this.expense_date = expense_date;
    } 

    if (expense_quantity) {
      this.expense_quantity = expense_quantity;
    }

    if (expense_description) {
      this.expense_description = expense_description;
    }

    if (expense_file) {
      this.expense_file = expense_file;
    }

    if (owner) {
      this.owner = owner;
    }
  }

  setType(expense_type) {
    this.set((self) => {
      self.expense_type = expense_type;
    });
  }

  setDate(expense_date) {
    this.set((self) => {
      self.expense_date = expense_date;
    });
  }

  setQuantity(expense_quantity) {
    this.set((self) => {
      self.expense_quantity = expense_quantity;
    });
  }

  setDescription(expense_description) {
    this.set((self) => {
      self.expense_description = expense_description;
    });
  }

  setFile(expense_file) {
    this.set((self) => {
      self.expense_file = expense_file;
    });
  }

  setOwner(owner) {
    this.set((self) => {
      self.owner = owner;
    });
  }
}

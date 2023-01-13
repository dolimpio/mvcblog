class ExpensesComponent extends Fronty.ModelComponent {
  constructor(expensesModel, userModel, router) {
    super(Handlebars.templates.expensestable, expensesModel, null, null);
    
    this.expensesModel = expensesModel;
    this.userModel = userModel;
    this.addModel('user', userModel);
    this.router = router;

    this.expensesService = new ExpensesService();

    this.addEventListener('click', '#sortbuttonbydate', (event) => {
      if(document.getElementById("expensesTable").id == "expensesTable" ){
        console.log(this.flanco);
      }
      this.sortTableByDate();
    });

    this.addEventListener('click', '#sortbuttonbytype', (event) => {
      if(document.getElementById("expensesTable").id == "expensesTable" ){
        console.log(this.flanco);
      }
      this.sortTableByType();
    });

    this.addEventListener('click', '#sortbuttonbyquantity', (event) => {
      if(document.getElementById("expensesTable").id == "expensesTable" ){
        console.log(this.flanco);
      }
      this.sortTableByQuantity();
    });

    this.flancodate = true;
    this.flancotype = true;
    this.flancoquantity = true;
    
  }

  onStart() {
    this.updateExpenses();
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

  
  sortTableByDate(){
    let table = document.getElementById("expensesTable");
    let rows = table.rows;
    let rowArray = [];
    for (let i = 1; i < rows.length; i++) {
        rowArray.push(rows[i]);
    }
    if(this.flancodate){
      rowArray.sort(function(a, b) {
        let dateA = new Date(a.cells[1].innerHTML);
        let dateB = new Date(b.cells[1].innerHTML);
        return dateA - dateB;
      });
      this.flancodate=!this.flancodate;
    }else{
      rowArray.sort(function(a, b) {
        let dateA = new Date(a.cells[1].innerHTML);
        let dateB = new Date(b.cells[1].innerHTML);
        return dateB - dateA;
      })
      this.flancodate=!this.flancodate;
    }
    for (let i = 0; i < rowArray.length; i++) {
        table.appendChild(rowArray[i]);
    }
}

sortTableByType(){
  var table = document.getElementById("expensesTable");
  var rows = table.rows;
  var rowArray = [];
  
  for (let i = 1; i < rows.length; i++) {
      rowArray.push(rows[i]);
  }
  if(this.flancotype){
    rowArray.sort(function(a, b) {
      let typeA = a.getElementsByTagName("TD")[0].getElementsByTagName("a")[0].innerHTML.toLowerCase();
      let typeB = b.getElementsByTagName("TD")[0].getElementsByTagName("a")[0].innerHTML.toLowerCase();
      return typeA > typeB ? 1 : -1;
    });
    this.flancotype = !this.flancotype;
  }else{
    rowArray.sort(function(a, b) {
      let typeA = a.getElementsByTagName("TD")[0].getElementsByTagName("a")[0].innerHTML.toLowerCase();
      let typeB = b.getElementsByTagName("TD")[0].getElementsByTagName("a")[0].innerHTML.toLowerCase();
      return typeA < typeB ? 1 : -1;
    });
    this.flancotype = !this.flancotype;
  }
  for (let i = 0; i < rowArray.length; i++) {
      table.appendChild(rowArray[i]);
  }
}

sortTableByQuantity(){
  let table = document.getElementById("expensesTable");
  let rows = table.rows;
  let rowArray = [];
  for (let i = 1; i < rows.length; i++) {
      rowArray.push(rows[i]);
  }
  if(this.flancotype){
    rowArray.sort(function(a, b) {
      let dateA = new Date(a.cells[2].innerHTML);
      let dateB = new Date(b.cells[2].innerHTML);
      return dateA - dateB;
    });
    this.flancotype=!this.flancotype;
  }else{
    rowArray.sort(function(a, b) {
      let dateA = new Date(a.cells[2].innerHTML);
      let dateB = new Date(b.cells[2].innerHTML);
      return dateB - dateA;
    })
    this.flancotype=!this.flancotype;
  }
  for (let i = 0; i < rowArray.length; i++) {
      table.appendChild(rowArray[i]);
  }
}

  // Override
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

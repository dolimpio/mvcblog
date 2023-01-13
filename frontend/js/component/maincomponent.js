class MainComponent extends Fronty.RouterComponent {
  constructor() {
    super('frontyapp', Handlebars.templates.main, 'maincontent');

    // models instantiation
    // we can instantiate models at any place
    this.userModel = new UserModel();
    this.expensesModel = new ExpensesModel();
    this.userService = new UserService();
    this.expensesService = new ExpensesService();
    this.counterModel = new Counter();


    super.setRouterConfig({
      expenses: {
        component: new ExpensesComponent(this.expensesModel, this.userModel, this),
        title: 'Expenses'
      },
      'view-expense': {
        component: new ExpenseViewComponent(this.expensesModel, this.userModel, this),
        title: 'Expense'
      },
      'edit-expense': {
        component: new ExpenseEditComponent(this.expensesModel, this.userModel, this),
        title: 'Edit Expense'
      },
      'add-expense': {
        component: new ExpenseAddComponent(this.expensesModel, this.userModel, this),
        title: 'Add Expense'
      },
      login: {
        component: new LoginComponent(this.userModel, this),
        title: 'Login'
      },
      charts: {
        component: new CounterComponent(this.counterModel, this),
        title: 'Charts'
      },
      defaultRoute: 'expenses'
    });

    Handlebars.registerHelper('currentPage', () => {
          return super.getCurrentPage();
    });

    this.addChildComponent(this._createUserBarComponent());
    this.addChildComponent(this._createLanguageComponent());

  }

  start() {
    // override the start() function in order to first check if there is a logged user
    // in sessionStorage, so we try to do a relogin and start the main component
    // only when login is checked
    this.userService.loginWithSessionData()
      .then((logged) => {
        if (logged != null) {
          this.userModel.setLoggeduser(logged);
        }
        super.start(); // now we can call start
      });
  }

  _createUserBarComponent() {
    var userbar = new Fronty.ModelComponent(Handlebars.templates.user, this.userModel, 'userbar');

    userbar.addEventListener('click', '#logoutbutton', () => {
      this.userModel.logout();
      this.userService.logout();
    });

    userbar.addEventListener('click', '#deleteuserbutton', () => {
      console.log("dentro del main "+ this.userModel.currentUser);
      var userToDelete = this.userModel.currentUser
      this.userModel.deleteuser();
      this.userService.deleteUser(userToDelete);

    });

    userbar.addEventListener('click', '#downloadCsv', () => {
      console.log("sE HA ESCUCHACHO E CLICK DE DESCRAGAR EN EL COMPONENT");
      var userForDownload = this.userModel.currentUser;
      var expenses = this.expensesModel.expenses;

      for (var i=0; i < expenses.length; i++) {
        delete expenses[i].observers;
        delete expenses[i].name;
        delete expenses[i].id;
      }
      let csvDownload = '';
      let header = Object.keys(expenses[0]).join(',');
      let values = expenses.map(o => Object.values(o).join(',')).join('\n');
      csvDownload += header + '\n' + values;
      console.log(csvDownload);
      downloadCSV(csvDownload,"expensesInCSV.csv")
      });

      function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;
        csvFile = new Blob([csv], {type: "text/csv"});
        downloadLink = document.getElementById("downloadLink");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }
    return userbar;
  }

  _createLanguageComponent() {
    var languageComponent = new Fronty.ModelComponent(Handlebars.templates.language, this.routerModel, 'languagecontrol');
    // language change links
    languageComponent.addEventListener('click', '#englishlink', () => {
      I18n.changeLanguage('default');
      document.location.reload();
    });

    languageComponent.addEventListener('click', '#spanishlink', () => {
      I18n.changeLanguage('es');
      document.location.reload();
    });

    return languageComponent;
  }
}

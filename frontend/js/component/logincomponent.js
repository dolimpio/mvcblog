class LoginComponent extends Fronty.ModelComponent {
  constructor(userModel, router) {
    super(Handlebars.templates.login, userModel);
    this.userModel = userModel;
    this.userService = new UserService();
    this.router = router;

    this.addEventListener('click', '#loginbutton', (event) => {

      var selected = false;
      if ($('#rememberCheck').is(':checked')) {
        selected = true;
      }

      if (!selected) {
        this.userService.login($('#login').val(), $('#password').val())
          .then(() => {
            this.router.goToPage('charts');
            this.userModel.setLoggeduser($('#login').val());
          })
          .catch((error) => {
            this.userModel.set((model) => {
              model.loginError = error.responseText;
            });
            this.userModel.logout();
          });
      } else {
        this.userService.loginCookies($('#login').val(), $('#password').val())
          .then(() => {
            this.router.goToPage('expenses');
            this.userModel.setLoggeduser($('#login').val());
          })
          .catch((error) => {
            this.userModel.set((model) => {
              model.loginError = error.responseText;
            });
            this.userModel.logout();
          });
      }
    });


    this.addEventListener('click', '#registerlink', (event) => {
      this.userModel.set(() => {
        this.userModel.registerMode = true;
      });
    });

    this.addEventListener('click', '#registerbutton', (event) => {
      this.userService.register({
        username: $('#registerusername').val(),
        password: $('#registerpassword').val(),
        email: $('#registeremailname').val()

      })
        .then(() => {
          alert(I18n.translate('User registered! Please login'));
          this.userModel.set((model) => {
            model.registerErrors = {};
            model.registerMode = false;
          });
        })
        .fail((xhr, errorThrown, statusText) => {
          if (xhr.status == 400) {
            this.userModel.set(() => {
              this.userModel.registerErrors = xhr.responseJSON;
            });
          } else {
            alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
          }
        });
    });
  }
}

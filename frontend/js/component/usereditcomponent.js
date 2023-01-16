class UserEditComponent extends Fronty.ModelComponent {
  constructor(userModel, router) {
    super(Handlebars.templates.useredit, userModel);
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.userService = new UserService();

    this.addEventListener('click', '#cancelbutton', () => {
      
      this.router.goToPage('charts');
    });

    this.addEventListener('click', '#savebutton', () => {
      
      this.userService.editUser({
        username: $('#editusername').val(),
        password: $('#editpassword').val(),
        email: $('#editemailname').val()
      })
      .then(() => {
        this.userModel.set((model) => {
          model.registerErrors = {};
          model.registerMode = false;
        });
        this.userService.logout();
        router.goToPage('login');
        alert(I18n.translate('User modified! Please login'));

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

  onStart() {
    console.log(this.userModel.currenUser);
  }

}

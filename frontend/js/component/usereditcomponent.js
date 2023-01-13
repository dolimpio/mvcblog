class UserEditComponent extends Fronty.ModelComponent {
  constructor(userModel, router) {
    super(Handlebars.templates.useredit, userModel);
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.userService = new UserService();


    this.addEventListener('click', '#savebutton', () => {
      
      this.userService.register({
        username: $('#editusername').val(),
        password: $('#editpassword').val(),
        email: $('#editemailname').val()
      })
      .then(() => {
        this.router.goToPage('expenses');
        alert(I18n.translate('User modified! Please login'));
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

    this.addEventListener('click', '#cancelbutton', () => {
      
      this.router.goToPage('expenses');
    });
  }

  onStart() {
  }

}

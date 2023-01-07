class UserService {
  constructor() {

  }

  loginWithSessionData() {
    var self = this;
    return new Promise((resolve, reject) => {
      if (window.sessionStorage.getItem('login') &&
        window.sessionStorage.getItem('pass')) {
        self.login(window.sessionStorage.getItem('login'), window.sessionStorage.getItem('pass'))
          .then(() => {
            resolve(window.sessionStorage.getItem('login'));
          })
          .catch(() => {
            reject();
          });
      } else {
        resolve(null);
      }
    });
  }

  login(login, pass) {
    return new Promise((resolve, reject) => {

      $.get({
          url: AppConfig.backendServer+'/rest/user/' + login,
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
          }
        })
        .then(() => {
          //keep this authentication forever
          window.sessionStorage.setItem('login', login);
          window.sessionStorage.setItem('pass', pass);
          $.ajaxSetup({
            beforeSend: (xhr) => {
              xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
            }
          });
          resolve();
        })
        .fail((error) => {
          window.sessionStorage.removeItem('login');
          window.sessionStorage.removeItem('pass');
          $.ajaxSetup({
            beforeSend: (xhr) => {}
          });
          reject(error);
        });
    });
  }

  logout() {
    window.sessionStorage.removeItem('login');
    window.sessionStorage.removeItem('pass');
    $.ajaxSetup({
      beforeSend: (xhr) => {}
    });
  }

  register(user) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/user',
      method: 'POST',
      data: JSON.stringify(user),
      contentType: 'application/json'
    });
  }

  deleteUser(user) {
    console.log("dentro del service " + user)
    return $.ajax({
      url: AppConfig.backendServer+'/rest/user/' + user,
      method: 'DELETE'
    });
  }

//Opcion de nuestro amigo
//  deleteUser(userId) {
//     fetch(/api/users/${userId}, {
//       method: 'DELETE',
//     })
//       .then(response => {
//         if (response.ok) {
//           // Remove the user from the list of users displayed on the page
//           // Show a message to confirm that the deletion was successful
//           return;
//         }
//         throw new Error('Error deleting user');
//       })
//       .catch(error => {
//         // Handle the error
//       });
//   }


}

/* Main mvcblog-front script */

//load external resources
function loadTextFile(url) {
  return new Promise((resolve, reject) => {
    $.get({
      url: url,
      cache: true,
      beforeSend: function( xhr ) {
        xhr.overrideMimeType( "text/plain" );
      }
    }).then((source) => {
      resolve(source);
    }).fail(() => reject());
  });
}


// Configuration
var AppConfig = {
  backendServer: 'http://localhost/mvcblog'
  // backendServer: '/mvcblog'
}

Handlebars.templates = {};
Promise.all([
    I18n.initializeCurrentLanguage('js/i18n'),
    loadTextFile('templates/components/main.hbs').then((source) =>
      Handlebars.templates.main = Handlebars.compile(source)),
    loadTextFile('templates/components/language.hbs').then((source) =>
      Handlebars.templates.language = Handlebars.compile(source)),
    loadTextFile('templates/components/user.hbs').then((source) =>
      Handlebars.templates.user = Handlebars.compile(source)),
    loadTextFile('templates/components/login.hbs').then((source) =>
      Handlebars.templates.login = Handlebars.compile(source)),
    loadTextFile('templates/components/expenses-table.hbs').then((source) =>
      Handlebars.templates.expensestable = Handlebars.compile(source)),
    loadTextFile('templates/components/expense-edit.hbs').then((source) =>
      Handlebars.templates.expenseedit = Handlebars.compile(source)),
    loadTextFile('templates/components/expense-view.hbs').then((source) =>
      Handlebars.templates.expenseview = Handlebars.compile(source)),
    loadTextFile('templates/components/expense-row.hbs').then((source) =>
      Handlebars.templates.expenserow = Handlebars.compile(source)),
    loadTextFile('templates/components/counter.hbs').then((source) =>
      Handlebars.templates.counter = Handlebars.compile(source))
  ])
  .then(() => {
    $(() => {
      new MainComponent().start();
    });
  }).catch((err) => {
    alert('FATAL: could not start app ' + err);
  });

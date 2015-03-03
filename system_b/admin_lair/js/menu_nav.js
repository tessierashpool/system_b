Todos = Ember.Application.create();
Todos.Router.map(function() {
  this.resource('todos');
  this.resource('create_db');
  this.resource('users');
  this.resource('settings');
});
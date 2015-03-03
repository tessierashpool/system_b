Todos = Ember.Application.create();
Todos.Router.map(function() {
  this.resource('todos');
  this.resource('users');
  this.resource('create_db');
});
# Concept of Views

When we talk about views we like to think about the V in MVC. For webforge views are some kind of service that renders the model into a displayable concept. When it comes to templates most MVC frameworks try to merge the definition of a view with the definition of a template. So a template does everything a view would do: generate URLs, translate strings, maybe query some business objects, etc. We think that this makes templates to complex and to difficult to be changed from non-developers. Thats why we seperated the concept of a view into two parts: the ViewModel and the Template.
If you look at mustache templates and its reduced syntax you'll have a great idea what we mean with a `Template`. Its not a view because it's to dumb for it!
Let's have a look how these dumb templates improve your code quality:

```php
<?php if (isset($page->sidebar)): ?>
<div id="sidebar">
  <!-- the sidebar html -->
</div>
<?php endif; ?>
```
Mainly most template languages that will allow ifs and other logic in your templates will compile to something like the above. At least this is still readable, but whats the problem here?  
The template is suddenly coupled to the fact, that the page variable as an object does have a property `sidebar`. That looks not very terrifying, but think about a scenario where you have set the sidebar but you want to suddenly not display it. Well - you would have to change your template. Now look at this mustache template:

```php
{{#page.hasSidebar}}
<div id="sidebar">
  <!-- the sidebar html -->
</div>
{{/page}}
```

`hasSidebar` is a boolean template variable here. This now ensures that the variable-providing facility of the template has the full control wether the template should display the sidebar or not. 

## Adding a new Layer to the MVC framework

So basically we have a MVTC framework. Model, View, Template, Controller. Templates are dumb as hell and views do all the business logic to make a template display the model. The controller still connects the model with the view.